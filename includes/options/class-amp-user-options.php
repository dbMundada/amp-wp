<?php
/**
 * Class AMP_User_Options.
 *
 * @since 1.6.0
 *
 * @package AMP
 */

/**
 * Class AMP_User_Options
 *
 * @since 1.6.0
 */
final class AMP_User_Options {

	/**
	 * Key for the user option (stored in amp_features user meta) enabling or disabling developer tools.
	 *
	 * @var string
	 */
	const USER_OPTION_DEVELOPER_TOOLS = 'amp_dev_tools_enabled';

	/**
	 * Sets up hooks.
	 */
	public static function init() {
		add_filter( 'amp_setup_wizard_data', [ __CLASS__, 'inject_setup_wizard_data' ] );
		add_action( 'rest_api_init', [ __CLASS__, 'register_user_meta' ] );
		add_filter( 'get_user_metadata', [ __CLASS__, 'maybe_initialize_enable_developer_tools_setting' ], 10, 3 );
		add_filter( 'update_user_metadata', [ __CLASS__, 'update_enable_developer_tools_permission_check' ], 10, 4 );
	}

	/**
	 * Registers user meta related to validation management.
	 *
	 * @since 1.6.0
	 */
	public static function register_user_meta() {
		register_meta(
			'user',
			self::USER_OPTION_DEVELOPER_TOOLS,
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'boolean',
			]
		);
	}

	/**
	 * Add fields relevant to user options to the data passed to the setup wizard app.
	 *
	 * @param array $data Associative array of data provided to the app.
	 * @return array Filtered array.
	 */
	public static function inject_setup_wizard_data( $data ) {
		$data['USER_OPTION_DEVELOPER_TOOLS'] = self::USER_OPTION_DEVELOPER_TOOLS;
		$data['USER_REST_ENDPOINT']          = rest_url( 'wp/v2/users/me' );

		return $data;
	}

	/**
	 * Initialize a user's dev tools enabled setting if it does not yet exist.
	 *
	 * @see get_metadata
	 *
	 * @param any    $value Null if the value has not yet been filtered.
	 * @param int    $object_id Object ID associated with the meta data.
	 * @param string $key The metadata key.
	 * @return any Null to prevent filtering.
	 */
	public static function maybe_initialize_enable_developer_tools_setting( $value, $object_id, $key ) {
		if ( self::USER_OPTION_DEVELOPER_TOOLS !== $key ) {
			return $value;
		}

		$meta            = get_user_meta( $object_id );
		$metadata_exists = array_key_exists( self::USER_OPTION_DEVELOPER_TOOLS, $meta );

		if ( ! $metadata_exists ) {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'amp_validate' ) ) {
				update_user_meta( $object_id, $key, false );
			} else {
				update_user_meta( $object_id, $key, true );
			}
		}

		return $value; // Should stay null. The new value is retrieved and cached at the calling location after the hook.
	}

	/**
	 * Checks whether a user is allowed to update their enable developer tools setting.
	 *
	 * @see update_metadata
	 *
	 * @param false|null $check Null if the setting can be updated. False to block updating.
	 * @param int        $object_id The object ID.
	 * @param string     $meta_key The meta key.
	 * @param any        $meta_value The new value.
	 * @return false|null The filtered result.
	 */
	public static function update_enable_developer_tools_permission_check( $check, $object_id, $meta_key, $meta_value ) {
		if ( self::USER_OPTION_DEVELOPER_TOOLS !== $meta_key ) {
			return $check;
		}

		// Only users with specified permissions can have it set to true.
		if ( true === $meta_value && ! current_user_can( 'manage_options' ) && ! current_user_can( 'amp_valudate' ) ) {
			return false;
		}

		return $check;
	}
}
