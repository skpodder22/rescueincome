<?php
/**
 * Admin View: Page - Support
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap about-wrap" xmlns="http://www.w3.org/1999/html">
	<h1><?php esc_html_e( 'Get Support', 'cleenday-core' ); ?></h1>
	<div class="about-text">
		<?php esc_html_e( 'Sometimes you just need some help. Use this form to submit a support URL and then provide that link to the product developer or our staff.', 'cleenday-core' ); ?>
	</div>
	<div class="redux-badge">
		<i class="el el-redux"></i>
		<span><?php printf( esc_html__( 'Version', 'cleenday-core' ) . ' %s', esc_html( Redux_Core::$version ) ); ?></span>
	</div>

	<?php $this->actions(); ?>
	<?php $this->tabs(); ?>

	<div id="support_div" class="support">

		<!-- multistep form -->
		<form id="supportform">
			<ul id="progressbar" class=" breadcrumb">
				<li class="active"><?php esc_html_e( 'Generate a Support URL', 'cleenday-core' ); ?></li>
				<li href="#"><?php esc_html_e( 'Select Support Type', 'cleenday-core' ); ?></li>
				<li href="#"><?php esc_html_e( 'How to Get Support', 'cleenday-core' ); ?></li>
			</ul>

			<!-- fieldsets -->
			<fieldset>
				<h2 class="fs-title">
					<?php esc_html_e( 'Submit a Support Request', 'cleenday-core' ); ?>
				</h2>

				<h3 class="fs-title" style="margin-top:0;">
					<?php esc_html_e( 'To get started, we will need to generate a support hash.', 'cleenday-core' ); ?>
				</h3>

				<p>
					<?php // translators: %s: Redux Health page. ?>
					<?php echo sprintf( wp_kses( __( 'This will provide to your developer all the information they may need to remedy your issue. This action WILL send information securely to a remote server. To see the type of information sent, please look at the  <a href="%s">Health Check</a> tab.', 'cleenday-core' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'tools.php?page=redux-health' ) ) ); ?>
				</p>

				<p>
					<a href="#" class="docs button button-primary button-large redux_support_hash">
						<?php esc_html_e( 'Generate a Support URL', 'cleenday-core' ); ?>
					</a>
				</p>
				<input
					type="button"
					name="next"
					class="next hide action-button"
					value="Next"
				/>
			</fieldset>

			<fieldset>
				<h2 class="fs-title">
					<?php esc_html_e( 'Select Your Support Type', 'cleenday-core' ); ?>
				</h2>

				<h3 class="fs-subtitle" style="text-align: center;">
					<?php esc_html_e( 'What type of user are you?', 'cleenday-core' ); ?>
				</h3>

				<table id="user_type">
					<tr>
						<td id="is_user">
							<i class="el el-user"></i><br/>
							<?php esc_html_e( 'User', 'cleenday-core' ); ?><br/>
							<small>
								<?php esc_html_e( 'I am a user, using a pre-built product.', 'cleenday-core' ); ?>
							</small>
						</td>
						<td id="is_developer">
							<i class="el el-github"></i><br/>
							<?php esc_html_e( 'Developer', 'cleenday-core' ); ?><br/>
							<small>
								<?php esc_html_e( 'I am a developer, building a product using Redux.', 'cleenday-core' ); ?>
							</small>
						</td>
					</tr>
				</table>

				<input
					type="button"
					name="next"
					class="next action-button hide"
					value="Next"
				/>
			</fieldset>

			<fieldset id="final_support">
				<h2 class="fs-title">
					<?php esc_html_e( 'How to Get Support', 'cleenday-core' ); ?>
				</h2>

				<div class="is_developer">
					<p>
						<?php esc_html_e( 'Please proceed to the Redux Framework issue tracker and supply us with your support URL below. Please also provide any information that will help us to reproduce your issue.', 'cleenday-core' ); ?>
					</p>
					<a href="<?php echo esc_url( 'https://github.com/reduxframework/redux-framework/issues' ); ?>" target="_blank">
						<h4>https://github.com/reduxframework/redux-framework/issues</h4>
					</a>
				</div>

				<div class="is_user">
					<p align="left">
						<?php esc_html_e( 'Listed below are the WordPress plugins and/or theme installed on your site that utilize Redux Framework. We do not directly support products created with our framework.  It is the responsibility of the plugin or theme developer to support their work. You will need to contact the author(s) of the products listed below with your support questions.', 'cleenday-core' ); ?>
					</p>
					<p>
						<strong>
							<?php esc_html_e( 'Please be sure to include for your developer - via cut and paste - the Support URL in the box below.', 'cleenday-core' ); ?>
						</strong>
					</p>
				</div>
				<textarea
						type="text"
						id="support_hash"
						name="hash"
						placeholder="Support Hash"
						readonly="readonly"
						class="hash"
						value="https://support.redux.io/"></textarea>

				<p>
					<em>
						<?php // translators: %s: Support Defined URL. ?>
						<?php echo sprintf( wp_kses( __( 'Should the developer not be responsive, read the <a href="%s" target="_blank">following article</a> before asking for support from us directly.', 'cleenday-core' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'http://docs.redux.io/core/support-defined/' ) ); ?>
					</em>
				</p>
				<input
					type="button"
					name="previous"
					class="previous action-button"
					value="Go Back"
				/>
			</fieldset>
		</form>
		<div class="clear" style="clear:both;"></div>
	</div>
</div>
