<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the accounts creation page.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/admin/partials/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$connected = get_option( 'mwb-' . $this->crm_slug . '-gf-crm-connected', false );

?>
<?php if ( '1' !== get_option( 'mwb-' . $this->crm_slug . '-gf-active', false ) || '1' !== $connected ) : ?>
	<?php if ( '1' !== $connected ) : ?>
		<section class="mwb-intro">
			<div class="mwb-content-wrap">
				<div class="mwb-intro__header">
					<h2 class="mwb-section__heading">
						<?php /* translators: %s: CRM Name. */ ?>
						<?php echo sprintf( esc_html__( 'Getting started with Gravity Forms and %s', 'mwb-gf-integration-with-salesforce' ), esc_html( $this->crm_name ) ); ?>
					</h2>
				</div>
				<div class="mwb-intro__body mwb-intro__content">
					<p>
					<?php
					echo sprintf(
						/* translators: %1$s, %2$s, %4$s : CRM Name, %3$s: String */
						esc_html__( 'With this GF %1$s Integration you can easily sync all your Gravity Form Submissions data over %2$s CRM. It will create %3$s over %4$s CRM, based on your Gravity Form Feed data.', 'mwb-gf-integration-with-salesforce' ),
						esc_html( $this->crm_name ),
						esc_html( $this->crm_name ),
						esc_html( 'Contacts, Leads, Case etc.' ),
						esc_html( $this->crm_name )
					);
					?>
					</p>
					<ul class="mwb-intro__list">
						<li class="mwb-intro__list-item">
							<?php /* translators: %s: CRM Name. */ ?>
							<?php echo sprintf( esc_html__( 'Connect your %s CRM account with GF.', 'mwb-gf-integration-with-salesforce' ), esc_html( $this->crm_name ) ); ?>
						</li>
						<li class="mwb-intro__list-item">
						<?php /* translators: %s: CRM Name. */ ?>
							<?php echo sprintf( esc_html__( 'Sync your data over %s CRM.', 'mwb-gf-integration-with-salesforce' ), esc_html( $this->crm_name ) ); ?>
						</li>
					</ul>
					<div class="mwb-intro__button">
						<a href="javascript:void(0)" class="mwb-btn mwb-btn--filled" id="mwb-showauth-form">
							<?php esc_html_e( 'Connect your Account.', 'mwb-gf-integration-with-salesforce' ); ?>
						</a>
					</div>
				</div> 
			</div>
		</section>
	<?php endif; ?>

	<!--============================================================================================
										Authorization form start.
	================================================================================================-->

	<div class="mwb_sf_gf__account-wrap <?php echo esc_html( false === $connected ? 'row-hide' : '' ); ?>" id="mwb-gf-auth_wrap">

		<!-- Logo section start -->
		<div class="mwb-sf_gf__logo-wrap">
			<div class="mwb-sf_gf__logo-salesforce">
				<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/salesforce-logo.png' ); ?>" alt="<?php esc_html_e( 'Salesforce', 'mwb-gf-integration-with-salesforce' ); ?>">
			</div>
			<div class="mwb-sf_gf__logo-contact">
				<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/gravity-form.png' ); ?>" alt="<?php esc_html_e( 'GF', 'mwb-gf-integration-with-salesforce' ); ?>">
			</div>
		</div>
		<!-- Logo section end -->

		<!-- Login form start -->
		<form method="post" id="mwb_sf_gf_account_form">

			<div class="mwb_sf_gf_table_wrapper">
				<div class="mwb_sf_gf_account_setup">
					<h2>
						<?php esc_html_e( 'Enter your credentials here', 'mwb-gf-integration-with-salesforce' ); ?>
					</h2>
				</div>

				<table class="mwb_sf_gf_table">
					<tbody>
						<div class="mwb-auth-notice-wrap row-hide">
							<p class="mwb-auth-notice-text">
								<?php esc_html_e( 'Authorization has been successful ! Validating Connection .....', 'mwb-gf-integration-with-salesforce' ); ?>
							</p>
						</div>

						<!-- Integration method start -->
						<tr class="mwb-gf-integration-method-tr">
							<th>
								<label><?php esc_html_e( 'Integration Method', 'mwb-gf-integration-with-salesforce' ); ?></label>
							</th>

							<td class="mwb-gf-integration-method-td">
								<?php
								$method = ! empty( $params['method'] ) ? sanitize_text_field( wp_unslash( $params['method'] ) ) : '';
								?>
								<label>
									<input type="radio" name="mwb_account[integration_method]" value="api" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-api-method" <?php checked( 'api', $method ); ?> >
									<?php esc_html_e( 'REST API', 'mwb-gf-integration-with-salesforce' ); ?>
								</label>
								<label>
									<input type="radio" name="mwb_account[integration_method]" value="web" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-web-method" <?php checked( 'web', $method ); ?> >
									<?php esc_html_e( 'Web-to-Lead or Web-to-Case (use this if REST API is disabled for your Organization)', 'mwb-gf-integration-with-salesforce' ); ?>
								</label> 
							</td>
						</tr>
						<!-- Integration method end -->

						<!-- Environment start  -->
						<tr>
							<th>							
								<label><?php esc_html_e( 'Environment', 'mwb-gf-integration-with-salesforce' ); ?></label>
							</th>

							<td>
								<?php
								$env = ! empty( $params['environment'] ) ? sanitize_text_field( wp_unslash( $params['environment'] ) ) : '';
								?>

								<select  name="mwb_account[env]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-environment" required>
									<option value="production" <?php selected( $env, 'production' ); ?> ><?php esc_html_e( 'Production', 'mwb-gf-integration-with-salesforce' ); ?></option>
									<option value="sandbox" <?php selected( $env, 'sandbox' ); ?> ><?php esc_html_e( 'Sandbox', 'mwb-gf-integration-with-salesforce' ); ?></option>
								</select>

							</td>
						</tr>
						<!-- Environment end -->


						<!-- Custom App -->
						<tr class="mwb-sf-gf-use-custom-app">
							<th>
								<label><?php esc_html_e( 'Use Custom app', 'mwb-gf-integration-with-salesforce' ); ?></label>
							</th>
							<td>
								<?php $custom_app = $params['custom_app']; ?>
								<div class="mwb-switch">
									<input type="checkbox" class="mwb_sf-gf_enable_own_app" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf_enable_own_app" name="mwb_account[enable_own_app]" value="yes" <?php checked( 'yes', $custom_app ); ?>>
								</div>
							</td>
						</tr>


						<!-- Consumer key start  -->
						<tr class="mwb-api-fields row-hide">
							<th>							
								<label><?php esc_html_e( 'Consumer Key', 'mwb-gf-integration-with-salesforce' ); ?></label>
							</th>

							<td>
								<?php
								$consumer_key = ! empty( $params['consumer_key'] ) ? sanitize_text_field( wp_unslash( $params['consumer_key'] ) ) : '';
								?>
								<div class="mwb-sf-gf__secure-field">
									<input type="password"  name="mwb_account[app_id]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-consumer-key" value="<?php echo esc_html( $consumer_key ); ?>" required>
									<div class="mwb-sf-gf__trailing-icon">
										<span class="dashicons dashicons-visibility mwb-toggle-view"></span>
									</div>
								</div>
							</td>
						</tr>
						<!-- Consumer key end -->

						<!-- Consumer Secret Key start -->
						<tr class="mwb-api-fields">
							<th>
								<label><?php esc_html_e( 'Consumer Secret', 'mwb-gf-integration-with-salesforce' ); ?></label>
							</th>

							<td>
								<?php
								$secret_key = ! empty( $params['consumer_secret'] ) ? sanitize_text_field( wp_unslash( $params['consumer_secret'] ) ) : '';
								?>

								<div class="mwb-sf-gf__secure-field">
									<input type="password" name="mwb_account[secret_key]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-consumer-secret" value="<?php echo esc_html( $secret_key ); ?>" required>
									<div class="mwb-sf-gf__trailing-icon">
										<span class="dashicons dashicons-visibility mwb-toggle-view"></span>
									</div>
								</div>
							</td>
						</tr>
						<!-- Consumer Secret Key End -->

						<!-- Callback url start -->
						<tr class="mwb-api-fields">
							<th>
								<label><?php esc_html_e( 'Callback URL', 'mwb-gf-integration-with-salesforce' ); ?></label>
							</th>

							<td>
								<input type="url" name="mwb_account[redirect_url]" value="<?php echo esc_html( rtrim( admin_url() ) ); ?>" readonly>
								<p class="mwb-description">
									<?php esc_html_e( 'Web-Protocol must be HTTPS in order to successfully authorize with Salesforce', 'mwb-gf-integration-with-salesforce' ); ?>
								</p>
							</td>
						</tr>
						<!-- Callback url end -->

						<!-- Org ID start -->
						<tr class="mwb-web-fields">
							<th>
								<label><?php esc_html_e( 'Organization ID', 'mwb-gf-integration-with-salesforce' ); ?></label>
							</th>

							<td>
								<?php
								$orgid = ! empty( $params['orgid'] ) ? sanitize_text_field( wp_unslash( $params['orgid'] ) ) : '';
								?>
								<div class="mwb-sf-gf__secure-field">
									<input type="password" name="mwb_account[org_id]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-orgid" value="<?php echo esc_attr( $orgid ); ?>" required>
									<div class="mwb-sf-gf__trailing-icon">
										<span class="dashicons dashicons-visibility mwb-toggle-view"></span>
									</div>
								</div>
							</td>
						</tr>
						<!-- Org ID end -->

						<!-- Salesforce domain start -->
						<tr class="mwb-web-fields">
							<th>
								<label><?php esc_html_e( 'Salesforce Domain', 'mwb-gf-integration-with-salesforce' ); ?></label>
							</th>

							<td>
								<?php
									$_domain = ! empty( $params['domain'] ) ? sanitize_text_field( wp_unslash( $params['domain'] ) ) : '';
								?>
								<input type="url" name="mwb_account[sf_domain]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-domain" value="<?php echo esc_attr( $_domain ); ?>">
							</td>
						</tr>
						<!-- Salesforce domain end -->


						<!-- Save & connect account start -->
						<tr>
							<th>
							</th>
							<td>
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( '?mwb-gf-perform-auth=1' ) ) ); ?>" class="mwb-btn mwb-btn--filled mwb_sf_gf_submit_account" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-authorize-button" ><?php esc_html_e( 'Authorize', 'mwb-gf-integration-with-salesforce' ); ?></a>
							</td>
						</tr>
						<!-- Save & connect account end -->
					</tbody>
				</table>
			</div>
		</form>
		<!-- Login form end -->

		<!-- Info section start -->
		<div class="mwb-intro__bottom-text-wrap ">
			<p>
				<?php esc_html_e( 'Don’t have an account yet. ', 'mwb-gf-integration-with-salesforce' ); ?>
				<a href="https://www.salesforce.com/" target="_blank" class="mwb-btn__bottom-text"><?php esc_html_e( 'Create A Free Account', 'mwb-gf-integration-with-salesforce' ); ?></a>
			</p>
			<p>
				<?php esc_html_e( 'Check app setup guide. ', 'mwb-gf-integration-with-salesforce' ); ?>
				<a href="javascript:void(0)" class="mwb-btn__bottom-text trigger-setup-guide"><?php esc_html_e( 'Show Me How', 'mwb-gf-integration-with-salesforce' ); ?></a>
			</p>
		</div>
		<!-- Info section end -->
	</div>

