<?php
/**
 * Base Api Class
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/mwb-crm-fw
 */

/**
 * Base Api Class.
 *
 * This class defines all code necessary api communication.
 *
 * @since      1.0.0
 * @package    Mwb_Gf_Integration_With_Salesforce
 * @subpackage Mwb_Gf_Integration_With_Salesforce/mwb-crm-fw
 */
class Mwb_Gf_Integration_Salesforce_Api_Base extends Mwb_Gf_Integration_Api_Base {

	/**
	 * Crm prefix
	 *
	 * @var    string   Crm prefix
	 * @since  1.0.0
	 */
	public static $crm_prefix;

	/**
	 * Production Base auth url.
	 *
	 * @var     string  Production Base auth url
	 * @since   1.0.0
	 */
	public static $base_auth_url = 'https://login.salesforce.com/services/oauth2/';

	/**
	 * Sandbox base auth url.
	 *
	 * @var     string Sandbox base auth url.
	 * @since   1.0.0
	 */
	public static $sandbox_base_auth_url = 'https://test.salesforce.com/services/oauth2/';

	/**
	 * Salesforce Consumer key
	 *
	 * @var     string  Consumer key
	 * @since   1.0.0
	 */
	public static $consumer_key;

	/**
	 * Salesforce Consumer Secret Key
	 *
	 * @var     string Consumer secret key
	 * @since   1.0.0
	 */
	public static $consumer_secret;

	/**
	 * Salesforce Environment
	 *
	 * @var      string  Environment type
	 * @since    1.0.0
	 */
	public static $enviornment;

	/**
	 * Salesforce Access token data.
	 *
	 * @var     string   Stores access token data.
	 * @since   1.0.0
	 */
	public static $access_token;

	/**
	 * Salesforce Refresh token data
	 *
	 * @var     string   Stores refresh token data.
	 * @since   1.0.0
	 */
	public static $refresh_token;

	/**
	 * Access token expiry data
	 *
	 * @var     integer   Stores access token expiry data.
	 * @since   1.0.0
	 */
	public static $expiry;

	/**
	 * Current instance URL
	 *
	 * @var     string    Current instance url.
	 * @since   1.0.0
	 */
	public static $instance_url;

	/**
	 * Issued at data
	 *
	 * @var     string     Issued at data by salesforce
	 * @since   1.0.0
	 */
	public static $issued_at;

	/**
	 * Creates an instance of the class
	 *
	 * @var     object     An instance of the class
	 * @since   1.0.0
	 */
	protected static $_instance = null; // phpcs:ignore

	/**
	 * Salesforce API version
	 *
	 * @var     string       API version
	 * @since   1.0.0
	 */
	public static $api_version = 'v51.0';

	/**
	 * Main Mwb_Gf_Integration_Salesforce_Api_Base Instance.
	 *
	 * Ensures only one instance of Mwb_Gf_Integration_Salesforce_Api_Base is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @return Mwb_Gf_Integration_Salesforce_Api_Base - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		self::initialize();
		return self::$_instance;
	}

	/**
	 * Initialize properties.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $token_data Saved token data.
	 */
	public static function initialize( $token_data = array() ) {

		self::$crm_prefix = Mwb_Gf_Integration_With_Salesforce::mwb_get_current_crm_property( 'slug' );

		$custom_app = get_option( 'mwb-' . self::$crm_prefix . '-gf-use-custom-app', 'no' );
		if ( 'yes' == $custom_app ) {  // phpcs:ignore
			self::$consumer_key    = get_option( 'mwb-' . self::$crm_prefix . '-gf-consumer-key', '' );
			self::$consumer_secret = get_option( 'mwb-' . self::$crm_prefix . '-gf-consumer-secret', '' );
		} else {
			self::$consumer_key    = '3MVG9fe4g9fhX0E7xz1Xm4Ewa5rcJI8I6Ga4rGD2SR2Fs4czhtbOsk.2yK652WN0r1F5ZBH65d_ZK41YHm4gT';
			self::$consumer_secret = 'E30C4E54EC11A4FA190C85CDD530EAEA9FEAB98063E55125A512AF4878714082';
		}

		self::$enviornment = get_option( 'mwb-' . self::$crm_prefix . '-gf-enviornment', '' );

		if ( empty( $token_data ) ) {
			$token_data = get_option( 'mwb-' . self::$crm_prefix . '-gf-token-data', array() );
		}

		if ( ! empty( $token_data ) ) {
			self::$access_token  = ! empty( $token_data['access_token'] ) ? $token_data['access_token'] : '';
			self::$refresh_token = ! empty( $token_data['refresh_token'] ) ? $token_data['refresh_token'] : '';
			self::$instance_url  = ! empty( $token_data['instance_url'] ) ? $token_data['instance_url'] : '';
			self::$issued_at     = ! empty( $token_data['issued_at'] ) ? $token_data['issued_at'] : '';
		}
	}

	/**
	 * Get api domain.
	 *
	 * @since    1.0.0
	 * @param    string $custom_app   Use CXustom App.
	 *
	 * @return   string   Site redirecrt Uri.
	 */
	public function get_redirect_uri( $custom_app ) {

		if ( 'yes' == $custom_app ) {  // phpcs:ignore
			return admin_url();
		}
		return 'https://auth.makewebbetter.com/integration/salesforce-auth/';
	}

	/**
	 * Get instance url.
	 *
	 * @since    1.0.0
	 * @return   string   Instance url.
	 */
	public function get_instance_url() {
		return ! empty( self::$instance_url ) ? self::$instance_url : false;
	}

