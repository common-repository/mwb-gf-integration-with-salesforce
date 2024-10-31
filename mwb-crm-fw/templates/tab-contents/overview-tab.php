<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the premium page.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_GF_Integration_With_Salesforce
 * @subpackage Mwb_GF_Integration_With_Salesforce/admin/partials/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<!-- Overview content start -->

<div class="mwb-sf-overview">
	<div class="mwb-sf-overview__wrapper">
		<div class="mwb-sf-overview__container">
			<div class="mwb-sf-overview__icons-wrap">
				<a href="<?php echo esc_url( 'https://makewebbetter.com/contact-us/?utm_source=MWB-gfsalesforce-backend&utm_medium=gfsalesforce-backend&utm_campaign=MWB-gfsalesforce-integration' ); ?>">
					<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/connect.svg' ); ?>" alt="contact-us-img">
				</a>
				<a href="<?php echo esc_url( 'https://docs.makewebbetter.com/mwb-gf-integration-with-salesforce/?utm_source=MWB-gfsalesforce-backend&utm_medium=gfsalesforce-backend&utm_campaign=MWB-gfsalesforce-integration' ); ?>">
					<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/doc.svg' ); ?>" alt="doc-img">
				</a>
			</div>
			<div class="mwb-sf-overview__banner-img">
				<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/banner.png' ); ?>" alt="sf-banner-img">
			</div>
			<div class="mwb-sf-overview__content">
				<h1><?php esc_html_e( 'What is  MWB GF Integration with Salesforce ?', 'mwb-gf-integration-with-salesforce' ); ?></h1>
				<p><?php esc_html_e( 'MWB GF Integration with Salesforce allows you to seamlessly integrate your Salesforce CRM dashboard with Gravity Forms Using the plugin, you can easily send your Gravity Forms data to the different fields in Salesforce CRM.', 'mwb-gf-integration-with-salesforce' ); ?></p>
			</div>
			<div class="mwb-sf-overview__features">
				<h2><?php esc_html_e( 'What does MWB GF Integration with Salesforce CRM do?', 'mwb-gf-integration-with-salesforce' ); ?></h2>
				<ul class="mwb-sf-overview__features-list">
					<li><?php esc_html_e( 'Seamlessly integrates your Salesforce CRM.', 'mwb-gf-integration-with-salesforce' ); ?></li>
					<li><?php esc_html_e( 'Integration of your gravity forms data with the Salesforce object fields.', 'mwb-gf-integration-with-salesforce' ); ?></li>
					<li><?php esc_html_e( 'Allows you to delete the logs after a defined number of days.', 'mwb-gf-integration-with-salesforce' ); ?></li>
					<li><?php esc_html_e( 'Provides for deleting all the plugin data when the plugin is uninstalled.', 'mwb-gf-integration-with-salesforce' ); ?></li>
					<li><?php esc_html_e( 'Notifies errors through e-mail notifications.', 'mwb-gf-integration-with-salesforce' ); ?></li>
				</ul>
			</div>
			<div class="mwb-sf-overview__keywords-wrap">
				<h2><?php esc_html_e( 'Salient Features of MWB GF Integration with Salesforce Plugin.', 'mwb-gf-integration-with-salesforce' ); ?></h2>
				<div class="mwb-sf-overview__keywords">
					<div class="mwb-sf-overview__keywords-item">
						<div class="mwb-sf-overview__keywords-card">
							<div class="mwb-sf-overview__keywords-text">
								<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/Smooth-Setup.png' ); ?>" alt="smooth-integration" width="180px">
								<h4 class="mwb-sf-overview__keywords-heading"><?php esc_html_e( 'Smooth Setup and Integration', 'mwb-gf-integration-with-salesforce' ); ?></h4>
								<p class="mwb-sf-overview__keywords-description">
									<?php esc_html_e( 'The MWB Gravity Forms Integration with Salesforce allows for an easy setup using two different methods- Rest API or Web-to-Lead/ Web-to-Case.', 'mwb-gf-integration-with-salesforce' ); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="mwb-sf-overview__keywords-item">
						<div class="mwb-sf-overview__keywords-card">
							<div class="mwb-sf-overview__keywords-text">
								<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/Dashboard.png' ); ?>" alt="Comprehensive dashboard" width="180px">
								<h4 class="mwb-sf-overview__keywords-heading"><?php esc_html_e( 'Comprehensive Dashboard', 'mwb-gf-integration-with-salesforce' ); ?></h4>
								<p class="mwb-sf-overview__keywords-description">
									<?php esc_html_e( 'The integration dashboard provides you with all the necessary options. You can click on the ‘Refresh token’ button to refresh the access token effortlessly. The dashboard also displays the number of gravity forms synced.', 'mwb-gf-integration-with-salesforce' ); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="mwb-sf-overview__keywords-item">
						<div class="mwb-sf-overview__keywords-card">
							<div class="mwb-sf-overview__keywords-text">
								<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/conditional-filters.jpg' ); ?>" alt="Filter-form" width="180px">
								<h4 class="mwb-sf-overview__keywords-heading"><?php esc_html_e( 'Fields Synchronization Using Conditional Filters', 'mwb-gf-integration-with-salesforce' ); ?></h4>
								<p class="mwb-sf-overview__keywords-description">
									<?php esc_html_e( 'The admin can filter the gravity form submissions and synchronization with Salesforce objects. The sync is based on conditional filtering using  AND/ OR logic predefined by the admin in the backend.', 'mwb-gf-integration-with-salesforce' ); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="mwb-sf-overview__keywords-item">
						<div class="mwb-sf-overview__keywords-card">
							<div class="mwb-sf-overview__keywords-text">
								<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/Easy-Mapping.jpg' ); ?>" alt="Mapping" width="180px">
								<h4 class="mwb-sf-overview__keywords-heading"><?php esc_html_e( 'Easy Mapping of Fields with Salesforce Object Fields', 'mwb-gf-integration-with-salesforce' ); ?></h4>
								<p class="mwb-sf-overview__keywords-description">
									<?php esc_html_e( 'Field mapping from gravity forms to the CRM fields is very convenient for any Salesforce object. All you need to do is pre-define the feeds.', 'mwb-gf-integration-with-salesforce' ); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="mwb-sf-overview__keywords-item">
						<div class="mwb-sf-overview__keywords-card">
							<div class="mwb-sf-overview__keywords-text">
								<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/Primary-Key.jpg' ); ?>" alt="Primary-key" width="180px">
								<h4 class="mwb-sf-overview__keywords-heading"><?php esc_html_e( 'Primary Key As The Unique Identifier', 'mwb-gf-integration-with-salesforce' ); ?></h4>
								<p class="mwb-sf-overview__keywords-description">
									<?php esc_html_e( 'A primary key is used to minimize the data redundancy issue in the Salesforce CRM data. The primary key helps in identifying a previously created entry for an update instead of creating a new entry.', 'mwb-gf-integration-with-salesforce' ); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="mwb-sf-overview__keywords-item">
						<div class="mwb-sf-overview__keywords-card">
							<div class="mwb-sf-overview__keywords-text">
								<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/E-mail.jpg' ); ?>" alt="Email-notification" width="180px">
								<h4 class="mwb-sf-overview__keywords-heading"><?php esc_html_e( 'E-mail Notifications', 'mwb-gf-integration-with-salesforce' ); ?></h4>
								<p class="mwb-sf-overview__keywords-description">
									<?php esc_html_e( 'If you enable the notifications, you will be able to spot the errors instantly through the e-mail notifications. These notifications are sent only in case of errors during the synchronization process.', 'mwb-gf-integration-with-salesforce' ); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="mwb-sf-overview__keywords-item">
						<div class="mwb-sf-overview__keywords-card">
							<div class="mwb-sf-overview__keywords-text">
								<img src="<?php echo esc_url( MWB_GF_INTEGRATION_WITH_SALESFORCE_URL . 'admin/images/Log.jpg' ); ?>" alt="Detailed-log" width="180px">
								<h4 class="mwb-sf-overview__keywords-heading"><?php esc_html_e( 'Log History of Synced Gravity Forms', 'mwb-gf-integration-with-salesforce' ); ?></h4>
								<p class="mwb-sf-overview__keywords-description">
									<?php esc_html_e( 'The plugin will also provide you with complete log history. The log history will be deleted after the specified number of days. The log history will ease the process of debugging if needed.', 'mwb-gf-integration-with-salesforce' ); ?>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Overview content end-->
