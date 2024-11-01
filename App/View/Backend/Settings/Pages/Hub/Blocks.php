<?php
	/**
	 * @accept $data
	 */
	foreach( $data->blocks as $block ):
		include 'Block.php';
	endforeach;
?>