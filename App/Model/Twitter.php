<?php

namespace WPBlocksHub\Model;
use WPBlocksHub\Helper\Utils;
use WPBlocksHub\Helper\Settings;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitter model
 *
 */
class Twitter {
	
	/**
	 * Get latest tweets
	 */
	public function get_latest_tweets( $limit ) {

		$transient_name = WPBH()->Config['prefix'] . '_tweets_num_' . $limit;

		// delete_transient( $transient_name );
		$tweets_cache = get_transient( $transient_name );

		if( defined( 'WPBH_DEBUG') && WPBH_DEBUG ) {
			$tweets_cache = false;
		}

		if( false === $tweets_cache || ( $tweets_cache['updated'] + DAY_IN_SECONDS < time() ) ) {

			$connection = new TwitterOAuth(
				Settings::get_option( 'twitter_consumer_key' ),
				Settings::get_option( 'twitter_consumer_secret' ),
				Settings::get_option( 'twitter_access_token' ),
				Settings::get_option( 'twitter_access_token_secret' )
			);

			$tweets = $connection->get( 'statuses/home_timeline', [ 'count' => $limit, 'exclude_replies' => true ]);

			if( isset( $tweets->errors ) && ! empty( $tweets->errors ) ) {

				return [
					'result' => false,
					'error_msg' => $tweets->errors[0]->message
				];

			} else {

				$new_transient = [
					'updated' => time(),
					'tweets' => $tweets
				];

				set_transient( $transient_name, $new_transient, DAY_IN_SECONDS );

				return [
					'result' => true,
					'tweets' => $tweets
				];

			}

		}

		return [
			'result' => true,
			'tweets' => $tweets_cache['tweets']
		];

	}
	
}

?>