<?php
/**
 * Redux Embedded Class
 *
 * @class Redux_Embedded
 * @version 3.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Redux_Embedded
 */
class Redux_Embedded implements themecheck {

	/**
	 * Error array.
	 *
	 * @var array
	 */
	protected $error = array();

	/**
	 * Run checker.
	 *
	 * @param array $php_files Files to check.
	 * @param array $css_files Files to check.
	 * @param array $other_files Files to check.
	 *
	 * @return bool
	 */
	public function check( $php_files, $css_files, $other_files ) {

		$ret   = true;
		$check = Redux_ThemeCheck::get_instance();
		$redux = $check::get_redux_details( $php_files );

		if ( $redux ) {
			if ( ! isset( $_POST['redux_wporg'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				checkcount();
				$this->error[] = '<div class="redux-error">' . sprintf( '<span class="tc-lead tc-recommended">' . esc_html__( 'RECOMMENDED', 'cleenday-core' ) . '</span>: ' . esc_html__( 'If you are submitting to WordPress.org Theme Repository, it is', 'cleenday-core' ) . ' <strong>' . esc_html__( 'strongly', 'cleenday-core' ) . '</strong> ' . esc_html__( 'suggested that you read', 'cleenday-core' ) . ' <a href="%s" target="_blank">' . esc_html__( 'this document', 'cleenday-core' ) . '</a>, ' . esc_html__( 'or your theme will be rejected because of Redux.', 'cleenday-core' ), '//docs.redux.io/core/wordpress-org-submissions/' ) . '</div>';
				$ret           = false;
			} else {
				// TODO Granular WP.org tests!!!
				// Check for Tracking.
				checkcount();
				$tracking = $redux['dir'] . 'inc/tracking.php';
				if ( file_exists( $tracking ) ) {
					$this->error[] = '<div class="redux-error">' . sprintf( '<span class="tc-lead tc-required">' . esc_html__( 'REQUIRED', 'cleenday-core' ) . '</span>: ' . esc_html__( 'You MUST delete', 'cleenday-core' ) . ' <strong> %s </strong>, ' . esc_html__( 'or your theme will be rejected by WP.org theme submission because of Redux.', 'cleenday-core' ), $tracking ) . '</div>';
					$ret           = false;
				}

				// Embedded CDN package
				// use_cdn
				// Arguments.
				checkcount();
				$args          = '<ol>';
				$args         .= "<li><code>'save_defaults' => false</code></li>";
				$args         .= "<li><code>'use_cdn' => false</code></li>";
				$args         .= "<li><code>'customizer_only' => true</code> Non-Customizer Based Panels are Prohibited within WP.org Themes</li>";
				$args         .= "<li><code>'database' => 'theme_mods'</code> (' . esc_html__( 'Optional', 'cleenday-core' ) . ')</li>";
				$args         .= '</ol>';
				$this->error[] = '<div class="redux-error"><span class="tc-lead tc-recommended">' . esc_html__( 'RECOMMENDED', 'cleenday-core' ) . '</span>: ' . esc_html__( 'The following arguments MUST be used for WP.org submissions, or you will be rejected because of your Redux configuration.', 'cleenday-core' ) . $args . '</div>';
			}
		}

		return $ret;
	}

	/**
	 * Return error array.
	 *
	 * @return array
	 */
	public function getError() {
		return $this->error;
	}

}

$themechecks[] = new Redux_Embedded();
