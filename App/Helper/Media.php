<?php

namespace WPBlocksHub\Helper;

class Media {

	/**
	 * Print image tag
	 */
	public static function img( $url, $width, $height, $alt = '' ) {

		if( self::is_attachment_svg( $url ) ) {
			return '<img width="' . $width . '" src="' . $url . '" alt="' . $alt . '">';
		} else {
			$img_resized = self::img_resize( $url, $width, $height );
			return '<img width="' . $width . '" srcset="' . $img_resized['src_hdmi'] . ' 2x, ' . $img_resized['src'] . ' 1x" src="' . $img_resized['src'] . '" alt="' . $alt . '">';
		}

	}

	/**
	 * Print inline SVG or standard image tag
	*/
	public static function inline_svg( $image_id ) {

		$feat_img_array = wp_get_attachment_image_src(
			$image_id, 'full', false
		);

		$img = $feat_img_array[0];

		if( self::is_attachment_svg( $feat_img_array[0] ) ) {
			$path = get_attached_file( $image_id );
			return WPBH()->Controller->FS->read( $path );
		} else {
			$img_resized = self::img_resize( $url, $width, $height );
			return '<img src="' . $feat_img_array[0] . '" alt="' . $alt . '">';
		}
	}

	/**
	 * Check if attachment is SVG
	 */
	public static function is_attachment_svg( $attachment_url, $attachment_id = 0 ) {
		
		$attachment_id  = (int) $attachment_id;
		$attachment_url = (string) $attachment_url;
		
		$is_attachment_svg_by_mime = $is_attachment_svg_by_ext = false;
		
		if ( $attachment_url ) {
			$path                     = parse_url( $attachment_url, PHP_URL_PATH );   // get path from url
			$extension                = pathinfo( $path, PATHINFO_EXTENSION );        // get ext from path
			$is_attachment_svg_by_ext = ( strtolower( $extension ) === 'svg' );
		}

		if ( $attachment_id > 0 ) {
			$mime                      = \get_post_mime_type( $attachment_id );
			$is_attachment_svg_by_mime = ( $mime === 'image/svg+xml' );
		}	
		
		return $is_attachment_svg_by_ext || $is_attachment_svg_by_mime;
	}

	/**
	 * Resize image
	 */
	public static function img_resize( $url, $width, $height, $crop = true, $hdmi = true ) {

		$width = absint( $width );
		$height = absint( $height );

		if ( ! class_exists( 'Aq_Resize' ) ) {
			require_once WPBH()->Config['plugin_path'] . '/vendor-custom/aq_resizer/aq_resizer.php';
		}

		if ( strpos( $url, 'http' ) !== 0 ) {
			$protocol = \is_ssl() ? 'https:' : 'http:';
			if ( $url <> '' ) {
				$url = $protocol . $url;
			}
		}

		$src = \aq_resize( $url, $width, $height, $crop );

		if ( ! $src ) {
			$src = $url;
		}

		$src2x = \aq_resize( $url, $width * 2, $height * 2, $crop );

		if ( ! $src2x ) {
			$src2x = $url;
		}

		return [
			'src' => $src,
			'src_hdmi' => $src2x,
		];

	}


}

?>