	/**
	 * Get access token.
	 *
	 * @since    1.0.0
	 * @return   string   Access token.
	 */
	public function get_access_token() {
		return ! empty( self::$access_token ) ? self::$access_token : false;
	}

	/**
	 * Get refresh token.
	 *
	 * @since     1.0.0
	 * @return    string    Refresh token.
	 */
	public function get_refresh_token() {
		return ! empty( self::$refresh_token ) ? self::$refresh_token : false;
	}

	/**
	 * Get active access token issued time.
	 *
	 * @since     1.0.0
	 * @return    string    Expiry seconds.
	 */
	public function get_access_token_issue_time() {
		return ! empty( self::$issued_at ) ? ( self::$issued_at / 1000 ) : false;
	}


	/**
	 * Check if access token is valid.
	 *
	 * @since    1.0.0
	 * @return   boolean
	 */
	public function is_access_token_valid() {
		return ! empty( self::$expiry ) ? ( self::$expiry > time() ) : false;
	}

	/**
	 * Get refreshed token data from api.
	 *
	 * @since     1.0.0
	 * @return    boolean.
	 */
	public function renew_access_token() {

		$refresh_token = $this->get_refresh_token();

		if ( ! empty( $refresh_token ) ) {
			$response = $this->process_access_token( false, $refresh_token );
		}

		return ! empty( $response['code'] ) && 200 == $response['code'] ? true : false; // phpcs:ignore
	}

	/**
	 * Save New token data into db.
	 *
	 * @since   1.0.0
	 * @param   string $code    Unique code to generate token.
	 */
	public function save_access_token( $code ) {
		return $this->process_access_token( $code );
	}

	/**
	 * Save Company Id.
	 *
	 * @since   1.0.0
	 * @param   string $company_id   Connected Company Id.
	 */
	public function save_company_id( $company_id = false ) {
		if ( empty( $company_id ) ) {
			return;
		}
		update_option( 'mwb-' . self::$crm_prefix . '-gf-company-id', $company_id );
	}

	/**
	 * Get Base Authorization url.
	 *
	 * @since    1.0.0
	 * @return   string   Base Authorization url.
	 */
	public function base_auth_url() {
		$url = 'production' == self::$enviornment ? self::$base_auth_url : self::$sandbox_base_auth_url; // phpcs:ignore
		return $url;
	}

	/**
	 * Get Authorization url.
	 *
	 * @since    1.0.0
	 * @return   string Authorization url.
	 */
	public function get_auth_code_url() {

		$custom_app = get_option( 'mwb-' . self::$crm_prefix . '-gf-use-custom-app', 'no' );

		$query_args = array(
			'response_type' => 'code',
			'state'         => rawurlencode( $this->get_oauth_state( $custom_app ) ), // phpcs:ignore.
			'client_id'     => rawurlencode( self::$consumer_key ),
			'redirect_uri'  => rawurlencode( $this->get_redirect_uri( $custom_app ) ),
			'scope'         => urlencode( 'api refresh_token' ), // phpcs:ignore
		);

		$salesforce_login_url = add_query_arg( $query_args, $this->base_auth_url() . 'authorize' );

		return $salesforce_login_url;
	}

	/**
	 * Get oauth state with current instance redirect url.
	 *
	 * @since    1.0.0
	 * @param    string $custom_app Use Custom App.
	 *
	 * @return   string State.
	 */
	public function get_oauth_state( $custom_app = 'no' ) {

		$nonce = wp_create_nonce( 'mwb_' . self::$crm_prefix . '_gf_state' );

		if ( 'yes' == $custom_app ) {  // phpcs:ignore
			return $nonce;
		}

		$admin_redirect_url = admin_url();
		$args               = array(
			'mwb_nonce'  => $nonce,
			'mwb_source' => 'salesforce',
		);
		$admin_redirect_url = add_query_arg( $args, $admin_redirect_url );

		return $admin_redirect_url;
	}

	/**
	 * Get refresh token data from api.
	 *
	 * @since   1.0.0
	 * @param   string $code            Unique code to generate token.
	 * @param   string $refresh_token   Unique code to renew token.
	 * @return  array
	 */
	public function process_access_token( $code = '', $refresh_token = '' ) {

		$endpoint = '/token';

		$custom_app = get_option( 'mwb-' . self::$crm_prefix . '-gf-use-custom-app', 'no' );

		$this->base_url = $this->base_auth_url();

		$params = array(
			'grant_type'    => 'authorization_code',
			'client_id'     => self::$consumer_key,
			'client_secret' => self::$consumer_secret,
			'redirect_uri'  => $this->get_redirect_uri( $custom_app ),
			'code'          => $code,
		);

		if ( empty( $code ) ) {
			$params['refresh_token'] = $refresh_token;
			$params['grant_type']    = 'refresh_token';
			unset( $params['code'] );
		}

		$response = $this->post( $endpoint, $params, $this->get_token_auth_header() );

		if ( isset( $response['code'] ) && 200 == $response['code'] ) { // phpcs:ignore

			// Save token.
			$token_data = ! empty( $response['data'] ) ? $response['data'] : array();
			$token_data = $this->merge_refresh_token( $token_data );
			update_option( 'mwb-' . self::$crm_prefix . '-gf-token-data', $token_data );
			update_option( 'mwb-' . self::$crm_prefix . '-gf-crm-connected', true );
			self::initialize( $token_data );
		} else {
			// On failure add to log.
			delete_option( 'mwb-' . self::$crm_prefix . '-gf-token-data' );
			delete_option( 'mwb-' . self::$crm_prefix . '-gf-crm-connected' );
		}

		return $response;
	}


