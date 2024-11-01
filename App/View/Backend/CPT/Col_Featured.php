<?php
	/**
	 * @accept $data
	 */

	use \WpBlocksHub\Helper\Utils;
	use \WpBlocksHub\Helper\Settings;

  $class_featured = 'dashicons-star-filled';
  $class_normal = 'dashicons-star-empty';
  $class_current = Utils::bool( Settings::get_post_option( $data['post_id'], 'featured' ) ) ? $class_featured : $class_normal;
?>

<a
  href="#"
  data-post-id="<?php echo esc_attr( $data['post_id'] ); ?>"
  data-class-featured="<?php echo esc_attr( $class_featured ); ?>"
  data-class-normal="<?php echo esc_attr( $class_normal ); ?>"
  class="wpbh-featured-post-toggle">
    <i class="dashicons <?php echo esc_attr( $class_current ); ?>"></i>
</a>