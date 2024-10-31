<?php
/**
 * The complete management for the Salesforce-GF plugin through out the site.
 *
 * @since      1.0.0
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/includes
 * @author     MakeWebBetter <https://makewebbetter.com>
 */

/**
 * The complete management for the ajax handlers.
 *
 * @since      1.0.0
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/includes
 * @author     MakeWebBetter <https://makewebbetter.com>
 */
class Mwb_Gf_Integration_With_Salesforce_Ajax_Handler {

	/**
	 * Current crm slug.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $crm_slug    The current crm slug.
	 */
	public $crm_slug;

	/**
	 * Current crm name.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @var       string   $crm_name    The current crm name.
	 */
	public $crm_name;

	/**
	 * Instance of the Mwb_Gf_Integration_Salesforce_Api_Base class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object   $crm_api_module   Instance of Mwb_Gf_Integration_Salesforce_Api_Base class.
	 */
	public $crm_api_module;

	/**
	 * Current CRM API class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $crm_class   Name of the current CRM API class.
	 */
	public $crm_class;

	/**
	 * Connect manager class name
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $connect   Name of the Connect manager class.
	 */
	private $connect;

	/**
	 * Instance of Connect manager class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $connect_manager  Instance of the Connect manager class.
	 */
	private $connect_manager;

	/**
	 * Instance of the plugin main class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object   $core_class    Name of the plugin core class.
	 */
	public $core_class = 'Mwb_Gf_Integration_With_Salesforce';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// Initialise CRM name and slug.
		$this->crm_slug = $this->core_class::mwb_get_current_crm_property( 'slug' );
		$this->crm_name = $this->core_class::mwb_get_current_crm_property( 'name' );

		// Initialise CRM API class.
		$this->crm_class      = 'Mwb_Gf_Integration_' . $this->crm_name . '_Api_Base';
		$this->crm_api_module = $this->crm_class::get_instance();

