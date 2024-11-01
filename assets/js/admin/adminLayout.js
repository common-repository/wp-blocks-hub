'use strict';

window.wpbhMediaLibrary = {};

(function($){

	class WPBlocksHubLayout {

		constructor() {

			this.setupConditionalLogic();
			this.setupCustomInputs();
			this.setupGalleryInputs();
			this.setupImagePicker();
	
		}

		/**
		 * Conditional logic for plugin settings
		 */
		setupConditionalLogic() {

			const $condElems = $('*[data-wpbh-cond-elem]');

			$condElems.hide();

			$condElems.each( function() {

				let $condElem = $(this),
				elName = $condElem.data( 'wpbh-cond-elem'),
				elVal = $condElem.data( 'wpbh-cond-val');

				let $input = $('[name=' + elName + ']');

				let checkVal = () => {
					
					let inputVal = $input.attr('type') == 'radio' ? $('[name=' + elName + ']:checked').val() : $input.val();

					inputVal == elVal ? $condElem.show() : $condElem.hide();

				}

				checkVal();

				$input.off( 'change', checkVal ).on( 'change', checkVal );

			});

		}
	

		/**
		 * Custom inputs
		 */
		setupCustomInputs() {

			// color pickers
			if( $.fn.wpColorPicker ) {
				$('.wpbh-color-picker').wpColorPicker();
			}

			// select2 
			if( $.fn.select2 ) {
				$('.wpbh-select2').select2({
					dropdownCssClass: 'wpbh-select2-dropdown'
				});
			}
			
		}


		/**
		 * Gallery inputs
		 */
		setupGalleryInputs() {

			const updateValues = ( $holder, $input) => {

				const result = [];

				$holder.find('.wpbh-img').each( function() {
					let $img = $(this),
					id = $img.data('id'),
					url = $img.attr('src');

					result.push({
						'id': id,
						'url': url
					});

				});

				$input.val( JSON.stringify( result ) );

				return result;

			};

			$('.wpbh-images-picker-thumbs').each( function() {
				const $box = $(this),
				$itemsHolder = $(this).parent(),
				$itemsInput = $itemsHolder.find('input[type=hidden]');

				$box.sortable({
					update: function( event, ui ) {
						updateValues( $box, $itemsInput );
					}
				}).disableSelection();

				$('.wpbh-images-picker-thumbs').on( 'click', '.wpbh-remove', function( e ) {
					e.preventDefault();

					let $link = $(this);
	
					$link.parents('.wpbh-item').remove();

					updateValues( $itemsHolder, $itemsInput );

				});

			});

			$('.wpbh-images-picker-add').on( 'click', function( e) {
				e.preventDefault();

				const $link = $(this),
				$holder = $link.parent(),
				$itemsHolder = $holder.find('.wpbh-images-picker-thumbs'),
				$itemsInput = $holder.find('input[type=hidden]');

				window.wpbhMediaLibrary = wp.media.frames.wpbhMediaLibrary = wp.media({
					className: 'media-frame wproto-media-frame',
					frame: 'select',
					multiple: true,
					title: wpbhVars.selectImgsStr,
					button: {
						text: wpbhVars.selectOk
					}
				});

				window.wpbhMediaLibrary.on( 'select', function() {

					let attachments = wpbhMediaLibrary.state().get( 'selection').toJSON(),
					html = '';				

					if( attachments.length ) {
						for( let i=0; i<attachments.length; i++ ){

							let el = attachments[ i ],
							url = '';

							if( typeof( el.sizes.thumbnail ) === 'object' ) {
								url = el.sizes.thumbnail.url;
							} else {
								url = el.url;
							}

							html += `<div class="wpbh-item"><img src="${url}" data-id="${el.id}" class="wpbh-img" alt=""><a href="#" class="wpbh-remove dashicons dashicons-no-alt"></a></div>`;

						};

					}

					$itemsHolder.html( html );

					updateValues( $itemsHolder, $itemsInput );

				}).open();

			});

		}

		/**
		 * Image picker
		 */
		setupImagePicker() {

			$( document ).on( 'click', '.wpbh-image-picker .wpbh-image-picker-add', function( e) {
				e.preventDefault();

				const $link = $(this),
				$holder = $link.parent(),
				$itemsHolder = $holder.find('.wpbh-image-picker-thumb'),
				$input = $holder.parents( '.wpbh-image-picker').find('input[type=hidden]'),
				$itemsInput = $holder.find('input[type=hidden]');

				window.wpbhMediaLibrary = wp.media.frames.wpbhMediaLibrary = wp.media({
					className: 'media-frame wproto-media-frame',
					frame: 'select',
					multiple: false,
					title: wpbhVars.selectImgStr,
					button: {
						text: wpbhVars.selectOk
					}
				});

				window.wpbhMediaLibrary.on( 'select', function() {

					let attachment = wpbhMediaLibrary.state().get( 'selection').first().toJSON();

					let url = '';
					if( typeof( attachment.sizes.thumbnail ) === 'object' ) {
						url = attachment.sizes.thumbnail.url;
					} else {
						url = attachment.url;
					}

					let html = `<div class="wpbh-item"><img src="${url}" class="wpbh-img" alt=""><a href="#" class="wpbh-remove dashicons dashicons-no-alt"></a></div>`;
					$itemsHolder.html( html );
					$input.val( attachment.id ).trigger('change');

					// if( typeof( wpWidgets ) === 'object' ) {
					// 	wpWidgets.save( $link.parents('div.widget'), 0, 1, 0 );
					// }

				}).open();

			});

			$( document ).on( 'click', '.wpbh-image-picker .wpbh-remove', function( e) {
				e.preventDefault();

				const $btn = $(this),
				$img = $btn.parents('.wpbh-item'),
				$input = $btn.parents( '.wpbh-image-picker').find('input[type=hidden]');

				$img.remove();
				$input.val('').trigger('change');

			});

		}

	}

	new WPBlocksHubLayout();
	
})( window.jQuery );