'use strict';

(function($){

	class WPBlocksHubSettings {

		constructor() {
	
			this.handleInstallDemoDataButtons();
			this.setupSettingsLayout();
			this.handleFontsPreview();
			this.handleColorsPreview();
			this.handleThemesPreview();
			this.setupCustomEditors();
			this.handleClearErrorLogButton();
			this.handleFlushCacheButton();
			this.handleResetSettingsButton();
			this.handleSaveSettings();
	
		}

		/**
		 * Install demo data
		 */
		handleInstallDemoDataButtons() {

			$('#install_portfolio_posts, #install_testimonial_posts, #install_people_posts, #install_benefits_posts').on( 'click', function( e ) {
				e.preventDefault();

				const $btn = $(this),
				$loader = $btn.next('.wpbh-loader');

				if( $btn.is( '[disabled]') ) {
					return false;
				}

				$btn.attr('disabled', 'disabled');
				$loader.removeClass('hidden');

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_install_demo_posts`,
						'type' : $btn.data('data'),
						'nonce' : wpbhVars.ajaxNonce
					},
					success: function( response ) {

						$btn.removeAttr('disabled');
						$loader.addClass('hidden');

						$btn.pointer({
							content: response,
							position: {
								edge: 'left',
								align: 'center'
							}
						}).pointer('open');

					}
				});

			});

		}

		/**
		 * Custom settings behaviors
		 */
		setupSettingsLayout() {

			// table of contents
			$('#wpbh-settings-toc').toc();

			// sticky blocks on settings page
			const $stickyBlocks = $( '.wpbh-stick-in-parent');

			const makeStickyBlocks = () => {
				if( $stickyBlocks.length ) {
					$stickyBlocks.stick_in_parent({
						offset_top: $('#wpadminbar').outerHeight() + 15
					});
				}
			};

			$( window ).resize( function() {
				let windowWidth = $( window ).width();
				if( windowWidth > 1600 ) {
					makeStickyBlocks();
				} else {
					$stickyBlocks.trigger( 'sticky_kit:detach');
				}
			});

			$( window ).trigger('resize');

			// system status textarea
			$('textarea#system_status, textarea#error_log').on( 'click', function( e ) {
				e.preventDefault();
				$(this).select();
				document.execCommand('copy');
			});

		}

		/**
		 * Preview fonts
		 */
		handleFontsPreview() {

			const loadGoogleFont = ( family ) => {

				const url = `https://fonts.googleapis.com/css?family=${family}`;

        if( $("link[href*='" + url + "']").length === 0 ){
					$( 'link:last').after('<link href="' + url + '" rel="stylesheet" type="text/css">');
				}

			}

			$('#primary_font, #secondary_font').on( 'change', function( e ) {
				e.preventDefault();
				let $el = $(this),
				family = $el.val(),
				fontType = $el.attr('id').indexOf( 'primary') >= 0 ? 'primary' : 'secondary';

				if( family == '' || family == 'custom' || family == 'inherit' ) {
					family = '';
				}

				loadGoogleFont( family );
				$('#wpbh-text-preview-font-' + fontType ).css('font-family', family );
				if( fontType == 'primary' ) {
					$('#wpbh-text-preview-font-bigger' ).css('font-family', family );
					$('#wpbh-text-preview-font-smaller' ).css('font-family', family );
				}
			});

			$('#primary_font, #secondary_font').trigger('change');

			const $fontSizeInputs = $('.wpbh-range-slider');
			let sliderTimer;

			if( $fontSizeInputs.length ) {
				$fontSizeInputs.ionRangeSlider({
					onChange: function( data) {

						let inputId = $( data.input ).attr('id'),
						val = data.from;

						switch( inputId ) {
							case 'primary_font_size-desktop':
								$('#wpbh-text-preview-font-primary').css('font-size', val + 'px' );
							break;
							case 'primary_font_line_height-desktop':
								$('#wpbh-text-preview-font-primary').css('line-height', val + 'px' );
							break;
							case 'heading_font_size-desktop':
								$('#wpbh-text-preview-font-secondary').css('font-size', val + 'px' );
							break;
							case 'heading_font_line_height-desktop':
								$('#wpbh-text-preview-font-secondary').css('line-height', val + 'px' );
							break;
							case 'bigger_font_size-desktop':
								$('#wpbh-text-preview-font-bigger').css('font-size', val + 'px' );
							break;
							case 'bigger_font_line_height-desktop':
								$('#wpbh-text-preview-font-bigger').css('line-height', val + 'px' );
							break;
							case 'smaller_font_size-desktop':
								$('#wpbh-text-preview-font-smaller').css('font-size', val + 'px' );
							break;
							case 'smaller_font_line_height-desktop':
								$('#wpbh-text-preview-font-smaller').css('line-height', val + 'px' );
							break;

							case 'tl_shadow-offset-x':
							case 'tl_shadow-offset-y':
							case 'tl_shadow-blur':
							case 'tl_shadow-spread':

								let offsetX = $('#tl_shadow-offset-x').val(),
								offsetY = $('#tl_shadow-offset-y').val(),
								blur = $('#tl_shadow-blur').val(),
								spread = $('#tl_shadow-spread').val(),
								color = $('#tl_shadow_color').val();

								$('#wpbh-preview-light').css('box-shadow', `${offsetX}px ${offsetY}px ${blur}px ${spread}px ${color}` );

							break;

							case 'td_shadow-offset-x':
							case 'td_shadow-offset-y':
							case 'td_shadow-blur':
							case 'td_shadow-spread':

								let tdOffsetX = $('#td_shadow-offset-x').val(),
								tdOffsetY = $('#td_shadow-offset-y').val(),
								tdBlur = $('#td_shadow-blur').val(),
								tdSpread = $('#td_shadow-spread').val(),
								tdColor = $('#td_shadow_color').val();

								$('#wpbh-preview-dark').css('box-shadow', `${tdOffsetX}px ${tdOffsetY}px ${tdBlur}px ${tdSpread}px ${tdColor}` );

							break;
						}

						if( sliderTimer) {
							window.clearTimeout( sliderTimer);
						}

						sliderTimer = window.setTimeout( function() {
							$(document.body).trigger('sticky_kit:recalc');
						}, 300 );
						
					}
				});
			}

		}

		/**
		 * Preview colors
		 */
		handleColorsPreview() {

			const $elems = $('#wpbh-accent-colors .wpbh-color-picker');

			$elems.on( 'change', function( e ) {
				e.preventDefault();

				const $el = $(this),
				val = $el.val(),
				id = $el.attr('id'),
				$box = $( `#wpbh-${id}-preview` ),
				$boxes = $('.wpbh-colors-preview-box'),
				$primaryAccentBox = $('#wpbh-alpha_accent_color-preview');

				if( id == 'accent_inner' ) {
					$boxes.not( $primaryAccentBox ).css( 'color', val );
				} else if( id == 'primary_accent_inner' ) {
					$primaryAccentBox.css( 'color', val );
				} else {
					$box.css('background-color', val );
				}

			});

			$elems.trigger('change');

		}

		/**
		 * Preview themes
		 */
		handleThemesPreview() {

			$('#settings-light-theme-cp .wpbh-color-picker, #settings-dark-theme-cp .wpbh-color-picker').on( 'change', function( e ) {
				e.preventDefault();

				const $el = $(this),
				newColor = $el.val(),
				elId = $el.attr('id').substring(3),
				themeType = $el.parents('.wpbh-settings-theme-cp').attr('id').indexOf( 'light') >= 0 ? 'light' : 'dark',
				$previewBox = $( '#wpbh-preview-' + themeType ),
				$previewText = $previewBox.find('.wpbh-box_body_text > div'),
				$previewHeader = $previewBox.find('.wpbh-box_head'),
				$previewData = $previewBox.find('.wpbh-box_head_data'),
				$previewAltBg = $previewBox.find('.wpbh-box_head_img'),
				$previewIcon = $previewBox.find('svg > path');

				switch( elId ) {
					case 'border_color':
						$previewBox.css('border-color', newColor );
					break;
					case 'icon_color':
						$previewIcon.css('fill', newColor );
					break;
					case 'shadow_color':

						if( themeType == 'light' ) {

							let offsetX = $('#tl_shadow-offset-x').val(),
							offsetY = $('#tl_shadow-offset-y').val(),
							blur = $('#tl_shadow-blur').val(),
							spread = $('#tl_shadow-spread').val(),
							color = $('#tl_shadow_color').val();
							$previewBox.css('box-shadow', `${offsetX}px ${offsetY}px ${blur}px ${spread}px ${color}` );

						} else {

							let tdOffsetX = $('#td_shadow-offset-x').val(),
							tdOffsetY = $('#td_shadow-offset-y').val(),
							tdBlur = $('#td_shadow-blur').val(),
							tdSpread = $('#td_shadow-spread').val(),
							tdColor = $('#td_shadow_color').val();
							$previewBox.css('box-shadow', `${tdOffsetX}px ${tdOffsetY}px ${tdBlur}px ${tdSpread}px ${newColor}` );

						}

					break;
					case 'header_color':
						$previewHeader.css('background-color', newColor );
					break;
					case 'text_color':
						$previewText.css('background-color', newColor );
					break;
					case 'text_alt_color':
						$previewData.css('background-color', newColor );
					break;
					case 'primary_bg_color':
						$previewBox.css('background-color', newColor );
					break;
					case 'secondary_bg_color':
						$previewAltBg.css('background-color', newColor );
					break;
				}

			});

		}

		/**
		 * Custom code editors
		 */
		setupCustomEditors() {

			const $textareaCSS = $('#custom_css');
			if( $textareaCSS.length ) {
				const editorCSS = ace.edit( 'custom_css_ace');
				editorCSS.session.setMode( 'ace/mode/css');
				editorCSS.getSession().on( 'change', function(){
					$textareaCSS.val( editorCSS.getSession().getValue());
				});
				$textareaCSS.val( editorCSS.getSession().getValue());
			}

			const $textareaJS = $('#custom_js');
			if( $textareaJS.length ) {
				const editorJS = ace.edit( 'custom_js_ace');
				editorJS.session.setMode( 'ace/mode/javascript');
				editorJS.getSession().on( 'change', function(){
					$textareaJS.val( editorJS.getSession().getValue());
				});
				$textareaCSS.val( editorJS.getSession().getValue());
			}

		}

		/**
		 * Clear error log
		 */
		handleClearErrorLogButton() {

			$('#clear_error_log').on( 'click', function( e ) {
				e.preventDefault();

				const $btn = $(this),
				$loader = $btn.next('.wpbh-loader');

				if( $btn.is( '[disabled]') ) {
					return false;
				}

				$btn.attr('disabled', 'disabled');
				$loader.removeClass('hidden');

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_clear_error_log`,
						'nonce' : wpbhVars.ajaxNonce
					},
					success: function( response ) {

						$btn.removeAttr('disabled');
						$loader.addClass('hidden');

						$('textarea#error_log').val('');

						$btn.pointer({
							content: response,
							position: {
								edge: 'left',
								align: 'center'
							}
						}).pointer('open');

					}
				});

			});

		}

		/**
		 * Flush cache
		 */
		handleFlushCacheButton() {

			$('#flush_hub_cache').on( 'click', function( e ) {
				e.preventDefault();

				const $btn = $(this),
				$loader = $btn.next('.wpbh-loader');

				if( $btn.is( '[disabled]') ) {
					return false;
				}

				$btn.attr('disabled', 'disabled');
				$loader.removeClass('hidden');

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_flush_cache`,
						'nonce' : wpbhVars.ajaxNonce
					},
					success: function( response ) {

						$btn.removeAttr('disabled');
						$loader.addClass('hidden');

						$btn.pointer({
							content: response,
							position: {
								edge: 'left',
								align: 'center'
							}
						}).pointer('open');

					}
				});

			});

		}

		/**
		 * Reset settings
		 */
		handleResetSettingsButton() {

			$('#reset_settings').on( 'click', function( e ) {
				e.preventDefault();

				const $btn = $(this),
				$loader = $btn.next('.wpbh-loader');

				if( $btn.is( '[disabled]') ) {
					return false;
				}

				$btn.attr('disabled', 'disabled');
				$loader.removeClass('hidden');

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_reset_settings`,
						'nonce' : wpbhVars.ajaxNonce
					},
					success: function( response ) {

						$btn.removeAttr('disabled');
						$loader.addClass('hidden');

						$btn.pointer({
							content: response,
							position: {
								edge: 'left',
								align: 'center'
							}
						}).pointer('open');

						window.location.reload( true );

					}
				});

			});

		}

		/**
		 * Save settings through AJAX
		 */
		handleSaveSettings() {
	
			$('#save').on( 'click', function( e ) {
				e.preventDefault();

				const $btn = $(this),
				$loader = $('#loading-indicator'),
				$form = $('#wpbh-settings-form');

				if( $btn.is( '[disabled]') ) {
					return false;
				}

				$btn.attr('disabled', 'disabled');
				$loader.addClass('active');

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_save_settings`,
						'data' : $form.serialize(),
						'nonce' : wpbhVars.ajaxNonce
					},
					success: function( response ) {

						$btn.removeAttr('disabled');
						$loader.removeClass('active');

						$btn.val( $btn.data('txt-saved') ).addClass('wpbh-success');

						setTimeout( function() {
							$btn.val( $btn.data('txt-default') ).removeClass('wpbh-success');
						}, 1500 );

					}
				});

			});

		}
	
	}

	new WPBlocksHubSettings();
	
})( window.jQuery );