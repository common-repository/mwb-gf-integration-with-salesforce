<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the select form section of feeds.
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

$forms = isset( $params['forms'] ) ? $params['forms'] : array();
?>
<div class="mwb-feeds__content  mwb-content-wrap">
	<a class="mwb-feeds__header-link active">
		<?php esc_html_e( 'Select GF Form', 'mwb-gf-integration-with-salesforce' ); ?>
	</a>
	<input type="hidden" name="mwb_gf_dependent_feed" id="mwb_gf_dependent_feed" value="<?php echo esc_attr( $params['dependent_on'] ); ?>">
	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper">
				<select name="crm_form" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-gf-select-form" class="mwb-form__dropdown">
					<option value="-1"><?php esc_html_e( 'Select Form', 'mwb-gf-integration-with-salesforce' ); ?></option>
					<optgroup label="<?php esc_html_e( 'Gravity Form', 'mwb-gf-integration-with-salesforce' ); ?>" ></optgroup>
					<?php if ( ! empty( $forms ) && is_array( $forms ) ) : ?>
						<?php foreach ( $forms as $form ) : ?>
							<option value="<?php echo esc_html( $form['id'] ); ?>" <?php selected( $params['selected_form'], $form['id'] ); ?>><?php echo esc_html( $form['title'] ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
		</div>
	</div>
</div>