	/**
	 * Merge refresh token with new access token data.
	 *
	 * @since   1.0.0
	 * @param   array $new_token_data   Latest token data.
	 * @return  array                   Token data.
	 */
	public function merge_refresh_token( $new_token_data ) {

		$old_token_data = get_option( 'mwb-' . self::$crm_prefix . '-gf-token-data', array() );

		if ( empty( $old_token_data ) ) {
			return $new_token_data;
		}

		foreach ( $old_token_data as $key => $value ) {
			if ( isset( $new_token_data[ $key ] ) ) {
				$old_token_data[ $key ] = $new_token_data[ $key ];
			}
		}
		return $old_token_data;
	}

	/**
	 * Get authorization headers for getting token.
	 *
	 * @since   1.0.0
	 * @return  array   Headers.
	 */
	public function get_token_auth_header() {
		return array();
	}

	/**
	 * Get Request headers.
	 *
	 * @since    1.0.0
	 * @return   array   Headers.
	 */
	public function get_auth_header() {

		$headers = array(
			'Authorization' => 'Bearer ' . self::$access_token,
			'content-type'  => 'application/json',
		);

		return $headers;
	}

	/**
	 * Prepare bulk request data for create or update records.
	 *
	 * @since 1.0.1
	 *
	 * @param  string $object     Object Name.
	 * @param  array  $record_data All records data.
	 * @return array Bulk request data.
	 */
	public function prepare_bulk_request_data( $object, $record_data ) {

		$request_data     = array();
		$record_to_update = array();
		$record_to_create = array();
		$created_indexes  = array();
		$updated_indexes  = array();
		foreach ( $record_data as $key => $data ) {

			$data['attributes'] = array( 'type' => $object );
			if ( ! empty( $data['id'] ) ) {
				// Add a method here to unset unique indexes.
				$record_to_update[] = $data;
				$updated_indexes[]  = $key;
			} else {
				unset( $data['id'] );
				$record_to_create[] = $data;
				$created_indexes[]  = $key;
			}
			array_push( $request_data, $data );
		}

		return array(
			'record_to_create' => array(
				'records' => $record_to_create,
			),
			'record_to_update' => array(
				'records' => $record_to_update,
			),
			'created_indexes'  => $created_indexes,
			'updated_indexes'  => $updated_indexes,
		);
	}

	/**
	 * Bulk sync data.
	 *
	 * @param    array  $record_data     Record data.
	 * @param    array  $indexes         Indexes.
	 * @param    string $method          Method.
	 * @since    1.0.0
	 * @return   array
	 */
	public function bulk_sync_record( $record_data, $indexes, $method ) {

		$this->base_url = self::$instance_url;
		$endpoint       = '/services/data/' . self::$api_version . '/composite/sobjects';
		$params         = wp_json_encode( $record_data );
		$headers        = $this->get_auth_header();
		$response       = $this->$method( $endpoint, $params, $headers );

		if ( $this->if_access_token_expired( $response ) ) {
			$this->base_url = self::$instance_url;
			$endpoint       = '/services/data/' . self::$api_version . '/composite/sobjects';
			$params         = wp_json_encode( $record_data );
			$headers        = $this->get_auth_header();
			$response       = $this->$method( $endpoint, $params, $headers );
		}

		$indexed_response = array();

		if ( $this->is_success( $response ) ) {
			set_transient( 'mwb_sf_gf_bulk_sync_status', true );
			foreach ( $response['data'] as $line_key => $line_response ) {
				$line_response['message']                  = ( 'post' == $method ? 'Created' : 'Updated' ); // phpcs:ignore
				$indexed_response[ $indexes[ $line_key ] ] = $line_response;
			}
		}
		if ( empty( $indexed_response ) ) {
			$indexed_response = $response;
		}

		return $indexed_response;
	}

