'use strict';

(function($){

	const $grid = $('#wpbh-portfolio-archive-01-masonry-grid'),
	id = $grid.attr( 'id');

	const maronry = $grid.masonry({
		itemSelector: '.wpbh-item',
		columnWidth: '.wpbh-item',
		percentPosition: true
	});

	$grid.imagesLoaded().done( function( instance ) {
		maronry.masonry( 'layout' );
	});

	$grid.find('.wpbh-icon-lightbox').swipebox( {
		useCSS: true,
		hideCloseButtonOnMobile: true,
		removeBarsOnMobile: true,
		hideBarsDelay: 3000
	} );

	function handleScroll() {
		$grid.find( '.wpbh-inside:in-viewport(0)').addClass( 'wpbhAnimated');
	}

	$(window).off( 'scroll.' + id ).on( 'scroll.' + id, handleScroll ).trigger( 'scroll');

})( window.jQuery );