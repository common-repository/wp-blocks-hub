<?php
	/**
	 * @accept $data
	 */
	
  use \WpBlocksHub\Helper\Settings;
  if( $data['post_id'] > 0 ) {
    echo $data['post_id'];
  }
?>