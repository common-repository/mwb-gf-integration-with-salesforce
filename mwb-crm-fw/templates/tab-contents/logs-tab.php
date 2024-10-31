<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the salesforce logs listing aspects of the plugin.
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

?>
<div class="mwb-sf_gf__logs-wrap" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-logs" ajax_url="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>">
	<div class="mwb-sf_gf__logo-wrap">
		<div class="mwb-sf_gf__logo-salesforce">
			<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/salesforce-logo.png' ); ?>" alt="<?php esc_html_e( 'Salesforce', 'mwb-gf-integration-with-salesforce' ); ?>">
		</div>
		<div class="mwb-sf_gf__logo-contact">
			<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/gravity-form.png' ); ?>" alt="<?php esc_html_e( 'GF', 'mwb-gf-integration-with-salesforce' ); ?>">
		</div>
		<?php if ( $params['log_enable'] ) : ?>
				<ul class="mwb-logs__settings-list">
					<li class="mwb-logs__settings-list-item">
						<a id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-clear-log" href="javascript:void(0)" class="mwb-logs__setting-link">
							<?php esc_html_e( 'Clear Log', 'mwb-gf-integration-with-salesforce' ); ?>	
						</a>
					</li>
					<li class="mwb-logs__settings-list-item">
						<a id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-download-log" href="javascript:void(0)"  class="mwb-logs__setting-link">
							<?php esc_html_e( 'Download', 'mwb-gf-integration-with-salesforce' ); ?>	
						</a>
					</li>
				</ul>
		<?php endif; ?>
	</div>
	<div>
		<div>
			<?php if ( $params['log_enable'] ) : ?>
			<div class="mwb-sf_gf__logs-table-wrap">
				<table id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-table" class="display mwb-sf__logs-table dt-responsive nowrap" style="width: 100%;">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Expand', 'mwb-gf-integration-with-salesforce' ); ?></th>
							<th><?php esc_html_e( 'Feed', 'mwb-gf-integration-with-salesforce' ); ?></th>
							<th><?php esc_html_e( 'Feed ID', 'mwb-gf-integration-with-salesforce' ); ?></th>
							<th>
								<?php
								/* translators: %s: CRM name. */
								printf( esc_html__( '%s Object', 'mwb-gf-integration-with-salesforce' ), esc_html( $this->crm_name ) );
								?>
							</th>
							<th>
								<?php
								/* translators: %s: CRM name. */
								printf( esc_html__( '%s ID', 'mwb-gf-integration-with-salesforce' ), esc_html( $this->crm_name ) );
								?>
							</th>
							<th><?php esc_html_e( 'Event', 'mwb-gf-integration-with-salesforce' ); ?></th>
							<th><?php esc_html_e( 'Timestamp', 'mwb-gf-integration-with-salesforce' ); ?></th>
							<th class=""><?php esc_html_e( 'Request', 'mwb-gf-integration-with-salesforce' ); ?></th>
							<th class=""><?php esc_html_e( 'Response', 'mwb-gf-integration-with-salesforce' ); ?></th>
						</tr>
					</thead>
				</table>
			</div>
			<?php else : ?>
				<div class="mwb-content-wrap">
					<?php esc_html_e( 'Please enable the logs from ', 'mwb-gf-integration-with-salesforce' ); ?><a href="<?php echo esc_url( 'admin.php?page=mwb_' . $this->crm_slug . '_gf_page&tab=settings' ); ?>" target="_blank"><?php esc_html_e( 'Settings tab', 'mwb-gf-integration-with-salesforce' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
