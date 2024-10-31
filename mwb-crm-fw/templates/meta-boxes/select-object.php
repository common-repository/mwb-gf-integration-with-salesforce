<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the select object section of feeds.
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

$objects = isset( $params['objects'] ) ? $params['objects'] : array();

if ( ! is_array( $objects ) ) {
	echo esc_html( $objects );
	return;
}

?>
<div class="mwb-feeds__content  mwb-content-wrap  mwb-feed__select-object">
	<a class="mwb-feeds__header-link active">
		<?php esc_html_e( 'Select Salesforce Object', 'mwb-gf-integration-with-salesforce' ); ?>
	</a>

	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper m-b-15">
				<select name="crm_object" id="mwb-feeds-<?php echo esc_attr( $this->crm_slug ); ?>-object" class="mwb-form__dropdown">
					<option value="-1"><?php esc_html_e( 'Select Object', 'mwb-gf-integration-with-salesforce' ); ?></option>
					<?php if ( is_array( $objects ) ) : ?>
						<?php foreach ( $objects as $key => $object ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $params['selected_object'], $object ); ?> >
								<?php echo esc_html( $object ); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="mwb-form-wrapper">
				<a id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-refresh-object" class="button refresh-object">
					<span class="mwb-sf_gf-refresh-object ">
						<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/refresh.svg' ); ?>">
					</span>
					<?php esc_html_e( 'Refresh Objects', 'mwb-gf-integration-with-salesforce' ); ?>
				</a>
				<a id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-refresh-fields" class="button refresh-fields">
					<span class="mwb-sf_gf-refresh-fields ">
						<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/refresh.svg' ); ?>">
					</span>
					<?php esc_html_e( 'Refresh Fields', 'mwb-gf-integration-with-salesforce' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
