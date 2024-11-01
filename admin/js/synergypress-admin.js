(function ($, root, undefined) {
	'use strict';

	jQuery(document).ready(function ($) {

        /**
         * Toggler & Toggles
         */
		let fsToggle = document.querySelectorAll('button.fs-toggler');
		if (fsToggle) {
			var fsToggle_length = fsToggle.length;
			for (let i = 0; i < fsToggle_length; ++i) {
				fsToggle[i].addEventListener('click', () => {
					let aria = fsToggle[i].getAttribute('aria-expanded'),
						card = fsToggle[i].parentNode.parentNode;
					if ('false' == aria) {
						fsToggle[i].setAttribute('aria-expanded', 'true');
						let box = card.querySelector('.fs-toggle');
						if (box) box.classList.remove('close');
					} else {
						fsToggle[i].setAttribute('aria-expanded', 'false');
						let box = card.querySelector('.fs-toggle');
						if (box) box.classList.add('close');
					}
				});
			}
		}

        /**
         * Tab groups
         */
		let disableTabs = (fsTabGroup) => {
			for (let [k, v] of Object.entries(fsTabGroup.getElementsByClassName('fs-tab-active'))) {
				v.classList.remove('fs-tab-active');
			}
			for (let [k, v] of Object.entries(fsTabGroup.getElementsByClassName('nav-tab-active'))) {
				v.classList.remove('nav-tab-active');
			}
		}
		let fsTabGroup = document.querySelectorAll('[data-tab-group]');
		if (fsTabGroup) {
			var fsTabGroup_length = fsTabGroup.length;
			for (let i = 0; i < fsTabGroup_length; ++i) {
				let navTab = fsTabGroup[i].getElementsByTagName('a');
				if (navTab) {
					var navTab_length = navTab.length;
					for (let b = 0; b < navTab_length; ++b) {
						navTab[b].addEventListener('click', (e) => {
							disableTabs(fsTabGroup[i]);
							e.preventDefault();
							navTab[b].classList.add('nav-tab-active');
							document.querySelector(navTab[b].getAttribute('href')).classList.add('fs-tab-active');
						});
					}
				}
			}
		}

        /**
         * Input toggles
         */
		let fsInputToggles = document.querySelectorAll('[data-toggle-fs]'),
			fsInputToggles_length = fsInputToggles.length;
		for (let i = 0; i < fsInputToggles_length; ++i) {
			fsInputToggles[i].addEventListener('click', (e) => {
				let el = 'INPUT' == e.target.nodeName ? e.target.parentNode : e.target,
					div = document.getElementById(el.dataset.toggleFs),
					checked = 'INPUT' == e.target.nodeName ? e.target.checked : e.target.firstChild.checked;
				checked
					? div.classList.add('fs-tab-active')
					: div.classList.remove('fs-tab-active');
				if (el.dataset.urlFs) {
					div.classList.add('fs-loading');
					setTimeout(() => {
						window.location.href = el.dataset.urlFs;
					}, 3500);
				}
			});
		}

        /**
         * Input collapse
         */
		let fsInputCollapse = document.querySelectorAll('[data-collapse-fs]'),
			fsInputCollapse_length = fsInputCollapse.length;
		for (let i = 0; i < fsInputCollapse_length; ++i) {
			fsInputCollapse[i].addEventListener('click', (e) => {
				let el = 'INPUT' == e.target.nodeName ? e.target.parentNode : e.target,
					div = document.getElementById(el.dataset.collapseFs),
					checked = 'INPUT' == e.target.nodeName ? e.target.checked : e.target.firstChild.checked;
				if (checked) {
					div.classList.remove('fs-tab-active');
				}
			});
		}

        /**
         * Import Synergy Press settings.
         */
		let synergyPressSettings = document.getElementById('upload-synergy-press-settings');
		if (synergyPressSettings) {
			synergyPressSettings.addEventListener('change', (e) => {
				if (e.target.files) {
					const reader = new FileReader();
					reader.onload = function (file) {
						var fileData = file.target.result;
						try {
							fileData = JSON.parse(fileData);
							document.getElementById('upload-synergy-press-domain').value = fileData.domain;
							document.getElementById('upload-synergy-press-name').value = fileData.name;
							document.getElementById('upload-synergy-press-verified').value = fileData.verified;
							document.getElementById('upload-synergy-press-indexpage').value = fileData.indexpage;
							document.getElementById('upload-synergy-press-proto').value = fileData.proto;
							document.getElementById('upload-synergy-press-profileid').value = fileData.profileid;
							document.getElementById('upload-synergy-press-siteid').value = fileData.siteid;
							document.getElementById('upload-synergy-press-apikey').value = fileData.apikey;
							document.getElementById('upload-synergy-press-secretkey').value = fileData.secretkey;
							document.getElementById('submit-synergy-press-settings').disabled = false;
						}
						catch (exception) {
							alert('Error: This file does not contain valid JSON data!');
							document.getElementById('submit-synergy-press-settings').disabled = true;
							e.preventDefault();
							return false;
						}
					};
					reader.readAsText(e.target.files[0], "UTF-8");
				}
			});
		}

		let fsAutoSubmit = document.querySelectorAll('[data-submit-fs]'),
			fsAutoSubmit_length = fsAutoSubmit.length;
		for (let i = 0; i < fsAutoSubmit_length; ++i) {
			fsAutoSubmit[i].addEventListener('change', (e) => {
				e.target.form.submit();
			});
		}

		let triggerActions = document.querySelectorAll('[data-trigger-action]'),
			triggerActions_length = triggerActions.length;
		for (let i = 0; i < triggerActions_length; ++i) {
			var eType = triggerActions[i].dataset.triggerAction;
			triggerActions[i].addEventListener(eType, () => {
				var el = document.querySelector(triggerActions[i].getAttribute('href'));
				el[eType]();
			});
		}

		let fsClickToggles = document.querySelectorAll('[data-trigger-click-fs]'),
			fsClickToggles_length = fsClickToggles.length;
		for (let i = 0; i < fsClickToggles_length; ++i) {
			fsClickToggles[i].addEventListener('change', (e) => {
				document.querySelector('a[href="#' + e.target.dataset.triggerClickFs + '"]').click();
			});
		}

		function helpSection(action = 'none') {
			let tipBlocks = document.querySelectorAll('.tips-block:not([close])'),
				tipBlocks_length = tipBlocks.length;
			for (let i = 0; i < tipBlocks_length; ++i) {
				tipBlocks[i].classList.add('close');
			}
			if ('none' != action) {
				var tip = document.querySelector('#tips-' + action);
				if (tip) tip.classList.remove('close');
			}
		}

		$(document).on('click', '#synchronize-modules', function (e) {
			$('#btn-update-strategy').trigger('click');
			e.preventDefault();
		});

		$(document).on('change', '[data-synergypress-fs="auto-update"]', function (e) {
			$(this).closest('form').trigger('submit');
		});

		$(document).on('click', '.refresh-tips-helper', function () {
			helpSection('use-class');
		});

		$(document).on('click', '.show-how-tips', function () {
			helpSection('howto');
		});

		$(document).on('click', '[data-package-action="modules"]', function () {
			helpSection('use-class');
		});

		var synergypress_code_editor = $('.custom-codearea');
		if (synergypress_code_editor.length > 0) {
			wp.codeEditor.initialize(synergypress_code_editor, cm_settings);
		}

		$(document).on('click', '.refresh-codemirror', function () {
			var editor = $($(this).attr('href') + ' .codearea');
			if (!$($(editor).next()).hasClass('CodeMirror')) {
				var editor_ = wp.codeEditor.initialize(editor, cm_settings);
				editor_.codemirror.on('cursorActivity', function (e) {
					var item = e.display.lineDiv.querySelector('.CodeMirror-activeline pre.CodeMirror-line .cm-property');
					if (item && item.innerText) {
						helpSection(JSON.parse(item.innerText));

					}
				});
			}
		});
	});
})(this);