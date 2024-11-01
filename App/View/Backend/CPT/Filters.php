<?php
	/**
	 * @accept $data
	 */
  $id = isset( $data['public_id'] ) ? $data['public_id'] : '';
  $featured = isset( $data['featured'] ) ? $data['featured'] : '';
?>

<input type="number" name="public_id" value="<?php echo $id > 0 ? $id : ''; ?>" placeholder="<?php esc_attr_e( 'Filter by ID', 'wp-blocks-hub'); ?>">

<select name="featured">
  <option value=""><?php _e( 'Is featured?', 'wp-blocks-hub'); ?></option>
  <option <?php echo $featured == 'yes' ? 'selected="selected"' : ''; ?> value="yes"><?php _e( 'Featured posts', 'wp-blocks-hub'); ?></option>
  <option <?php echo $featured == 'no' ? 'selected="selected"' : ''; ?> value="no"><?php _e( 'Standard posts', 'wp-blocks-hub'); ?></option>
</select>