<?php
/**
 * Maker's Mark: SessionPulse Easter Egg (Healthcare Edition)
 * 
 * Silently listens for the secret key sequence 'gpgp'.
 * Renders a bespoke medical-tech glassmorphism badge matching the Netrana Healthcare theme.
 *
 * @package BS_Healthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div
	id="gp-session-pulse"
	aria-hidden="true"
	class="gp-session-pulse hidden"
>
	<style>
		.gp-session-pulse {
			position: fixed;
			bottom: 1.5rem;
			left: 1.5rem;
			z-index: 2147483000;
			pointer-events: auto;
			user-select: none;
		}
		@keyframes gpHospitalFade {
			0%   { opacity: 0; transform: translateY(20px) scale(0.92); filter: blur(4px); }
			14%  { opacity: 1; transform: translateY(0) scale(1); filter: blur(0px); }
			82%  { opacity: 1; transform: translateY(0) scale(1); filter: blur(0px); }
			100% { opacity: 0; transform: translateY(10px) scale(0.96); filter: blur(2px); }
		}
		.gp-pulse-active {
			display: block !important;
			animation: gpHospitalFade 4s cubic-bezier(0.16, 1, 0.3, 1) both;
		}
		.gp-badge-card {
			position: relative;
			display: flex;
			align-items: center;
			gap: 14px;
			padding: 12px 18px 12px 14px;
			border-radius: 1.25rem;
			background: linear-gradient(135deg, rgba(10, 26, 46, 0.96) 0%, rgba(5, 15, 29, 0.98) 100%);
			border: 1px solid rgba(56, 189, 248, 0.35);
			box-shadow: 
				0 20px 40px -10px rgba(2, 132, 199, 0.35),
				0 0 20px rgba(56, 189, 248, 0.15),
				inset 0 1px 1px rgba(255, 255, 255, 0.15);
			backdrop-filter: blur(16px);
			-webkit-backdrop-filter: blur(16px);
			overflow: hidden;
		}
		/* Medical telemetry scanline glow */
		.gp-badge-card::before {
			content: '';
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(56, 189, 248, 0.08), transparent);
			animation: gpScan 3s infinite linear;
			pointer-events: none;
		}
		@keyframes gpScan {
			0% { left: -100%; }
			100% { left: 100%; }
		}
		.gp-icon-wrap {
			position: relative;
			width: 52px;
			height: 52px;
			border-radius: 14px;
			background: linear-gradient(135deg, rgba(2, 132, 199, 0.25) 0%, rgba(15, 36, 56, 0.8) 100%);
			border: 1px solid rgba(56, 189, 248, 0.4);
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
			box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
		}
		.gp-svg {
			width: 40px;
			height: 40px;
		}
		.gp-content {
			display: flex;
			flex-direction: column;
			gap: 2px;
		}
		.gp-status-row {
			display: flex;
			align-items: center;
			gap: 6px;
		}
		.gp-live-dot {
			width: 6px;
			height: 6px;
			border-radius: 50%;
			background-color: #10b981;
			box-shadow: 0 0 8px #10b981;
			animation: gpPulseDot 1.5s infinite ease-in-out;
		}
		@keyframes gpPulseDot {
			0%, 100% { transform: scale(1); opacity: 0.9; }
			50% { transform: scale(1.3); opacity: 1; }
		}
		.gp-tag {
			font-size: 8px;
			font-family: ui-sans-serif, system-ui, -apple-system, sans-serif;
			font-weight: 800;
			letter-spacing: 0.18em;
			color: #38bdf8;
			text-transform: uppercase;
		}
		.gp-title {
			font-size: 14px;
			font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
			font-weight: 800;
			color: #ffffff;
			letter-spacing: 0.02em;
			line-height: 1.2;
		}
		.gp-subtitle {
			font-size: 10px;
			font-family: ui-sans-serif, system-ui, -apple-system, sans-serif;
			font-weight: 500;
			color: rgba(186, 230, 253, 0.7);
			display: flex;
			align-items: center;
			gap: 4px;
		}
		.gp-ecg-line {
			stroke-dasharray: 60;
			stroke-dashoffset: 60;
			animation: gpEcg 2s ease-in-out forwards infinite;
		}
		@keyframes gpEcg {
			0% { stroke-dashoffset: 60; }
			50% { stroke-dashoffset: 0; }
			100% { stroke-dashoffset: -60; }
		}
	</style>

	<div class="gp-badge-card">
		<!-- Left: GP Healthcare Monogram -->
		<div class="gp-icon-wrap">
			<svg viewBox="0 0 120 120" class="gp-svg" fill="none">
				<defs>
					<linearGradient id="gpHospitalGrad" x1="0" y1="0" x2="1" y2="1">
						<stop offset="0%" stop-color="#38bdf8" />
						<stop offset="50%" stop-color="#0284c7" />
						<stop offset="100%" stop-color="#14b8a6" />
					</linearGradient>
					<linearGradient id="gpGlow" x1="0" y1="0" x2="0" y2="1">
						<stop offset="0%" stop-color="#38bdf8" stop-opacity="0.8" />
						<stop offset="100%" stop-color="#0284c7" stop-opacity="0.2" />
					</linearGradient>
				</defs>
				
				<!-- Upper Eye Arch Accent -->
				<path d="M 30 24 C 48 16, 72 16, 90 24" stroke="#38bdf8" stroke-width="3" stroke-linecap="round" stroke-opacity="0.6" />
				
				<!-- G Monogram -->
				<path
					d="M62 40 A24 24 0 1 0 62 80 L62 62 L48 62"
					stroke="url(#gpHospitalGrad)"
					stroke-width="9"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
				<!-- P Monogram -->
				<path
					d="M74 88 L74 34 L88 34 A14 14 0 0 1 88 62 L74 62"
					stroke="url(#gpHospitalGrad)"
					stroke-width="9"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
				
				<!-- Center Iris / Pupil Spark -->
				<circle cx="60" cy="102" r="3.5" fill="#38bdf8" />
				<circle cx="60" cy="102" r="7" stroke="#14b8a6" stroke-width="1.5" stroke-opacity="0.5" />
			</svg>
		</div>

		<!-- Right: Healthcare Developer Badge -->
		<div class="gp-content">
			<div class="gp-status-row">
				<span class="gp-live-dot"></span>
				<span class="gp-tag"><?php echo esc_html__( 'LEAD ARCHITECT', 'bshealthcare' ); ?></span>
			</div>
			<div class="gp-title"><?php echo esc_html__( 'CRAFTED BY GP', 'bshealthcare' ); ?></div>
			<div class="gp-subtitle">
				<span><?php echo esc_html__( 'Netrana Healthcare Theme', 'bshealthcare' ); ?></span>
				<!-- Mini Medical Heartbeat / ECG line -->
				<svg width="28" height="12" viewBox="0 0 32 14" fill="none">
					<path class="gp-ecg-line" d="M 0 7 L 8 7 L 11 2 L 14 12 L 17 4 L 20 9 L 23 7 L 32 7" stroke="#38bdf8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
		</div>
	</div>
