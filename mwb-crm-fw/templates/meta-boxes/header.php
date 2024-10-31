<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the header of feeds section.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/mwb-crm-fw/framework/templates/meta-boxes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="mwb_<?php echo esc_attr( $this->crm_slug ); ?>_gf__feeds-wrap">
	<div class="mwb-sf_gf__logo-wrap">
		<div class="mwb-sf_gf__logo-zoho">
			<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/salesforce-logo.png' ); ?>" alt="<?php esc_html_e( 'Salesforce', 'mwb-gf-integration-with-salesforce' ); ?>">
		</div>
		<div class="mwb-sf_gf__logo-contact">
			<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/gravity-form.png' ); ?>" alt="<?php esc_html_e( 'GF', 'mwb-gf-integration-with-salesforce' ); ?>">
		</div>
	</div>

