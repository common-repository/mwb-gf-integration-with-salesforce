<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the add new field section of feeds.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/mwb-crm-fw/framework/templates/meta-boxes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}
$mapping_exists = ! empty( $params['mapping_data'] );
?>
<div id="mwb-add-new-field-section-wrapper"  class="mwb-feeds__content  mwb-content-wrap row-hide">
	<a class="mwb-feeds__header-link">
		<?php esc_html_e( 'Add New Field', 'mwb-gf-integration-with-salesforce' ); ?>
	</a>
	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper">
				<select id="add-new-field-select">
					<option value="-1"><?php esc_html_e( 'Select an Option', 'mwb-gf-integration-with-salesforce' ); ?></option>
					<?php foreach ( $params['crm_fields'] as $key => $fields_data ) : ?>
						<?php
						$disabled = '';
						if ( $mapping_exists ) {
							if ( array_key_exists( $fields_data['name'], $params['mapping_data'] ) ) {
								$disabled = 'disabled';
							}
						}
						if ( true == $fields_data['mandatory'] ) { // phpcs:ignore
							$disabled = 'disabled';
						}
						?>
						<option <?php echo esc_attr( $disabled ); ?>  value="<?php echo esc_attr( $fields_data['name'] ); ?>"><?php echo esc_html( $fields_data['label'] ); ?></option>	
					<?php endforeach; ?>
				</select>
				<a id="add-new-field-btn" class="mwb-btn mwb-btn--filled"><?php esc_html_e( 'Add Field', 'mwb-gf-integration-with-salesforce' ); ?></a>
			</div>
		</div>
	</div>
</div>