	/**
	 * Bulk sync data.
	 *
	 * @param    string  $object          CRM object.
	 * @param    array   $record_data     Record data.
	 * @param    string  $primary_field   Primary field.
	 * @param    integer $feed_id         Id of the feed.
	 * @param    array   $entry_index     Entry ID indexes.
	 * @since    1.0.0
	 * @return   mixed
	 */
	public function bulk_create_or_update_record( $object, $record_data, $primary_field, $feed_id, $entry_index ) {

		if ( empty( $entry_index ) || empty( $feed_id ) ) {
			return;
		}

		$request_data = $this->prepare_bulk_request_data( $object, $record_data );

		$create_response = array(); // 1,3,5
		$update_response = array(); // 2,4,6
		$merged_response = array();
		$record_response = array();

		if ( ! empty( $request_data['record_to_create'] ) && ! empty( $request_data['record_to_create']['records'] ) ) {
			$create_response = $this->bulk_sync_record(
				$request_data['record_to_create'],
				$request_data['created_indexes'],
				'post'
			);
		}

		if ( ! empty( $request_data['record_to_update'] ) && ! empty( $request_data['record_to_update']['records'] ) ) {
			$update_response = $this->bulk_sync_record(
				$request_data['record_to_update'],
				$request_data['updated_indexes'],
				'patch'
			);
		}

		$merged_response = array_replace( $create_response, $update_response );
		ksort( $merged_response );

		$duplicates = array();

		if ( ! empty( $merged_response ) ) {
			foreach ( $merged_response as $line_key => $line_response ) {
				if ( $line_response['success'] ) {
					$record_response[ $line_key ] = $line_response;
				} elseif ( ! empty( $line_response['errors'] ) ) {
					if ( ! empty( $line_response['errors'][0] ) && ! empty( $line_response['errors'][0]['statusCode'] ) ) {
						if ( 'DUPLICATES_DETECTED' === $line_response['errors'][0]['statusCode'] ) {
							array_push( $duplicates, $line_key );
						} else {
							$record_response[ $line_key ] = $line_response;
						}
					}
				}
			}
		}

		if ( ! empty( $duplicates ) ) {
			$duplicate_query = $this->prepare_duplicate_query( $record_data, $primary_field, $duplicates, $object );
			$query_response  = $this->query_records( $duplicate_query );
			if ( ! empty( $query_response ) ) {
				$query_response = $this->merge_query_response( $query_response, $record_data, $primary_field, $record_response );
			} else {
				$query_response = $this->complete_record_response( $record_data, $record_response );
			}

			ksort( $query_response );
			$record_response = $query_response;

		}

		$merge_created = array();

		if ( ! empty( $record_response ) ) {
			// Storing the created Slasesforce record Ids.
			$created_ids = get_post_meta( $feed_id, 'mwb_sf_gf_created_' . $object . '_id', true );
			$created_ids = ! empty( $created_ids ) ? $created_ids : array();

			foreach ( $record_response as $index => $response ) {
				if ( ! empty( $response['success'] ) && $response['success'] && ! empty( $response['id'] ) ) {

					$_entry_id = 'entry_' . $entry_index[ $index ];

					$merge_created[ $_entry_id ] = $response['id'];
				}
			}
			$created_ids = array_merge( $merge_created, $created_ids );
			update_post_meta( $feed_id, 'mwb_sf_gf_created_' . $object . '_id', $created_ids );

		}

		return $record_response;
	}

	/**
	 * If Query response is empty then complete the record reponse for remaining keys.
	 *
	 * @param array $record_data     Data of the record batch sent.
	 * @param array $record_response Response for that record data batch.
	 * @return array
	 */
	public function complete_record_response( $record_data, $record_response ) {

		foreach ( $record_data as $key => $val ) {
			if ( ! array_key_exists( $key, $record_response ) ) {

				$record_response[ $key ] = array(
					'id'      => '-',
					'success' => false,
					'errors'  => array(
						array(
							'statusCode' => 'DUPLICATES_DETECTED',
							'message'    => 'Check other records for same entry values',
						),
					),
					'message' => 'Please Check the Duplicate rules and Matching rules',
				);
			}
		}

		$record_response = array_values( $record_response );

		return $record_response;
	}

	/**
	 * Merge query response.
	 *
	 * @param    array  $query_response   Query response.
	 * @param    array  $record_data      Record data.
	 * @param    string $primary_field    Primary field.
	 * @param    array  $record_response  Record response.
	 * @since    1.0.0
	 * @return   array
	 */
	public function merge_query_response( $query_response, $record_data, $primary_field, $record_response ) {
		$primary_field_arr = array_column( $query_response, $primary_field );
		foreach ( $record_data as $key => $data ) {
			$field_key = array_search( $data[ $primary_field ], $primary_field_arr ); //phpcs:ignore
			if ( false !== $field_key ) {
				$record_response[ $key ] = array(
					'id'      => $query_response[ $field_key ]['Id'],
					'success' => true,
					'errors'  => array(),
					'message' => 'Updated',
				);
			}
		}
		return $record_response;
	}

	/**
	 * Query through records.
	 *
	 * @param     array $query    Query string.
	 * @since     1.0.0
	 * @return    array
	 */
	public function query_records( $query ) {
		$this->base_url = self::$instance_url;
		$records        = array();
		$endpoint       = '/services/data/' . self::$api_version . '/query';
		$params         = array(
			'q' => $query,
		);
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $params, $headers );

		if ( $this->is_success( $response ) ) {
			if ( ! empty( $response['data'] ) && ! empty( $response['data']['records'] ) ) {
				$records = $response['data']['records'];
			}
		}

