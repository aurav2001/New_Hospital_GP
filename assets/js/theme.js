/* global BSHC */
(function () {
	'use strict';

	var $ = function (s, c) { return (c || document).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

	function post(action, data) {
		var body = new FormData();
		body.append('action', action);
		body.append('nonce', BSHC.nonce);
		Object.keys(data || {}).forEach(function (k) {
			var v = data[k];
			if (Array.isArray(v)) {
				v.forEach(function (item, i) {
					if (item && typeof item === 'object') {
						Object.keys(item).forEach(function (ik) { body.append(k + '[' + i + '][' + ik + ']', item[ik]); });
					} else {
						body.append(k + '[]', item);
					}
				});
			} else {
				body.append(k, v == null ? '' : v);
			}
		});
		return fetch(BSHC.ajax, { method: 'POST', body: body, credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.catch(function () { return { success: false, data: { message: BSHC.i18n.error } }; });
	}

	/* ---------------- Header: sticky shadow, drawer, mega menu ---------------- */
	var nav = $('#bs-nav');
	if (nav) {
		var onScroll = function () {
			nav.classList.toggle('shadow-card', window.scrollY > 8);
			nav.classList.toggle('border-b', window.scrollY <= 8);
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	var drawer = $('#bs-drawer');
	var overlay = $('#bs-drawer-overlay');
	function toggleDrawer(open) {
		if (!drawer) return;
		drawer.classList.toggle('translate-x-full', !open);
		overlay.classList.toggle('hidden', !open);
		document.body.style.overflow = open ? 'hidden' : '';
	}
	var menuToggle = $('#bs-menu-toggle');
	if (menuToggle) menuToggle.addEventListener('click', function () { toggleDrawer(drawer.classList.contains('translate-x-full')); });
	if ($('#bs-drawer-close')) $('#bs-drawer-close').addEventListener('click', function () { toggleDrawer(false); });
	if (overlay) overlay.addEventListener('click', function () { toggleDrawer(false); });

	// Mega menu: move it inside the "Specialities" menu item and show on hover.
	var mega = $('#bs-mega');
	var megaItem = $('.bs-nav-links .has-mega');
	if (mega && megaItem) {
		megaItem.classList.add('relative');
		megaItem.appendChild(mega);
		var t;
		megaItem.addEventListener('mouseenter', function () { clearTimeout(t); mega.classList.remove('hidden'); });
		megaItem.addEventListener('mouseleave', function () { t = setTimeout(function () { mega.classList.add('hidden'); }, 120); });
		megaItem.addEventListener('click', function () { mega.classList.add('hidden'); });
	}

	/* ---------------- Back to top ---------------- */
	var topBtn = $('#bs-top');
	if (topBtn) {
		window.addEventListener('scroll', function () {
			var show = window.scrollY > 500;
			topBtn.classList.toggle('opacity-0', !show);
			topBtn.classList.toggle('pointer-events-none', !show);
		}, { passive: true });
		topBtn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
	}

	/* ---------------- Counters ---------------- */
	if ('IntersectionObserver' in window) {
		var obs = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (!e.isIntersecting) return;
				obs.unobserve(e.target);
				var raw = e.target.getAttribute('data-count') || '';
				var m = raw.match(/[\d,.]+/);
				if (!m) return;
				var target = parseFloat(m[0].replace(/,/g, ''));
				var prefix = raw.slice(0, m.index);
				var suffix = raw.slice(m.index + m[0].length);
				// Keep the same number of decimals as the source, so "4.9/5" does not become "5/5".
				var dot = m[0].indexOf('.');
				var decimals = dot === -1 ? 0 : m[0].length - dot - 1;
				var start = performance.now();
				var tick = function (now) {
					var p = Math.min(1, (now - start) / 1400);
					var val = target * (1 - Math.pow(1 - p, 3));
					e.target.textContent = prefix + val.toLocaleString('en-IN', {
						minimumFractionDigits: decimals,
						maximumFractionDigits: decimals
					}) + suffix;
					if (p < 1) requestAnimationFrame(tick);
				};
				requestAnimationFrame(tick);
			});
		}, { threshold: 0.4 });
		$$('[data-count]').forEach(function (el) { obs.observe(el); });
	}

	/* ---------------- FAQ ---------------- */
	$$('#bs-faq .bs-faq-item button').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var item = btn.closest('.bs-faq-item');
			var open = item.classList.contains('is-open');
			$$('#bs-faq .bs-faq-item').forEach(function (i) {
				var isSelf = i === item;
				var willOpen = isSelf && !open;
				i.classList.toggle('is-open', willOpen);
				i.classList.toggle('border-primary-200', willOpen);
				var body = $('.bs-faq-body', i);
				body.classList.toggle('grid-rows-[1fr]', willOpen);
				body.classList.toggle('opacity-100', willOpen);
				body.classList.toggle('grid-rows-[0fr]', !willOpen);
				body.classList.toggle('opacity-0', !willOpen);
				var icon = $('.bs-faq-icon', i);
				icon.classList.toggle('bg-primary-600', willOpen);
				icon.classList.toggle('text-white', willOpen);
				icon.classList.toggle('bg-navy-50', !willOpen);
				icon.classList.toggle('text-navy-500', !willOpen);
				$('.bs-faq-plus', i).classList.toggle('hidden', willOpen);
				$('.bs-faq-minus', i).classList.toggle('hidden', !willOpen);
				$('button', i).setAttribute('aria-expanded', willOpen ? 'true' : 'false');
			});
		});
	});

	/* ---------------- Testimonials ---------------- */
	var tWrap = $('#bs-testimonials');
	if (tWrap) {
		var texts = $$('.bs-t-text', tWrap);
		var medias = $$('.bs-t-media', tWrap);
		var dots = $$('.bs-t-dot', tWrap);
		var idx = 0;
		var show = function (i) {
			idx = (i + texts.length) % texts.length;
			texts.forEach(function (el, n) { el.classList.toggle('hidden', n !== idx); });
			medias.forEach(function (el, n) {
				el.classList.toggle('opacity-0', n !== idx);
				el.classList.toggle('pointer-events-none', n !== idx);
			});
			dots.forEach(function (d, n) {
				d.classList.toggle('w-6', n === idx);
				d.classList.toggle('bg-primary-600', n === idx);
				d.classList.toggle('w-1.5', n !== idx);
				d.classList.toggle('bg-navy-200', n !== idx);
			});
		};
		$$('[data-t-next]', tWrap).forEach(function (b) { b.addEventListener('click', function () { show(idx + 1); }); });
		$$('[data-t-prev]', tWrap).forEach(function (b) { b.addEventListener('click', function () { show(idx - 1); }); });
		dots.forEach(function (d) { d.addEventListener('click', function () { show(parseInt(d.getAttribute('data-index'), 10)); }); });
		var auto = setInterval(function () { show(idx + 1); }, 7000);
		tWrap.addEventListener('mouseenter', function () { clearInterval(auto); });
	}

	/* ---------------- Tabs (dashboards) ---------------- */
	$$('[data-tabs]').forEach(function (wrap) {
		$$('.bs-tab', wrap).forEach(function (tab) {
			tab.addEventListener('click', function () {
				var name = tab.getAttribute('data-tab');
				$$('.bs-tab', wrap).forEach(function (t) { t.classList.toggle('is-active', t === tab); });
				$$('.bs-tab-panel', wrap).forEach(function (p) { p.classList.toggle('hidden', p.getAttribute('data-panel') !== name); });
			});
		});
	});

	/* ---------------- Doctors archive search ---------------- */
	var docSearch = $('#bs-doctor-search');
	if (docSearch) {
		docSearch.addEventListener('input', function () {
			var q = docSearch.value.toLowerCase().trim();
			var visible = 0;
			$$('.bs-doctor-item').forEach(function (item) {
				var match = !q || (item.getAttribute('data-search') || '').indexOf(q) !== -1;
				item.classList.toggle('hidden', !match);
				if (match) visible++;
			});
			$('#bs-doctor-empty').classList.toggle('hidden', visible > 0);
		});
	}

	/* ---------------- Advertisement popup ---------------- */
	var ad = $('#bs-ad');
	if (ad) {
		try {
			if (!sessionStorage.getItem('recAdClosed')) {
				ad.classList.remove('hidden');
				ad.classList.add('flex');
			}
		} catch (e) { /* private mode */ }
		var closeAd = function () {
			ad.classList.add('hidden');
			ad.classList.remove('flex');
			try { sessionStorage.setItem('recAdClosed', '1'); } catch (e) { /* ignore */ }
		};
		$('#bs-ad-close').addEventListener('click', closeAd);
		ad.addEventListener('click', function (e) { if (e.target === ad) closeAd(); });
	}

	/* ---------------- Share ---------------- */
	$$('[data-share]').forEach(function (b) {
		b.addEventListener('click', function () {
			var url = window.location.href;
			if (navigator.share) {
				navigator.share({ title: document.title, url: url });
			} else if (navigator.clipboard) {
				navigator.clipboard.writeText(url);
				b.textContent = 'Link copied';
			}
		});
	});

	/* ---------------- Contact form ---------------- */
	var contactForm = $('#bs-contact-form');
	if (contactForm) {
		contactForm.addEventListener('submit', function (e) {
			e.preventDefault();
			var alertBox = $('#bs-contact-alert');
			var btn = $('button[type="submit"]', contactForm);
			var label = $('span', btn);
			alertBox.classList.add('hidden');
			btn.disabled = true;
			if (label) label.textContent = BSHC.i18n.sending;

			var fd = new FormData(contactForm);
			var data = {};
			fd.forEach(function (v, k) { data[k] = v; });

			post('bs_contact', data).then(function (res) {
				btn.disabled = false;
				if (label) label.textContent = 'Send message';
				if (res.success) {
					contactForm.classList.add('hidden');
					$('#bs-contact-success-text').textContent = res.data.message;
					$('#bs-contact-success').classList.remove('hidden');
				} else {
					alertBox.textContent = res.data.message;
					alertBox.classList.remove('hidden');
					if (res.data.field) {
						var f = contactForm.querySelector('[name="' + res.data.field + '"]');
						if (f) { f.classList.add('border-red-400'); f.focus(); }
					}
				}
			});
		});
		$$('input, textarea, select', contactForm).forEach(function (f) {
			f.addEventListener('input', function () { f.classList.remove('border-red-400'); });
		});
	}

	/* ---------------- Hero inline booking form ---------------- */
	var heroForm = $('#bs-hero-form');
	if (heroForm) {
		var hfAlert = $('#bs-hf-alert');
		var hfDoctor = $('#bs-hf-doctor');
		var hfDate = $('#bs-hf-date');
		var hfTime = $('#bs-hf-time');

		var hfError = function (msg, field) {
			hfAlert.textContent = msg;
			hfAlert.classList.remove('hidden');
			if (field) {
				var f = $('#bs-hf-' + field);
				if (f) { f.classList.add('border-red-400'); f.focus(); }
			}
		};
		var hfClear = function () {
			hfAlert.classList.add('hidden');
			$$('input, select', heroForm).forEach(function (f) { f.classList.remove('border-red-400'); });
		};

		var hfLoadSlots = function () {
			hfTime.innerHTML = '<option value="">Loading…</option>';
			hfTime.disabled = true;
			if (!hfDoctor.value || !hfDate.value) {
				hfTime.innerHTML = '<option value="">' + (hfDoctor.value ? 'Pick a date first' : 'Pick a doctor first') + '</option>';
				return;
			}
			post('bs_slots', { doctor: hfDoctor.value, date: hfDate.value }).then(function (res) {
				if (!res.success) { hfTime.innerHTML = '<option value="">Unavailable</option>'; return; }
				if (res.data.reason) {
					hfTime.innerHTML = '<option value="">Not available that day</option>';
					hfError(res.data.reason);
					return;
				}
				var free = res.data.slots.filter(function (s) { return s.available; });
				if (!free.length) {
					hfTime.innerHTML = '<option value="">No slots left</option>';
					return;
				}
				hfTime.innerHTML = '<option value="">Select a time</option>' + free.map(function (s) {
					return '<option value="' + s.time + '">' + s.time + '</option>';
				}).join('');
				hfTime.disabled = false;
			});
		};

		hfDoctor.addEventListener('change', function () { hfClear(); hfLoadSlots(); });
		hfDate.addEventListener('change', function () { hfClear(); hfLoadSlots(); });
		$$('input, select', heroForm).forEach(function (f) {
			f.addEventListener('input', function () { f.classList.remove('border-red-400'); });
		});

		heroForm.addEventListener('submit', function (e) {
			e.preventDefault();
			hfClear();

			if (BSHC.requireLogin && !BSHC.loggedIn) {
				window.location.href = BSHC.loginUrl + (BSHC.loginUrl.indexOf('?') === -1 ? '?' : '&') + 'redirect_to=' + encodeURIComponent(window.location.href);
				return;
			}

			var name = $('#bs-hf-name').value.trim();
			var phone = $('#bs-hf-phone').value.trim();
			var email = $('#bs-hf-email').value.trim();
			if (name.length < 2) return hfError('Please enter your full name.', 'name');
			if (!/^[+\d][\d\s-]{7,15}$/.test(phone)) return hfError('Please enter a valid phone number.', 'phone');
			if (!/^\S+@\S+\.\S+$/.test(email)) return hfError('Please enter a valid email address.', 'email');
			if (!hfDoctor.value) return hfError('Please choose a doctor.', 'doctor');
			if (!hfDate.value) return hfError('Please choose a date.', 'date');
			if (!hfTime.value) return hfError('Please choose a time slot.', 'time');

			var btn = $('button[type="submit"]', heroForm);
			var label = $('span', btn);
			btn.disabled = true;
			if (label) label.textContent = BSHC.i18n.sending;

			post('bs_book', {
				name: name, phone: phone, email: email,
				doctor: hfDoctor.value, date: hfDate.value, time: hfTime.value
			}).then(function (res) {
				btn.disabled = false;
				if (label) label.textContent = 'Book Appointment';
				if (!res.success) {
					if (res.data.login) { window.location.href = BSHC.loginUrl; return; }
					hfLoadSlots();
					return hfError(res.data.message, res.data.field);
				}
				$('#bs-hf-success-title').textContent = res.data.message;
				$('#bs-hf-success-rows').innerHTML =
					'<div class="flex justify-between"><span class="text-navy-500">Reference</span><strong>' + res.data.reference + '</strong></div>' +
					'<div class="flex justify-between"><span class="text-navy-500">Doctor</span><strong>' + res.data.doctor + '</strong></div>' +
					'<div class="flex justify-between"><span class="text-navy-500">Date</span><strong>' + res.data.date + '</strong></div>' +
					'<div class="flex justify-between"><span class="text-navy-500">Time</span><strong>' + res.data.time + '</strong></div>';
				heroForm.classList.add('hidden');
				$('#bs-hf-success').classList.remove('hidden');
			});
		});
	}

	/* ---------------- Booking modal ---------------- */
	var modal = $('#bs-modal');
	if (modal) {
		var step = 1;
		var doctors = [];
		var loaded = false;

		var openModal = function (doctorId) {
			if (BSHC.requireLogin && !BSHC.loggedIn) {
				window.location.href = BSHC.loginUrl + (BSHC.loginUrl.indexOf('?') === -1 ? '?' : '&') + 'redirect_to=' + encodeURIComponent(window.location.href);
				return;
			}
			modal.classList.remove('hidden');
			modal.classList.add('flex');
			document.body.style.overflow = 'hidden';
			toggleDrawer(false);
			loadDoctors(doctorId);
		};
		var closeModal = function () {
			modal.classList.add('hidden');
			modal.classList.remove('flex');
			document.body.style.overflow = '';
		};

		document.addEventListener('click', function (e) {
			var trigger = e.target.closest('[data-bs-book]');
			if (trigger) {
				e.preventDefault();
				openModal(trigger.getAttribute('data-doctor'));
			}
			if (e.target.closest('[data-bs-close]')) closeModal();
		});
		document.addEventListener('keydown', function (e) { if ('Escape' === e.key) closeModal(); });

		var setStep = function (n) {
			step = n;
			$$('.bs-step', modal).forEach(function (s) { s.classList.toggle('hidden', parseInt(s.getAttribute('data-step'), 10) !== n); });
			$('#bs-progress').style.width = (n * 33.34) + '%';
			$('#bs-back').classList.toggle('invisible', n === 1);
			$('#bs-next').textContent = n === 3 ? 'Confirm booking' : 'Continue';
			var labels = ['Step 1 of 3 · Your details', 'Step 2 of 3 · Choose a doctor', 'Step 3 of 3 · Date & time'];
			$('#bs-step-label').textContent = labels[n - 1];
		};

		var showAlert = function (msg) {
			var a = $('#bs-alert');
			a.className = 'rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700';
			a.textContent = msg;
		};
		var clearAlert = function () { $('#bs-alert').className = 'hidden'; };

		function loadDoctors(preselect) {
			if (loaded) { if (preselect) selectDoctor(preselect); return; }
			post('bs_doctors', {}).then(function (res) {
				loaded = true;
				var box = $('#bs-doctors');
				if (!res.success || !res.data.length) {
					box.innerHTML = '<p class="text-sm text-navy-400">No doctors available right now. Please call us.</p>';
					return;
				}
				doctors = res.data;
				box.innerHTML = doctors.map(function (d) {
					return '<button type="button" class="bs-doc w-full text-left rounded-xl border border-navy-200 p-3 flex items-center gap-3 hover:border-primary-400 hover:bg-primary-50/50 transition-colors" data-id="' + d.id + '">' +
						(d.image ? '<img src="' + d.image + '" alt="" class="w-11 h-11 rounded-lg object-cover shrink-0">' : '<span class="w-11 h-11 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 font-bold">' + d.name.charAt(0) + '</span>') +
						'<span class="min-w-0 flex-1"><span class="block font-semibold text-navy-800 text-sm truncate">' + d.name + '</span><span class="block text-xs text-navy-400 truncate">' + (d.role || '') + '</span></span>' +
						'<span class="text-xs font-bold text-primary-700 shrink-0">₹' + d.fee + '</span></button>';
				}).join('');
				$$('.bs-doc', box).forEach(function (b) {
					b.addEventListener('click', function () { selectDoctor(b.getAttribute('data-id')); });
				});
				if (preselect) selectDoctor(preselect);
			});
		}

		function selectDoctor(id) {
			$('#bs-doctor').value = id;
			$$('.bs-doc', modal).forEach(function (b) {
				var on = b.getAttribute('data-id') === String(id);
				b.classList.toggle('border-primary-500', on);
				b.classList.toggle('bg-primary-50', on);
			});
			var date = $('#bs-date').value;
			if (date) loadSlots();
		}

		function loadSlots() {
			var doctor = $('#bs-doctor').value;
			var date = $('#bs-date').value;
			var box = $('#bs-slots');
			$('#bs-time').value = '';
			if (!doctor || !date) { box.textContent = 'Pick a date to see slots.'; return; }
			box.innerHTML = '<span class="col-span-3 text-navy-400">Loading slots…</span>';
			post('bs_slots', { doctor: doctor, date: date }).then(function (res) {
				if (!res.success) { box.innerHTML = '<span class="col-span-3 text-red-600">' + res.data.message + '</span>'; return; }
				if (res.data.reason) { box.innerHTML = '<span class="col-span-3 text-amber-600">' + res.data.reason + '</span>'; return; }
				box.innerHTML = res.data.slots.map(function (s) {
					return '<button type="button" class="bs-slot rounded-lg border px-2 py-2.5 text-sm font-semibold transition-colors ' +
						(s.available ? 'border-navy-200 text-navy-700 hover:border-primary-500 hover:bg-primary-50' : 'border-navy-100 text-navy-300 line-through cursor-not-allowed') +
						'" data-time="' + s.time + '"' + (s.available ? '' : ' disabled') + '>' + s.time + '</button>';
				}).join('');
				$$('.bs-slot', box).forEach(function (b) {
					b.addEventListener('click', function () {
						$('#bs-time').value = b.getAttribute('data-time');
						$$('.bs-slot', box).forEach(function (x) {
							var on = x === b;
							x.classList.toggle('bg-primary-600', on);
							x.classList.toggle('text-white', on);
							x.classList.toggle('border-primary-600', on);
						});
						updateSummary();
					});
				});
			});
		}

		function updateSummary() {
			var d = doctors.filter(function (x) { return String(x.id) === $('#bs-doctor').value; })[0];
			var date = $('#bs-date').value;
			var time = $('#bs-time').value;
			if (!d || !date || !time) { $('#bs-summary').innerHTML = ''; return; }
			$('#bs-summary').innerHTML = '<strong class="block text-navy-800 mb-1">Booking summary</strong>' +
				'<span class="block text-navy-600">' + d.name + ' · ' + date + ' at ' + time + '</span>' +
				'<span class="block text-navy-600">Consultation fee: ₹' + d.fee + '</span>';
		}

		$('#bs-date').addEventListener('change', loadSlots);

		$('#bs-back').addEventListener('click', function () { clearAlert(); setStep(Math.max(1, step - 1)); });

		$('#bs-next').addEventListener('click', function () {
			clearAlert();
			if (step === 1) {
				var name = $('#bs-name').value.trim();
				var phone = $('#bs-phone').value.trim();
				var email = $('#bs-email').value.trim();
				if (name.length < 2) return showAlert('Please enter your full name.');
				if (!/^[+\d][\d\s-]{7,15}$/.test(phone)) return showAlert('Please enter a valid phone number.');
				if (!/^\S+@\S+\.\S+$/.test(email)) return showAlert('Please enter a valid email address.');
				return setStep(2);
			}
			if (step === 2) {
				if (!$('#bs-doctor').value) return showAlert('Please choose a doctor.');
				return setStep(3);
			}
			if (!$('#bs-date').value) return showAlert('Please choose a date.');
			if (!$('#bs-time').value) return showAlert('Please choose a time slot.');

			var btn = $('#bs-next');
			btn.disabled = true;
			btn.textContent = 'Booking…';
			post('bs_book', {
				name: $('#bs-name').value.trim(),
				phone: $('#bs-phone').value.trim(),
				email: $('#bs-email').value.trim(),
				doctor: $('#bs-doctor').value,
				date: $('#bs-date').value,
				time: $('#bs-time').value
			}).then(function (res) {
				btn.disabled = false;
				btn.textContent = 'Confirm booking';
				if (!res.success) {
					if (res.data.login) { window.location.href = BSHC.loginUrl; return; }
					return showAlert(res.data.message);
				}
				$$('.bs-step', modal).forEach(function (s) { s.classList.add('hidden'); });
				$('#bs-modal-actions').classList.add('hidden');
				$('#bs-progress').style.width = '100%';
				$('#bs-step-label').textContent = 'Done';
				$('#bs-success-title').textContent = res.data.message;
				$('#bs-success-text').textContent = 'A confirmation has been sent to your email.';
				$('#bs-success-rows').innerHTML =
					'<div class="flex justify-between"><span class="text-navy-500">Reference</span><strong>' + res.data.reference + '</strong></div>' +
					'<div class="flex justify-between"><span class="text-navy-500">Doctor</span><strong>' + res.data.doctor + '</strong></div>' +
					'<div class="flex justify-between"><span class="text-navy-500">Date</span><strong>' + res.data.date + '</strong></div>' +
					'<div class="flex justify-between"><span class="text-navy-500">Time</span><strong>' + res.data.time + '</strong></div>';
				$('#bs-success-link').href = res.data.dashboard;
				$('#bs-success').classList.remove('hidden');
			});
		});
	}

	/* ---------------- Appointment status buttons ---------------- */
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('[data-status]');
		if (!btn) return;
		var status = btn.getAttribute('data-status');
		var note = '';
		if (btn.hasAttribute('data-ask')) {
			note = window.prompt(btn.getAttribute('data-ask'), '');
			if (note === null) return;
		} else if (btn.hasAttribute('data-confirm')) {
			if (!window.confirm(btn.getAttribute('data-confirm'))) return;
		}
		btn.disabled = true;
		post('bs_status', { id: btn.getAttribute('data-id'), status: status, note: note }).then(function (res) {
			if (res.success) {
				window.location.reload();
			} else {
				btn.disabled = false;
				window.alert(res.data.message);
			}
		});
	});

	/* ---------------- Doctor: duty toggle ---------------- */
	var duty = $('#bs-duty');
	if (duty) {
		duty.addEventListener('click', function () {
			duty.disabled = true;
			post('bs_toggle_online', {}).then(function (res) {
				duty.disabled = false;
				if (!res.success) return window.alert(res.data.message);
				var on = res.data.online;
				duty.classList.toggle('bg-emerald-600', on);
				duty.classList.toggle('text-white', on);
				duty.classList.toggle('hover:bg-emerald-700', on);
				duty.classList.toggle('btn-outline', !on);
				$('.bs-duty-label', duty).textContent = on ? 'On duty' : 'Off duty';
				$('span', duty).classList.toggle('bg-white', on);
				$('span', duty).classList.toggle('bg-navy-300', !on);
			});
		});
	}

	/* ---------------- Doctor: settings ---------------- */
	var dsForm = $('#bs-doctor-settings');
	if (dsForm) {
		dsForm.addEventListener('submit', function (e) {
			e.preventDefault();
			var alertBox = $('#bs-ds-alert');
			var fd = new FormData(dsForm);
			var data = { days: [] };
			fd.forEach(function (v, k) {
				if (k === 'days[]') data.days.push(v);
				else data[k] = v;
			});
			post('bs_doctor_settings', data).then(function (res) {
				alertBox.className = 'rounded-xl border p-3 text-sm ' + (res.success ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700');
				alertBox.textContent = res.data.message;
			});
		});
	}

	/* ---------------- Doctor: prescription modal ---------------- */
	var rx = $('#bs-rx-modal');
	if (rx) {
		var medRow = function () {
			var row = document.createElement('div');
			row.className = 'grid sm:grid-cols-12 gap-2 bs-med';
			row.innerHTML =
				'<input class="input sm:col-span-4" name="name" placeholder="Medicine">' +
				'<input class="input sm:col-span-3" name="dosage" placeholder="1 drop, 3x/day">' +
				'<input class="input sm:col-span-2" name="duration" placeholder="7 days">' +
				'<input class="input sm:col-span-2" name="instructions" placeholder="After food">' +
				'<button type="button" class="sm:col-span-1 rounded-xl border border-navy-200 text-navy-400 hover:text-red-600 hover:border-red-300" data-med-remove aria-label="Remove">&times;</button>';
			row.querySelector('[data-med-remove]').addEventListener('click', function () { row.remove(); });
			return row;
		};

		document.addEventListener('click', function (e) {
			var trigger = e.target.closest('[data-prescribe]');
			if (trigger) {
				$('#bs-rx-appt').value = trigger.getAttribute('data-prescribe');
				$('#bs-rx-patient').textContent = trigger.getAttribute('data-patient');
				$('#bs-rx-meds').innerHTML = '';
				$('#bs-rx-meds').appendChild(medRow());
				$('#bs-rx-alert').className = 'hidden';
				rx.classList.remove('hidden');
				rx.classList.add('flex');
				document.body.style.overflow = 'hidden';
			}
			if (e.target.closest('[data-rx-close]')) {
				rx.classList.add('hidden');
				rx.classList.remove('flex');
				document.body.style.overflow = '';
			}
		});

		$('#bs-rx-add').addEventListener('click', function () { $('#bs-rx-meds').appendChild(medRow()); });

		$('#bs-rx-save').addEventListener('click', function () {
			var btn = this;
			var alertBox = $('#bs-rx-alert');
			var meds = $$('.bs-med', rx).map(function (row) {
				return {
					name: row.querySelector('[name="name"]').value.trim(),
					dosage: row.querySelector('[name="dosage"]').value.trim(),
					duration: row.querySelector('[name="duration"]').value.trim(),
					instructions: row.querySelector('[name="instructions"]').value.trim()
				};
			}).filter(function (m) { return m.name; });

			btn.disabled = true;
			post('bs_prescribe', {
				appointment: $('#bs-rx-appt').value,
				diagnosis: $('#rx-diagnosis').value.trim(),
				notes: $('#rx-notes').value.trim(),
				medications: meds
			}).then(function (res) {
				btn.disabled = false;
				alertBox.className = 'rounded-xl border p-3 text-sm ' + (res.success ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700');
				alertBox.textContent = res.data.message;
				if (res.success) setTimeout(function () { window.location.reload(); }, 1200);
			});
		});
	}
})();
