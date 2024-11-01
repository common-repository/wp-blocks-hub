<?php
namespace WPBlocksHub\Controller;

/**
 * SVG Support for WP
 **/
class SVG {

	function __construct() {

		add_filter( 'upload_mimes', [ $this, 'add_upload_types' ] );

		add_action( 'admin_init', [ $this, 'fix_svg_thumbs' ] );
		add_filter( 'wp_prepare_attachment_for_js', [ $this, 'fix_svgs_response_for_svg' ], 10, 3 );

	}

	function add_upload_types( $existing_mimes ) {
		$existing_mimes['svg'] = 'image/svg+xml';
		return $existing_mimes;
	}

	function fix_svg_thumbs() {

		$screen = get_current_screen();

		if ( is_object($screen) && $screen->id == 'upload' ) {

			function wpbh_svgs_fix_thumbs_filter( $content ) {
				return apply_filters( 'final_output', $content );
			}

			ob_start( 'wpbh_svgs_fix_thumbs_filter' );

			add_filter( 'final_output', 'wpbh_svgs_fix_final_output' );

			function wpbh_svgs_fix_final_output( $content ) {

				$content = str_replace(
					'<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
					'<# } else if ( \'svg+xml\' === data.subtype ) { #>
						<img class="details-image" src="{{ data.url }}" draggable="false" />
						<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
					$content
				);

				$content = str_replace(
					'<# } else if ( \'image\' === data.type && data.sizes ) { #>',
					'<# } else if ( \'svg+xml\' === data.subtype ) { #>
						<div class="centered">
							<img src="{{ data.url }}" class="thumbnail" draggable="false" />
						</div>
						<# } else if ( \'image\' === data.type && data.sizes ) { #>',
					$content
				);

				return $content;

			}

		}

	}

	function fix_svgs_response_for_svg( $response, $attachment, $meta ) {
		if ( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ) {

			$svg_path = get_attached_file( $attachment->ID );

			if ( ! file_exists( $svg_path ) ) {
				// If SVG is external, use the URL instead of the path
				$svg_path = $response[ 'url' ];
			}

			$dimensions = $this->svgs_get_dimensions( $svg_path );

			$response[ 'sizes' ] = [
				'full' => [
					'url' => $response[ 'url' ],
					'width' => $dimensions->width,
					'height' => $dimensions->height,
					'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait'
				]
			];

		}

		return $response;
	}

	function svgs_get_dimensions( $svg ) {
		$svg = simplexml_load_file( $svg );

		if ( $svg === FALSE ) {

			$width = '0';
			$height = '0';

		} else {

			$attributes = $svg->attributes();
			$width = (string) $attributes->width;
			$height = (string) $attributes->height;
		}

		return (object) [ 'width' => $width, 'height' => $height ];
	}

}

?>