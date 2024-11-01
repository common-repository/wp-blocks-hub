<?php
	/**
	 * @accept $data
	 */

  echo '<a href="' . get_permalink( $data['post_id'] ) . '">';

  if( has_post_thumbnail( $data['post_id'] ) ) {
    echo get_the_post_thumbnail( $data['post_id'], 'thumbnail' );
  } else {
    echo '<img src="' . WPBH()->Config['plugin_url'] . '/assets/images/admin/thumb.svg' . '" class="no-img" width="45" alt="" />';
  }

  echo '</a>';

?>