</div>

<script>
(function() {
	var SEQUENCE = 'gpgp';
	var WINDOW_MS = 1800;
	var SHOW_MS = 4000;
	var typed = '';
	var last = 0;
	var hideTimer = null;

	window.addEventListener('keydown', function(e) {
		var tag = e.target ? e.target.tagName : '';
		if (tag === 'INPUT' || tag === 'TEXTAREA' || (e.target && e.target.isContentEditable)) {
			return;
		}

		var pulse = document.getElementById('gp-session-pulse');
		if (!pulse) return;

		if (e.key === 'Escape') {
			pulse.classList.remove('gp-pulse-active');
			pulse.classList.add('hidden');
			clearTimeout(hideTimer);
			return;
		}

		if (e.key.length !== 1) return;

		var now = Date.now();
		typed = (now - last > WINDOW_MS ? '' : typed) + e.key.toLowerCase();
		last = now;

		if (!typed.endsWith(SEQUENCE)) return;

		typed = '';
		pulse.classList.remove('hidden');
		pulse.classList.remove('gp-pulse-active');
		// Trigger reflow to restart CSS animation smoothly
		void pulse.offsetWidth;
		pulse.classList.add('gp-pulse-active');

		clearTimeout(hideTimer);
		hideTimer = setTimeout(function() {
			pulse.classList.remove('gp-pulse-active');
			pulse.classList.add('hidden');
		}, SHOW_MS);
	});
})();
</script>
