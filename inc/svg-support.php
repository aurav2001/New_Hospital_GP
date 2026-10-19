<?php
/**
 * SVG Upload Support & Sanitization for GP Healthcare Theme.
 *
 * Enables safe uploading of SVG images in WordPress media library,
 * theme customizer (custom logo), and hospital settings while preventing
 * XSS / XML entity expansion attacks and bypassing GD/Imagick sub-size errors.
 *
 * @package GPHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allow SVG and SVGZ MIME types in WordPress upload list.
 *
 * @param array $mimes Existing mime types.
 * @return array
 */
function bs_svg_upload_mimes( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'bs_svg_upload_mimes' );

/**
 * Ensure WordPress recognizes SVG extension and mime-type correctly.
 *
 * Prevents "Sorry, you are not allowed to upload this file type" and
 * "This file cannot be processed by the web server" caused by finfo mismatch.
 *
 * @param array       $data File data array.
 * @param string      $file Full path to uploaded file.
 * @param string      $filename Filename.
 * @param array|null  $mimes Array of mime types.
 * @return array
 */
function bs_svg_check_filetype_and_ext( $data, $file, $filename, $mimes ) {
	$ext = isset( $data['ext'] ) ? $data['ext'] : '';
	if ( empty( $ext ) ) {
		$ext = pathinfo( $filename, PATHINFO_EXTENSION );
	}
	$ext = strtolower( $ext );

	if ( 'svg' === $ext || 'svgz' === $ext ) {
		$data['ext']  = $ext;
		$data['type'] = 'image/svg+xml';
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'bs_svg_check_filetype_and_ext', 10, 4 );

/**
 * Sanitize SVG content on upload before it is written to uploads directory.
 *
 * Strips script tags, entity expansions (XXE), and malicious event handlers.
 *
 * @param array $file Uploaded file information.
 * @return array
 */
function bs_svg_sanitize_upload( $file ) {
	if ( ! isset( $file['tmp_name'] ) || ! file_exists( $file['tmp_name'] ) ) {
		return $file;
	}

	$filename = isset( $file['name'] ) ? $file['name'] : '';
	$ext      = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
	if ( 'svg' !== $ext && 'svgz' !== $ext ) {
		return $file;
	}

	$content = @file_get_contents( $file['tmp_name'] );
	if ( false === $content ) {
		return $file;
	}

	// If gzipped SVG (svgz), decompress first.
	$is_gzipped = 0 === strpos( $content, "\x1f\x8b\x08" );
	if ( $is_gzipped && function_exists( 'gzdecode' ) ) {
		$decoded = @gzdecode( $content );
		if ( false !== $decoded ) {
			$content = $decoded;
		}
	}

	// Validate minimal SVG tag presence.
	if ( false === stripos( $content, '<svg' ) ) {
		$file['error'] = __( 'Invalid SVG file structure.', 'bshealthcare' );
		return $file;
	}

	// Strip DOCTYPE and ENTITY declarations to protect against XML External Entity (XXE) attacks.
	$content = preg_replace( '/<!ENTITY[\s\S]*?>/i', '', $content );
	$content = preg_replace( '/<!DOCTYPE[\s\S]*?>/i', '', $content );

	// Strip executable script elements.
	$content = preg_replace( '/<script[\s\S]*?<\/script>/i', '', $content );
	$content = preg_replace( '/<script[\s\S]*?\/>/i', '', $content );

	// Strip malicious inline JS handlers (onload, onclick, onerror, onmouseover, etc.).
	$content = preg_replace( '/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $content );

	// Neutralize dangerous javascript: and data: URI links.
	$content = preg_replace( '/(href|xlink:href)\s*=\s*["\']\s*(javascript|vbscript):[^"\']*["\']/i', '$1="#"', $content );

	if ( $is_gzipped && function_exists( 'gzencode' ) ) {
		$content = gzencode( $content );
	}

	@file_put_contents( $file['tmp_name'], $content );

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'bs_svg_sanitize_upload' );

/**
 * Inform WordPress that SVG is a displayable image.
 *
 * @param bool   $result Current check result.
 * @param string $path File path.
 * @return bool
 */
function bs_svg_is_displayable_image( $result, $path ) {
	if ( false === $result && ! empty( $path ) && preg_match( '/\.svgz?$/i', $path ) ) {
		return true;
	}
	return $result;
}
add_filter( 'file_is_displayable_image', 'bs_svg_is_displayable_image', 10, 2 );

/**
 * Prevent GD / ImageMagick from attempting to generate raster thumbnails for vector SVGs.
 *
 * This directly prevents the "This file cannot be processed by the web server" error.
 *
 * @param array $sizes Sub-sizes array.
 * @param array $image_meta Metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array
 */
function bs_svg_disable_subsizes( $sizes, $image_meta, $attachment_id ) {
	$file = get_attached_file( $attachment_id );
	if ( $file && preg_match( '/\.svgz?$/i', $file ) ) {
		return array();
	}
	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'bs_svg_disable_subsizes', 10, 3 );

/**
 * Extract SVG width and height from viewBox or width/height attributes so WordPress
 * metadata has valid dimensions instead of 0 or blank.
 *
 * @param array $metadata Attachment metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array
 */
function bs_svg_attachment_metadata( $metadata, $attachment_id ) {
	$mime = get_post_mime_type( $attachment_id );
	if ( 'image/svg+xml' === $mime ) {
		if ( ! is_array( $metadata ) ) {
			$metadata = array();
		}
		$file   = get_attached_file( $attachment_id );
		$width  = 0;
		$height = 0;

		if ( $file && file_exists( $file ) ) {
			$content = @file_get_contents( $file );
			if ( $content ) {
				// 1. Try explicit width and height on root <svg> tag.
				if ( preg_match( '/<svg[^>]*\bwidth=["\']([0-9.]+)(px)?["\']/i', $content, $wm ) &&
				     preg_match( '/<svg[^>]*\bheight=["\']([0-9.]+)(px)?["\']/i', $content, $hm ) ) {
					$width  = round( (float) $wm[1] );
					$height = round( (float) $hm[1] );
				}
				// 2. Fallback to viewBox: "minX minY width height".
				if ( ( ! $width || ! $height ) && preg_match( '/<svg[^>]*\bviewBox=["\']([0-9.-]+[\s,]+[0-9.-]+[\s,]+([0-9.]+)[\s,]+([0-9.]+))["\']/i', $content, $vm ) ) {
					$width  = round( (float) $vm[2] );
					$height = round( (float) $vm[3] );
				}
			}
		}

		$metadata['width']  = $width ? $width : 320;
		$metadata['height'] = $height ? $height : 80;
		$metadata['file']   = ! empty( $metadata['file'] ) ? $metadata['file'] : ( $file ? _wp_relative_upload_path( $file ) : '' );
		$metadata['sizes']  = array();
	}
	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'bs_svg_attachment_metadata', 10, 2 );

/**
 * Ensure media library JS objects contain full size and preview for SVGs.
 *
 * @param array       $response Prepared attachment data.
 * @param WP_Post     $attachment Attachment post.
 * @param array|false $meta Attachment meta.
 * @return array
 */
function bs_svg_prepare_for_js( $response, $attachment, $meta ) {
	if ( isset( $response['mime'] ) && 'image/svg+xml' === $response['mime'] ) {
		$url    = ! empty( $response['url'] ) ? $response['url'] : wp_get_attachment_url( $attachment->ID );
		$width  = ! empty( $response['width'] ) ? (int) $response['width'] : 320;
		$height = ! empty( $response['height'] ) ? (int) $response['height'] : 80;

		$response['sizes'] = array(
			'full' => array(
				'url'         => $url,
				'width'       => $width,
				'height'      => $height,
				'orientation' => $width >= $height ? 'landscape' : 'portrait',
			),
		);
	}
	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'bs_svg_prepare_for_js', 10, 3 );

/**
 * Admin styles so SVG images preview nicely in media modals and grid views.
 */
function bs_svg_admin_preview_styles() {
	echo '<style>
		.media-icon img[src$=".svg"],
		.attachment-preview img[src$=".svg"],
		.thumbnail img[src$=".svg"],
		.media-modal img[src$=".svg"] {
			width: 100% !important;
			height: auto !important;
			max-height: 100%;
			object-fit: contain;
		}
	</style>';
}
add_action( 'admin_head', 'bs_svg_admin_preview_styles' );

/**
 * Intercept WordPress AJAX crop requests for SVGs.
 *
 * Vector graphics (SVG) do not need or support raster bitmap cropping via GD/Imagick.
 * When a user selects an SVG logo in the Customizer and clicks "Crop image",
 * this hook returns the SVG attachment directly as the cropped result,
 * preventing the "There has been an error cropping your image" error.
 */
function bs_svg_intercept_crop_image() {
	if ( ! isset( $_POST['id'] ) ) {
		return;
	}
	$attachment_id = absint( $_POST['id'] );
	$mime_type     = get_post_mime_type( $attachment_id );

	if ( 'image/svg+xml' === $mime_type ) {
		check_ajax_referer( 'image_editor-' . $attachment_id, 'nonce' );

		if ( empty( $attachment_id ) || ! current_user_can( 'edit_post', $attachment_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'bshealthcare' ) ) );
		}

		// Return the prepared attachment object directly to the Customizer JS.
		wp_send_json_success( wp_prepare_attachment_for_js( $attachment_id ) );
	}
}
add_action( 'wp_ajax_crop_image', 'bs_svg_intercept_crop_image', 1 );