<?php else : ?>

	<!-- Successfull connection start -->
	<section class="mwb-sync">
		<div class="mwb-content-wrap">
			<div class="mwb-sync__header">
				<h2 class="mwb-section__heading">
				<?php /* translators: %s: CRM Name. */ ?>
					<?php echo sprintf( esc_html__( 'Congrats! You’ve successfully set up the MWB GF Integration with %s Plugin.', 'mwb-gf-integration-with-salesforce' ), esc_html( $this->crm_name ) ); ?>
				</h2>
			</div>
			<div class="mwb-sync__body mwb-sync__content-wrap">            
				<div class="mwb-sync__image">    
					<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/congo.jpg' ); ?>" >
				</div>       
				<div class="mwb-sync__content">            
					<p> 
						<?php
						/* translators: %s: CRM Name. */
						echo sprintf( esc_html__( 'Now you can go to the dashboard and check for the synced data. You can check your feeds, edit them and resync the data if you want. If you do not see your data over %s CRM, you can check the logs for any possible error.', 'mwb-gf-integration-with-salesforce' ), esc_html( $this->crm_name ) );
						?>
					</p>
					<div class="mwb-sync__button">
						<a href="javascript:void(0)" class="mwb-btn mwb-btn--filled mwb-onboarding-complete">
							<?php esc_html_e( 'View Dashboard', 'mwb-gf-integration-with-salesforce' ); ?>
						</a>
					</div>
				</div>             
			</div>       
		</div>
	</section>
	<!-- Successfull connection end -->

<?php endif; ?>
