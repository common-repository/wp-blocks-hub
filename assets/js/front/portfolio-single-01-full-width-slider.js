'use strict';

(function($){

	const $gallery = $('#wpbh-portfolio-single-gallery');

	$gallery.slick({
		dots: true,
		arrows: false,
		infinite: true,
		autoplay: true,
		autoplaySpeed: 5000,
		adaptiveHeight: true,
		customPaging: function(slider, i) {
			return '<a href="javascript:;" class="wpbh-slick-dot"></a>';
		},
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 1
				}
			}
		]
	});

})( window.jQuery );