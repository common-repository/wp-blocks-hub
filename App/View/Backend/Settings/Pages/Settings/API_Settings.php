<?php
	/**
	 * @accept $data
	 */
	use WPBlocksHub\Helper\Forms;
	use WPBlocksHub\Helper\Settings;
?>
<h3 id="settings-api" class="title"><?php _e( 'API Settings', 'wp-blocks-hub'); ?></h3>

<p><?php _e( 'Some of blocks that use third-party API (like Google Maps) requiere API keys. Obtain your own keys and paste them here.', 'wp-blocks-hub'); ?></p>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_text( [
				'name' => 'google_maps_api_key',
				'title' => __( 'Google Maps API Key', 'wp-blocks-hub'),
				'desc' => sprintf( __( 'Read more information about how to get Google Maps API key <a href="%s">here</a>. Google Maps JavaScript API v3 is REQUIRED to display blocks that use Google Maps.', 'wp-blocks-hub'), 'https://developers.google.com/maps/documentation/javascript/get-api-key#get-the-api-key'),
				'value' => Settings::get_option( 'google_maps_api_key' )
			]);

		?>
	</tbody>
</table>

<h3><?php _e( 'Twitter API', 'wp-blocks-hub'); ?></h3>

<table class="form-table">
	<tbody>
		<?php

			Forms::admin_input_text( [
				'name' => 'twitter_consumer_key',
				'title' => __( 'Twitter Consumer Key', 'wp-blocks-hub'),
				'desc' => sprintf( __( 'Read more information about how to get Twitter API keys <a href="%s">here</a>. These keys are REQUIRED to display tweets.', 'wp-blocks-hub'), 'https://developer.twitter.com/en/docs/basics/authentication/guides/access-tokens'),
				'value' => Settings::get_option( 'twitter_consumer_key' )
			]);

			Forms::admin_input_text( [
				'name' => 'twitter_consumer_secret',
				'title' => __( 'Twitter Consumer Secret', 'wp-blocks-hub'),
				'value' => Settings::get_option( 'twitter_consumer_secret' )
			]);

			Forms::admin_input_text( [
				'name' => 'twitter_access_token',
				'title' => __( 'Twitter Access Token', 'wp-blocks-hub'),
				'value' => Settings::get_option( 'twitter_access_token' )
			]);

			Forms::admin_input_text( [
				'name' => 'twitter_access_token_secret',
				'title' => __( 'Twitter Access Token Secret', 'wp-blocks-hub'),
				'value' => Settings::get_option( 'twitter_access_token_secret' )
			]);

		?>
	</tbody>
</table>