		return $records;
	}

	/**
	 * Prepare duplicate query.
	 *
	 * @param    array  $record_data      Record data.
	 * @param    string $primary_field    Primary Field.
	 * @param    array  $duplicates       Duplicate records.
	 * @param    string $object           CRM Object.
	 * @since    1.0.0
	 * @return   array
	 */
	public function prepare_duplicate_query( $record_data, $primary_field, $duplicates, $object ) {

		$duplicate_records = array_filter(
			$record_data,
			function ( $key ) use ( $duplicates ) {
				return in_array( $key, $duplicates ); // phpcs:ignore
			},
			ARRAY_FILTER_USE_KEY
		);
		$duplicate_data    = array_column( $duplicate_records, $primary_field );
		$duplicate_string  = '';
		if ( ! empty( $duplicate_data ) ) {
			array_walk(
				$duplicate_data,
				function( &$item ) {
					$item = "'" . $item . "'";
				}
			);
			$duplicate_string = '( ' . implode( ' , ', $duplicate_data ) . ' )';
		}
		$duplicate_query = sprintf(
			'SELECT Id, %s FROM %s WHERE %s IN %s',
			$primary_field,
			$object,
			$primary_field,
			$duplicate_string
		);

		return $duplicate_query;
	}

	/**
	 * Delete bulk records.
	 *
	 * @param   array $record_ids  Record ID's.
	 * @since   1.0.0
	 * @return  array
	 */
	public function delete_bulk_records( $record_ids ) {
		$this->base_url = self::$instance_url;
		$endpoint       = '/services/data/' . self::$api_version . '/composite/sobjects';
		$params         = array( 'ids' => implode( ',', $record_ids ) );
		$headers        = $this->get_auth_header();
		$response       = $this->delete( $endpoint, $params, $headers );
		if ( $this->if_access_token_expired( $response ) ) {
			$this->base_url = self::$instance_url;
			$endpoint       = '/services/data/' . self::$api_version . '/composite/sobjects';
			$params         = array( 'ids' => implode( ',', $record_ids ) );
			$headers        = $this->get_auth_header();
			$response       = $this->delete( $endpoint, $params, $headers );
		}
		return $response;
	}



	/**
	 * Create single record on Salesforce
	 *
	 * @param  string  $object      CRM object name.
	 * @param  array   $record_data Request data.
	 * @param  boolean $is_bulk     Is a bulk request.
	 * @param  array   $log_data    Data to create log.
	 * @param  bool    $manual_sync If synced manually.
	 *
	 * @since 1.0.0
	 *
	 * @return array Response data.
	 */
	public function create_or_update_record( $object, $record_data, $is_bulk = false, $log_data = array(), $manual_sync = false ) {

		if ( ! empty( $record_data['entry_id'] ) ) {
			$entry_id = $record_data['entry_id'];
			unset( $record_data['entry_id'] );
		}

		$response_data = array(
			'success' => false,
			'msg'     => __( 'Something went wrong', 'mwb-gf-integration-with-salesforce' ),
		);

		$record_id = false;
		$feed_id   = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;
		if ( $manual_sync && ! empty( $log_data['method'] ) ) {
			$event = $log_data['method'];
		} else {
			$event = __FUNCTION__;
		}

		// Check for the existing record based on selected primary field.
		if ( $feed_id ) {
			$duplicate_check_fields = get_post_meta( $feed_id, 'mwb-' . self::$crm_prefix . '-gf-primary-field', true );
			$primary_field          = ! empty( $duplicate_check_fields ) ? $duplicate_check_fields : false;
		}

		if ( $primary_field ) {
			$search_response = $this->check_for_existing_record( $object, $record_data, $primary_field );
			if ( $this->if_access_token_expired( $search_response ) ) {
				$search_response = $this->check_for_existing_record( $object, $record_data, $primary_field );
			}

			// Get record id from search query result.
			$record_id = $this->may_be_get_record_id_from_search( $search_response, $record_data, $primary_field );
		}

		if ( ! $record_id ) {

			$response = $this->create_record( $object, $record_data, $is_bulk, $log_data );
			if ( $this->if_access_token_expired( $response ) ) {
				$response = $this->create_record( $object, $record_data, $is_bulk, $log_data );
			}

			if ( $this->is_success( $response ) ) {
				$response_data['success']  = true;
				$response_data['msg']      = 'Create_Record';
				$response_data['response'] = $response;
				$response_data['id']       = $this->get_object_id_from_response( $response );

				// Storing the created Slasesforce record Ids.
				$created_ids                         = get_post_meta( $feed_id, 'mwb_sf_gf_' . $object . '_id', true );
				$created_ids                         = ! empty( $created_ids ) ? $created_ids : array();
				$created_ids[ 'entry_' . $entry_id ] = $response_data['id'];
				update_post_meta( $feed_id, 'mwb_sf_gf_created_' . $object . '_id', $created_ids );
			} else {
				$response_data['success']  = false;
				$response_data['msg']      = esc_html__( 'Error posting to CRM', 'mwb-gf-integration-with-salesforce' );
				$response_data['response'] = $response;
			}
		} else {

			// Update an existing record based on record_id.
			$response = $this->update_record( $record_id, $object, $record_data, $is_bulk, $log_data );
			if ( $this->if_access_token_expired( $response ) ) {
				$response = $this->update_record( $record_id, $object, $record_data, $is_bulk, $log_data );
			}
			if ( $this->is_success( $response ) ) {

				// Insert record id and message to response.
				if ( isset( $response['message'] ) ) {
					$response['message'] = 'Updated';
				}
				if ( empty( $response['data'] ) ) {
					$response['data'] = array(
						'id' => $record_id,
					);
				}

				$response_data['success']  = true;
				$response_data['msg']      = 'Update_Record';
				$response_data['response'] = $response;
				$response_data['id']       = $record_id;
			}
		}

		// Insert log in db.
		$this->log_request_in_db( $event, $object, $record_data, $response, $log_data );

		return $response_data;
	}

	/**
	 * Insert log data in db.
	 *
	 * @param     string $event                Trigger event/ Feed .
	 * @param     string $sf_object            Name of zoho module.
	 * @param     array  $request              An array of request data.
	 * @param     array  $response             An array of response data.
	 * @param     array  $log_data             Data to log.
	 * @return    void
	 */
	public function log_request_in_db( $event, $sf_object, $request, $response, $log_data ) {

		$sf_id = $this->get_object_id_from_response( $response );

		if ( '-' == $sf_id || ( isset( $log_data['sync'] ) && 'bulk' == $log_data['sync'] ) ) { // phpcs:ignore
			if ( ! empty( $log_data['id'] ) ) {
				$sf_id = $log_data['id'];
			}
		}

		$request  = serialize( $request ); // @codingStandardsIgnoreLine
		$response = serialize( $response ); // @codingStandardsIgnoreLine

		$feed      = ! empty( $log_data['feed_name'] ) ? $log_data['feed_name'] : false;
		$feed_id   = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;
		$event     = ! empty( $event ) ? $event : false;
		$sf_object = ! empty( $log_data['sf_object'] ) ? $log_data['sf_object'] : false;

		$time     = time();
		$log_data = compact( 'event', 'sf_object', 'request', 'response', 'sf_id', 'feed_id', 'feed', 'time' );
		$this->insert_log_data( $log_data );

	}

	/**
	 * Retrieve object ID from crm response.
	 *
	 * @param     array $response     An array of response data from crm.
	 * @since     1.0.0
	 * @return    integer
	 */
	public function get_object_id_from_response( $response ) {
		$id = '-';
		if ( isset( $response['data'] ) && isset( $response['data']['id'] ) ) {
			return ! empty( $response['data']['id'] ) ? $response['data']['id'] : $id;
		}
		return $id;
	}

	/**
	 * Insert data to db.
	 *
	 * @param      array $data    Data to log.
	 * @since      1.0.0
	 * @return     void
	 */
	public function insert_log_data( $data ) {

		$connect         = 'Mwb_Gf_Integration_Connect_' . self::$crm_prefix . '_Framework';
		$connect_manager = $connect::get_instance();

		if ( 'yes' != $connect_manager->get_settings_details( 'logs' ) ) { // phpcs:ignore
			return;
		}

		global $wpdb;
		$table = $wpdb->prefix . 'mwb_' . self::$crm_prefix . '_gf_log';
		$wpdb->insert( $table, $data ); // phpcs:ignore
	}

	/**
	 * Check for exsiting record in search query response.
	 *
	 * @param array  $response      Search query response.
	 * @param array  $record_data   Request data of searched record.
	 * @param string $primary_field Primary field name.
	 *
	 * @return string|bool          Id of existing record or false.
	 */
	public function may_be_get_record_id_from_search( $response, $record_data, $primary_field ) {
		$record_id     = false;
		$found_records = array();
		if ( isset( $response['code'] ) && 200 == $response['code'] && 'OK' == $response['message'] ) { // phpcs:ignore
			if ( ! empty( $response['data'] ) && ! empty( $response['data']['searchRecords'] ) ) {
				$found_records = $response['data']['searchRecords'];
			}
		}

		if ( count( $found_records ) > 0 ) {
			foreach ( $found_records as $key => $record ) {
				if ( $record[ $primary_field ] === $record_data[ $primary_field ] ) {
					$record_id = $record['Id'];
					break;
				}
			}
		}
		return $record_id;
	}

	/**
	 * Check for existing record using parameterizedSearch.
	 *
	 * @param string $object        Target object name.
	 * @param array  $record_data   Record data.
	 * @param string $primary_field Primary field.
	 *
	 * @return array                Response data array.
	 */
	public function check_for_existing_record( $object, $record_data, $primary_field ) {
		$this->base_url = self::$instance_url;
		$endpoint       = '/services/data/' . self::$api_version . '/parameterizedSearch';
		$params         = array(
			'q'        => $record_data[ $primary_field ],
			'fields'   => array( 'Id', $primary_field ),
			'sobjects' => array(
				array( 'name' => $object ),
			),
		);

		$params   = wp_json_encode( $params );
		$headers  = $this->get_auth_header();
		$response = $this->post( $endpoint, $params, $headers );
		return $response;
	}

	/**
	 * Check if resposne has success code.
	 *
	 * @param  array $response  Response data.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean true|false.
	 */
	public function is_success( $response ) {
		if ( ! empty( $response['code'] ) ) {
			return in_array( $response['code'], array( 200, 201, 204, 202 ) ); // phpcs:ignore
		}
		return false;
	}

	/**
	 * Create a new record.
	 *
	 * @param  string  $object     Object name.
	 * @param  array   $record_data Record data.
	 * @param  boolean $is_bulk    Is a bulk request.
	 * @param  array   $log_data   Data to create log.
	 * @return array               Response data.
	 */
	public function create_record( $object, $record_data, $is_bulk, $log_data ) {

		$this->base_url = self::$instance_url;
		$endpoint       = '/services/data/' . self::$api_version . '/sobjects/' . $object;
		$params         = wp_json_encode( $record_data );
		$headers        = $this->get_auth_header();
		$response       = $this->post( $endpoint, $params, $headers );
		return $response;
	}

	/**
	 * Update an existing record.
	 *
	 * @param  string  $record_id   Record id to be updated.
	 * @param  string  $object      Object name.
	 * @param  array   $record_data Record data.
	 * @param  boolean $is_bulk     Is a bulk request.
	 * @param  array   $log_data    Data to create log.
	 * @return array                Response data.
	 */
	public function update_record( $record_id, $object, $record_data, $is_bulk, $log_data ) {

		$this->base_url = self::$instance_url;
		$endpoint       = '/services/data/' . self::$api_version . '/sobjects/' . $object . '/' . $record_id;
		$params         = wp_json_encode( $record_data );
		$headers        = $this->get_auth_header();
		$response       = $this->patch( $endpoint, $params, $headers );
		return $response;
	}

	/**
	 * Get available pricebook in crm.
	 *
	 * @param  boolean $force Fetch from api.
	 * @return array          Response data.
	 */
	public function get_pricebook_data( $force = false ) {
		$pricebook_data = get_transient( 'mwb_woo_salesforce_pricebook_data', '' );
		if ( ! $force && ! empty( $pricebook_data ) ) {
			return $pricebook_data;
		}
		$this->base_url = self::$instance_url;
		$endpoint       = '/services/data/' . self::$api_version . '/queryAll';
		$query_args     = array(
			'q' => 'SELECT Id, Name, IsStandard FROM Pricebook2',
		);
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $query_args, $headers );

		if ( $this->if_access_token_expired( $response ) ) {
			$this->base_url = self::$instance_url;
			$headers        = $this->get_auth_header();
			$response       = $this->get( $endpoint, $query_args, $headers );
		}
		if ( isset( $response['code'] ) && 200 == $response['code'] && 'OK' == $response['message'] ) { // phpcs:ignore
			if ( ! empty( $response['data'] ) && ! empty( $response['data']['records'] ) ) {
				$pricebook_data = $response['data']['records'];
				set_transient( 'mwb_woo_salesforce_pricebook_data', $pricebook_data );
			}
		}
		return $pricebook_data;
	}

	/**
	 * Check if response has expired access token message.
	 *
	 * @param  array $response Api response.
	 * @return bool            Access token status.
	 */
	public function if_access_token_expired( $response ) {
		if ( isset( $response['code'] ) && 401 == $response['code'] && 'Unauthorized' == $response['message'] ) { // phpcs:ignore
			return $this->renew_access_token();
		}
		return false;
	}

	/**
	 * Get available object in crm.
	 *
	 * @param  boolean $force Fetch from api.
	 * @return array          Response data.
	 */
	public function get_crm_objects( $force = false ) {

		$method  = get_option( 'mwb-' . self::$crm_prefix . '-gf-integration-method', false );
		$objects = array();

		switch ( $method ) {
			case 'api':
				$data = get_transient( 'mwb_salesforce_gf_objects_data' );
				if ( ! $force && false !== ( $data ) ) {
					return $data;
				}

				$this->base_url = self::$instance_url;
				$endpoint       = '/services/data/' . self::$api_version . '/sobjects';
				$headers        = $this->get_auth_header();
				$response       = $this->get( $endpoint, array(), $headers );
				$objects        = array();

				if ( ! empty( $response ) ) {
					if ( isset( $response['code'] ) && 401 == $response['code'] && 'Unauthorized' == $response['message'] ) { // phpcs:ignore
						if ( $this->renew_access_token() ) {
							$this->base_url = self::$instance_url;
							$headers        = $this->get_auth_header();
							$response       = $this->get( $endpoint, array(), $headers );
						}
					}
					if ( isset( $response['code'] ) && 200 == $response['code'] && 'OK' == $response['message'] ) { // phpcs:ignore
						if ( ! empty( $response['data'] ) && ! empty( $response['data']['sobjects'] ) ) {

							foreach ( $response['data']['sobjects'] as $object ) {
								if ( true == $object['createable'] && true == $object['layoutable'] ) { // phpcs:ignore
									$objects[ $object['name'] ] = $object['label'];
								}
							}

							set_transient( 'mwb_salesforce_gf_objects_data', $objects );
						}
					}
				}
				break;

			case 'web':
				$objects = array(
					'Lead' => 'Lead',
					'Case' => 'Case',
				);
				break;

		}

		return $objects;
	}

	/**
	 * Validate connection.
	 *
	 * @since   1.0.0
	 * @return  mixed
	 */
	public function validate_crm_connection() {
		$this->base_url = self::$instance_url;
		$endpoint       = '/services/data/' . self::$api_version . '/sobjects';
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, array(), $headers );
		if ( ! empty( $response ) ) {
			if ( isset( $response['code'] ) && 401 == $response['code'] && 'Unauthorized' == $response['message'] ) { // phpcs:ignore
				if ( $this->renew_access_token() ) {
					$this->base_url = self::$instance_url;
					$headers        = $this->get_auth_header();
					$response       = $this->get( $endpoint, array(), $headers );
				}
			}
		}

		return $response;
	}

	/**
	 * Get fields assosiated with an object.
	 *
	 * @param  string  $object Name of object.
	 * @param  boolean $force  Fetch from api.
	 * @return array           Response data.
	 */
	public function get_object_fields( $object, $force = false ) {

		$method = get_option( 'mwb-' . self::$crm_prefix . '-gf-integration-method', false );
		$fields = array();

		if ( $method ) {
			switch ( $method ) {
				case 'api':
					$data = get_transient( 'mwb_salesforce_gf' . $object . '_fields' );
					if ( ! $force && false !== ( $data ) ) {
						return $data;
					}

					$this->base_url = self::$instance_url;
					$endpoint       = '/services/data/' . self::$api_version . '/sobjects/' . $object . '/describe';
					$headers        = $this->get_auth_header();
					$response       = $this->get( $endpoint, array(), $headers );
					$fields         = array();
					if ( ! empty( $response ) ) {
						if ( isset( $response['code'] ) && 401 == $response['code'] && 'Unauthorized' == $response['message'] ) { // phpcs:ignore
							if ( $this->renew_access_token() ) {
								$this->base_url = self::$instance_url;
								$headers        = $this->get_auth_header();
								$response       = $this->get( $endpoint, array(), $headers );
							}
						}
						if ( isset( $response['code'] ) && 200 == $response['code'] && 'OK' == $response['message'] ) { // phpcs:ignore
							if ( ! empty( $response['data'] ) && ! empty( $response['data']['fields'] ) ) {
								$fields = $this->maybe_add_mandatory_fields( $response['data']['fields'] );
								set_transient( 'mwb_salesforce_gf' . $object . '_fields', $fields );
							}
						}
					}
					break;

				case 'web':
					global $wp_filesystem;  // Define global object of WordPress filesystem.
					require_once ABSPATH . 'wp-admin/includes/file.php'; // Since we are using the filesystem outside wp-admin.
					WP_Filesystem(); // Initialise new file system object.

					$fields_data = array(
						'Lead' => $wp_filesystem->get_contents( MWB_GF_INTEGRATION_WITH_SALESFORCE_DIRPATH . '/mwb-crm-fw/framework/json/lead.json' ), // phpcs:ignore
						'Case' => $wp_filesystem->get_contents( MWB_GF_INTEGRATION_WITH_SALESFORCE_DIRPATH . '/mwb-crm-fw/framework/json/case.json' ), // phpcs:ignore
					);

					foreach ( $fields_data as $key => $value ) {
						if ( $key == $object ) { // phpcs:ignore
							$fields = json_decode( preg_replace( '/[\x00-\x1F\x80-\xFF]/', '', $value ), true );
						}
					}
					break;
			}
		}
		return $fields;
	}

	/**
	 * Check for mandatory fields and add an index to it also retricts phone fields.
	 *
	 * @param    array $fields  An array of fields data.
	 * @since    1.0.0
	 * @return   array
	 */
	public function maybe_add_mandatory_fields( $fields = array() ) {
		if ( empty( $fields ) || ! is_array( $fields ) ) {
			return;
		}

		$fields_arr = array();

		foreach ( $fields as $key => $field ) {
			if ( ( isset( $field['createable'] ) && true == $field['createable'] ) || 'Id' == $field['name'] || ( isset( $field['custom'] ) && true == $field['custom'] ) ) { // phpcs:ignore

				$mandatory = '';
				if ( ! empty( $field['nameField'] ) || ( ! empty( $field['createable'] ) && empty( $field['nillable'] ) && empty( $field['defaultedOnCreate'] ) ) ) {
					$mandatory = true;
				}

				$field['mandatory'] = $mandatory;
				$fields_arr[]       = $field;
			}
		}

		return $fields_arr;

	}

	/**
	 * Sync data to salesforce via webtolead / webtocase.
	 *
	 * @param    bool   $auth      Whether to authorize or post.
	 * @param    string $object    CRM Module.
	 * @param    array  $data      Data to send.
	 * @param    array  $log_data  Log data.
	 * @since    1.0.0
	 * @return   mixed
	 */
	public function post_via_web( $auth = false, $object = 'Lead', $data = array(), $log_data = array() ) {
		global $wp_version;

		$web = array(
			'orgid'       => get_option( 'mwb-' . self::$crm_prefix . '-gf-salesforce-orgid', false ),
			'domain'      => get_option( 'mwb-' . self::$crm_prefix . '-gf-salesforce-domain', false ),
			'environment' => get_option( 'mwb-' . self::$crm_prefix . '-gf-enviornment', false ),
		);

		switch ( $object ) {
			case 'Case':
				$data['orgid'] = ! empty( $web['orgid'] ) ? $web['orgid'] : false;
				break;

			case 'Lead':
				$data['oid'] = ! empty( $web['orgid'] ) ? $web['orgid'] : false;
				break;
		}

		// We need an Org ID to sync data to Salesforce successfully.
		if ( 'Case' == $object && ! $data['orgid'] ) { // phpcs:ignore
			return false;
		} elseif ( 'Lead' == $object && ! $data['oid'] ) { // phpcs:ignore
			return false;
		}

		$header = array(
			'user-agent' => 'MWB GF Integration with Salesforce Plugin - WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
		);
		$body   = array();

		foreach ( $data as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $index ) {
					$body[] = urlencode( $key ) . '=' . urlencode( $index ); // phpcs:ignore
				}
			} else {
				$body[] = urlencode( $key ) . '=' . urlencode( $value ); // phpcs:ignore
			}
		}

		$post = implode( '&', $body );

		// Use test / www-subdomain based on whether this is a production or sandbox .
		$environment = ! empty( $web['environment'] ) ? $web['environment'] : false;
		$sub_doamin  = ( 'sandbox' == $environment ) ? 'test' : 'webto'; // phpcs:ignore
		$post_url    = 'https://' . $sub_doamin . '.salesforce.com/';
		$org_url     = ! empty( $web['domain'] ) ? $web['domain'] : false;

		if ( ! $org_url ) {
			$post_url = trailingslashit( $org_url );
		}

		// Use WebTo / Lead / Case based on object.
		$url = $post_url . sprintf( 'servlet/servlet.WebTo%s?encoding=UTF-8', $object );

		$response = $this->post_webto( $url, $post, $header );

		if ( isset( $response['code'] ) && 200 == $response['code'] ) { // phpcs:ignore
			$response['data'] = array(
				'msg'  => 'create_record',
				'time' => gmdate( 'd-m-y h:i:s A' ),
			);

			if ( false === $auth ) {
				$count = get_option( 'mwb-' . self::$crm_prefix . '-gf-synced-forms-count', 0 );
				update_option( 'mwb-' . self::$crm_prefix . '-gf-synced-forms-count', $count + 1 );

				$this->log_request_in_db( __FUNCTION__, $object, $data, $response, $log_data );
			}
		}

		return $response;
	}

	// End of class.
}
