<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the nonce of feeds section.
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

?>
<input type="hidden" name="meta_box_nonce" value="<?php echo esc_attr( wp_create_nonce( 'meta_box_nonce' ) ); ?>" >