		// Initialise Connect manager class.
		$this->connect         = 'Mwb_Gf_Integration_Connect_' . $this->crm_name . '_Framework';
		$this->connect_manager = $this->connect::get_instance();

	}

	/**
	 * Get default response.
	 *
	 * @since     1.0.0
	 * @return    array
	 */
	public function mwb_sf_gf_get_default_response() {
		return array(
			'status'  => false,
			'message' => esc_html__( 'Something went wrong!!', 'mwb-gf-integration-with-salesforce' ),
		);
	}

	/**
	 * Ajax handler :: Handles all ajax callbacks.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_sf_gf_ajax_callback() {

		/* Nonce verification */
		check_ajax_referer( 'mwb_' . $this->crm_slug . '_gf_nonce', 'nonce' );

		$event    = ! empty( $_POST['event'] ) ? sanitize_text_field( wp_unslash( $_POST['event'] ) ) : '';
		$response = $this->mwb_sf_gf_get_default_response();

		if ( ! empty( $event ) ) {
			$data = $this->$event( $_POST );
			if ( $data ) { // phpcs:ignore
				$response['status']  = true;
				$response['message'] = esc_html__( 'Success', 'mwb-gf-integration-with-salesforce' );

				$response = $this->maybe_add_data( $response, $data );
			}
		}

		wp_send_json( $response );

	}


	/**
	 * Merge additional data to response.
	 *
	 * @param     array $response   An array of response.
	 * @param     array $data       An array of data to merge in response.
	 * @since     1.0.0
	 * @return    array
	 */
	public function maybe_add_data( $response, $data ) {

		if ( is_array( $data ) ) {
			$response['data'] = $data;
		}

		return $response;
	}


	/**
	 * Check current if account has api enabled.
	 *
	 * @return array Response data.
	 */
	public function validate_api_detail() {

		$response = array(
			'success' => false,
			'code'    => 400,
			'message' => __( 'Something went wrong!! Please try again', 'mwb-gf-integration-with-salesforce' ),
		);

		$info   = false;
		$method = get_option( 'mwb-' . $this->crm_slug . '-gf-integration-method' );
		switch ( $method ) {

			case 'api':
				$response = $this->crm_api_module->validate_crm_connection();
				$info     = array(
					'success'      => true,
					'msg'          => esc_html__( 'Validation successful !! Redirecting...', 'mwb-gf-integration-with-salesforce' ),
					'redirect_url' => admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_gf_page' ),
				);

				if ( isset( $response['code'] ) && 403 == $response['code'] ) { // phpcs:ignore
					if ( isset( $response['data'] ) ) {
						foreach ( $response['data'] as $key => $data ) {
							$info['success'] = false;
							$info['msg']     = $data['message'];
							$info['class']   = 'error';
							$info['error']   = $data['errorCode'];
						}
						return $info;
					}
				}
				break;

			case 'web':
				$response = $this->crm_api_module->post_via_web( true, 'Lead' );
				if ( isset( $response['code'] ) && 200 == $response['code'] ) { // phpcs:ignore
					$info = array(
						'success'      => true,
						'msg'          => esc_html__( 'Validation successful !! Redirecting...', 'mwb-gf-integration-with-salesforce' ),
						'redirect_url' => admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_gf_page' ),
					);
				} else {
					$info = array(
						'success' => false,
						'msg'     => esc_html__( 'Your Org ID or Salesforce Domain is not properly configured, please try again!', 'mwb-gf-integration-with-salesforce' ),
						'class'   => 'error',
					);
					return $info;
				}
				break;
		}
		update_option( 'mwb-' . $this->crm_slug . '-gf-active', true );
		return $info;
	}

	/**
	 * Save plugin general settings
	 *
	 * @return array Response array.
	 */
	public function mark_onboarding_complete() {
		update_option( 'mwb-gf-' . $this->crm_slug . '-authorised', '1' );
		return array( 'success' => true );
	}

	/**
	 * Referesh access tokens.
	 *
	 * @since     1.0.0
	 * @return    array
	 */
	public function refresh_salesforce_access_token() {

		$response        = array( 'success' => false );
		$response['msg'] = esc_html__( 'Something went wrong! Check your credentials and authorize again', 'mwb-gf-integration-with-salesforce' );
		$renew_result    = $this->crm_api_module->renew_access_token();

		if ( ! empty( $renew_result ) && true == $renew_result ) { // phpcs:ignore
			$issue_time    = $this->crm_api_module->get_access_token_issue_time();
			$access_token  = $this->crm_api_module->get_access_token();
			$token_message = sprintf( '%s : %s', esc_html__( 'Last token issued at ', 'mwb-gf-integration-with-salesforce' ), esc_html( gmdate( 'd M y h:i A', $issue_time ) ) );
			$response      = array(
				'success'       => true,
				'msg'           => __( 'Success', 'mwb-gf-integration-with-salesforce' ),
				'token_message' => $token_message,
				'access_token'  => $access_token,
			);
		}
		return $response;
	}

	/**
	 * Revoke account access.
	 *
	 * @since     1.0.0
	 * @return    bool
	 */
	public function revoke_salesforce_gf_access() {

		$connection_method = get_option( 'mwb-' . $this->crm_slug . '-gf-integration-method', false );

		if ( false == $connection_method ) { // phpcs:ignore
			$connection_method = 'api';
		}

		if ( false !== $connection_method ) {
			switch ( $connection_method ) {
				case 'api':
					delete_option( 'mwb-' . $this->crm_slug . '-gf-active' );
					delete_option( 'mwb-' . $this->crm_slug . '-gf-token-data' );
					delete_option( 'mwb-gf-' . $this->crm_slug . '-authorised' );
					delete_option( 'mwb-' . $this->crm_slug . '-gf-crm-connected' );
					break;

				case 'web':
					delete_option( 'mwb-' . $this->crm_slug . '-gf-active' );
					delete_option( 'mwb-gf-' . $this->crm_slug . '-authorised' );
					delete_option( 'mwb-' . $this->crm_slug . '-gf-crm-connected' );
					break;
			}
		}

		return true;
	}

	/**
	 * Get fields for a particular salesforce object.
	 *
	 * @param   array $posted_data    Ajax request data.
	 * @return  array                 Array for fields.
	 * @since   1.0.0
	 */
	public function get_object_fields_for_mapping( $posted_data = array() ) {

		$response    = array( 'success' => false );
		$fields_data = array();
		$form_id     = ! empty( $posted_data['selected_form'] ) ? sanitize_text_field( wp_unslash( $posted_data['selected_form'] ) ) : '';
		$object      = ! empty( $posted_data['selected_object'] ) ? sanitize_text_field( wp_unslash( $posted_data['selected_object'] ) ) : '';
		$force       = ! empty( $posted_data['force'] ) ? sanitize_text_field( wp_unslash( $posted_data['force'] ) ) : false;
		$feed_id     = ! empty( $posted_data['post_id'] ) ? sanitize_text_field( wp_unslash( $posted_data['post_id'] ) ) : false;
		$fields_data = $this->crm_api_module->get_object_fields( $object, $force );

		$fields_data = $this->maybe_restrict_fields( $fields_data );

		$feed_data['crm_fields']      = $fields_data;
		$feed_data['selected_object'] = $object;
		$feed_data['selected_form']   = $form_id;

		$options = $this->get_field_mapping_options( $form_id );

		$feed_data['field_options'] = $options;

		return array(
			'html'   => $this->retrieved_html( $feed_id, $feed_data ),
			'fields' => $fields_data,
		);

	}

	/**
	 * Restrict fields from mapping.
	 *
	 * @param   array $fields  An array of fields data.
	 * @since   1.0.0
	 * @return  array
	 */
	public function maybe_restrict_fields( $fields = array() ) {
		if ( empty( $fields ) || ! is_array( $fields ) ) {
			return;
		}
		$method = $this->connect_manager->get_connection_method();

		switch ( $method ) {
			case 'api':
				$phone_fields = array(
					'Phone',
					'Fax',
					'MobilePhone',
					'HomePhone',
					'OtherPhone',
					'AssistantPhone',
				);
				break;

			case 'web':
				$phone_fields = array(
					'phone',
					'mobile',
					'fax',
				);
				break;
		}

		$admin  = 'Mwb_Gf_Integration_With_' . $this->crm_name . '_Admin';
		$result = $fields;

		if ( $admin::is_pro_available_and_active() ) {
			foreach ( $fields as $key => $field ) {
				if ( array_key_exists( $field['name'], array_flip( $phone_fields ) ) ) {
					unset( $fields[ $key ] );
				}
			}
			$result = array_values( $fields );
		}

		return $result;

	}

	/**
	 * Get all mapping options for a salesforce field.
	 *
	 * @param    int $form_id    Gravity Form ID.
	 * @return   array           Array for field option.
	 * @since    1.0.0
	 */
	public function get_field_mapping_options( $form_id ) {
		$framework_class    = 'Mwb_Gf_Integration_Connect_' . $this->crm_name . '_Framework';
		$framework_instance = $framework_class::get_instance();
		$options            = $framework_instance->getMappingDataset( $form_id );
		return $options;
	}

	/**
	 * Ajax Callback :: Get module HTML.
	 *
	 * @param     int   $feed_id       Feed id.
	 * @param     array $posted_data   Posted data.
	 * @return    string               Response html.
	 * @since     1.0.0
	 */
	public function retrieved_html( $feed_id, $posted_data ) {

		$feed_class      = 'Mwb_Gf_Integration_' . $this->crm_name . '_Feed_Module';
		$feed_module     = $feed_class::get_instance();
		$selected_object = $posted_data['selected_object'];
		$primary_field   = $feed_module->fetch_feed_data( $feed_id, 'mwb-' . $this->crm_slug . '-gf-primary-field', '', $selected_object );
		$mapping_data    = $feed_module->fetch_feed_data( $feed_id, 'mwb-' . $this->crm_slug . '-gf-mapping-data', '', $selected_object );
		$method          = $this->connect_manager->get_connection_method();

		$params = array(
			'selected_object' => $selected_object,
			'crm_fields'      => $posted_data['crm_fields'],
			'field_options'   => $posted_data['field_options'],
			'mapping_data'    => $mapping_data,
		);

		$templates = array(
			'select-fields',
			'add-new-field',
			'nonce-field',
		);

		// If connected via API add primary field and template.
		if ( 'api' == $method ) { // phpcs:ignore
			$params['primary_field'] = $primary_field;
			$templates[]             = 'primary-field';
		}

		$html = '';
		foreach ( $templates as $k => $v ) {
			$html .= $feed_module->do_ajax_render( $v, $params );
		}
		return $html;
	}

	/**
	 * Get CRM Objects.
	 *
	 * @param    array $posted_data    Array of ajax posted data.
	 * @since    1.0.0
	 * @return   array $module_data    data of specific module.
	 */
	public function get_crm_objects( $posted_data = array() ) {

		$objects  = array();
		$force    = ! empty( $posted_data['force'] ) ? sanitize_text_field( wp_unslash( $posted_data['force'] ) ) : false;
		$response = array(
			'success' => false,
			'data'    => esc_html__( 'Somthing went wrong, Refresh and try again.', 'mwb-gf-integration-with-salesforce' ),
		);

		$objects = $this->crm_api_module->get_crm_objects( $force );
		if ( ! empty( $objects ) ) {
			$response = array(
				'success' => true,
				'data'    => $objects,
			);
		}
		return $response;
	}

	/**
	 * Add new field in feed form.
	 *
	 * @param    array $posted_data   Posted data.
	 * @since    1.0.0
	 * @return   array                Response data.
	 */
	public function add_new_field( $posted_data ) {

		$response = array(
			'success' => false,
			'msg'     => esc_html__( 'Somthing went wrong, Refresh and try again.', 'mwb-gf-integration-with-salesforce' ),
		);

		$object      = ! empty( $posted_data['object'] ) ? sanitize_text_field( wp_unslash( $posted_data['object'] ) ) : '';
		$field       = ! empty( $posted_data['field'] ) ? sanitize_text_field( wp_unslash( $posted_data['field'] ) ) : '';
		$form_id     = ! empty( $posted_data['form'] ) ? sanitize_text_field( wp_unslash( $posted_data['form'] ) ) : '';
		$fields_data = $this->crm_api_module->get_object_fields( $object, false );
		$field_index = array_search( $field, array_column( $fields_data, 'name' ) ); // phpcs:ignore

		if ( false === $field_index ) {
			return $response;
		}

		$field_options = $this->get_field_mapping_options( $form_id );

		ob_start();
		Mwb_Gf_Integration_Salesforce_Template_Manager::get_field_section_html( $field_options, $fields_data[ $field_index ], array() );
		$output = ob_get_contents();
		ob_end_clean();
		$response = array(
			'success' => true,
			'html'    => $output,
		);
		return $response;
	}

	/**
	 * Create filter field in feed form.
	 *
	 * @param    array $posted_data   Posted data.
	 * @since    1.0.0
	 * @return   array                Response data.
	 */
	public function create_feed_filters( $posted_data ) {

		$response = array(
			'success' => false,
			'msg'     => esc_html__( 'Somthing went wrong, Refresh and try again.', 'mwb-gf-integration-with-salesforce' ),
		);

		$feed_id = ! empty( $posted_data['post_id'] ) ? sanitize_text_field( wp_unslash( $posted_data['post_id'] ) ) : false;
		$form_id = ! empty( $posted_data['selected_form'] ) ? sanitize_text_field( wp_unslash( $posted_data['selected_form'] ) ) : '';

		$form_fields   = $this->get_field_mapping_options( $form_id );
		$filter_fields = $this->get_field_filter_options();

		return array(
			'form'    => $form_fields,
			'filter'  => $filter_fields,
			'success' => true,
		);
	}

	/**
	 * Get all mapping options for a filter field.
	 *
	 * @return   array           Array for field option.
	 * @since    1.0.0
	 */
	public function get_field_filter_options() {
		$framework_class    = 'Mwb_Gf_Integration_Connect_' . $this->crm_name . '_Framework';
		$framework_instance = $framework_class::get_instance();
		$options            = $framework_instance->getFilterMappingDataset();
		return $options;
	}

	/**
	 * Toggle feed status.
	 *
	 * @param     array $data    An array of ajax posted data.
	 * @since     1.0.0
	 * @return    bool
	 */
	public function toggle_feed_status( $data = array() ) {

		$feed_id  = ! empty( $data['feed_id'] ) ? sanitize_text_field( wp_unslash( $data['feed_id'] ) ) : '';
		$status   = ! empty( $data['status'] ) ? sanitize_text_field( wp_unslash( $data['status'] ) ) : '';
		$response = $this->connect_manager->change_post_status( $feed_id, $status );
		return $response;
	}

	/**
	 * Trash feeds.
	 *
	 * @param     array $data    An array of ajax posted data.
	 * @since     1.0.0
	 * @return    bool
	 */
	public function trash_feeds_from_list( $data = array() ) {

		$feed_id = ! empty( $data['feed_id'] ) ? sanitize_text_field( wp_unslash( $data['feed_id'] ) ) : '';
		$trash   = wp_trash_post( $feed_id );

		if ( $trash ) {
			return true;
		}
		return false;
	}

	/**
	 * Clear sync log.
	 *
	 * @since      1.0.0
	 * @return     array          Response array.
	 */
	public function clear_sync_log() {
		$this->connect_manager->delete_sync_log();
		return array( 'success' => true );
	}

	/**
	 * Download logs.
	 *
	 * @param      array $data   An arraay of ajax posted data.
	 * @since      1.0.0
	 * @return     array         Response array.
	 */
	public function download_sync_log( $data = array() ) {

		global $wpdb;
		$response = array(
			'success' => false,
			'msg'     => esc_html__( 'Somthing went wrong, Refresh and try again.', 'mwb-gf-integration-with-salesforce' ),
		);

		$table_name     = $wpdb->prefix . 'mwb_' . $this->crm_slug . '_gf_log';
		$log_data_query = "SELECT * FROM {$table_name} ORDER BY `id` DESC"; // phpcs:ignore
		$log_data       = $wpdb->get_results( $log_data_query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$path           = $this->connect_manager->create_log_folder( 'mwb-' . $this->crm_slug . '-gf-logs' );
		$log_dir        = $path . '/mwb-' . $this->crm_slug . '-gf-sync-log.log';

		if ( file_exists( $log_dir ) ) {
			unlink( $log_dir );
		}

		if ( ! empty( $log_data ) && is_array( $log_data ) ) {
			foreach ( $log_data as $key => $value ) {

				$value['sf_id'] = ! empty( $value['sf_id'] ) ? $value['sf_id'] : '-';

				$log  = 'FEED ID: ' . $value['feed_id'] . PHP_EOL;
				$log .= 'FEED : ' . $value['feed'] . PHP_EOL;
				$log .= 'SALESFORCE ID : ' . $value['sf_id'] . PHP_EOL;
				$log .= 'SALESFORCE OBJECT : ' . $value['sf_object'] . PHP_EOL;
				$log .= 'TIME : ' . gmdate( 'd-m-Y h:i A', esc_html( $value['time'] ) ) . PHP_EOL;
				$log .= 'REQUEST : ' . wp_json_encode( maybe_unserialize( $value['request'] ) ) . PHP_EOL;
				$log .= 'RESPONSE : ' . wp_json_encode( maybe_unserialize( $value['response'] ) ) . PHP_EOL;
				$log .= '------------------------------------' . PHP_EOL;
				file_put_contents( $log_dir, $log, FILE_APPEND ); // phpcs:ignore
			}

			$response = array(
				'success'  => true,
				'redirect' => admin_url( '?mwb_download=1' ),
			);
		} else {
			$response = array(
				'success' => false,
				'msg'     => esc_html__( 'No log data available', 'mwb-gf-integration-with-salesforce' ),
			);
		}

		return $response;
	}

	/**
	 * Enable datatable.
	 *
	 * @param     mixed $data    An array of ajax posted data.
	 * @since     1.0.0
	 * @return    void
	 */
	public function get_datatable_data_cb( $data = array() ) {

		$request = $_GET; // phpcs:ignore
		$offset  = $request['start'];
		$limit   = $request['length'];

		global $wpdb;
		$table_name     = $wpdb->prefix . 'mwb_' . $this->crm_slug . '_gf_log';
		$log_data_query = $wpdb->prepare( "SELECT * FROM {$table_name} ORDER BY `id` DESC LIMIT %d OFFSET %d ", $limit, $offset ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$log_data       = $wpdb->get_results( $log_data_query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$count_query    = "SELECT COUNT(*) as `total_count` FROM {$table_name}"; // phpcs:ignore
		$count_data     = $wpdb->get_col( $count_query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$total_count    = $count_data[0];
		$data           = array();

		foreach ( $log_data as $key => $value ) {

			$data_href = $this->connect_manager->get_salesforce_link( $value['sf_id'] );

			if ( ! empty( $data_href ) && '-' != $data_href ) { // phpcs:ignore
				$link = '<a href="' . $data_href . '" target="_blank">' . $value['sf_id'] . '</a>';
			} else {
				$link = $value['sf_id'];
			}

			$value['sf_id'] = ! empty( $value['sf_id'] ) ? $value['sf_id'] : '-';
			$temp           = array(
				'<span class="dashicons dashicons-plus-alt"></span>',
				$value['feed'],
				$value['feed_id'],
				$value['sf_object'],
				$link,
				$value['event'],
				gmdate( 'd-m-Y h:i A', esc_html( $value['time'] ) ),
				wp_json_encode( maybe_unserialize( $value['request'] ) ),
				wp_json_encode( maybe_unserialize( $value['response'] ) ),
			);
			$data[]         = $temp;
		}

		$json_data = array(
			'draw'            => intval( $request['draw'] ),
			'recordsTotal'    => $total_count,
			'recordsFiltered' => $total_count,
			'data'            => $data,
		);

		wp_send_json( $json_data );
	}

	/**
	 * Filter feeds by form
	 *
	 * @param      array $data     An array of ajax posted data.
	 * @since      1.0.0
	 * @return     mixed
	 */
	public function filter_feeds_by_form( $data ) {

		$form_id = isset( $data['form_id'] ) ? sanitize_text_field( wp_unslash( $data['form_id'] ) ) : '';
		$result  = array(
			'status' => false,
			'msg'    => esc_html__( 'Invalid form', 'mwb-gf-integration-with-salesforce' ),
		);

		if ( ! empty( $form_id ) ) {

			if ( 'all' == $form_id ) { // phpcs:ignore
				$feeds = $this->connect_manager->get_available_salesforce_feeds();
			} else {
				$feeds = $this->connect_manager->get_feeds_by_form( $form_id );
			}

			$output = '';

			foreach ( $feeds as $feed ) {
				ob_start();
				$template = new Mwb_Gf_Integration_Salesforce_Template_Manager();
				$template->get_filter_section_html( $feed );
				$output .= ob_get_contents();
				ob_end_clean();
			}

			$result = array(
				'status' => true,
				'feeds'  => $output,
			);
		}

		return $result;
	}

}
