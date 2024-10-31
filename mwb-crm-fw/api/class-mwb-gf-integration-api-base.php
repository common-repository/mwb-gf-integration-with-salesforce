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
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mwb_Gf_Integration_Api_Base {

	/**
	 * Base usrl of the api
	 *
	 * @var     string $base_url
	 * @since   1.0.0
	 */
	public $base_url;

	/**
	 * Get Request.
	 *
	 * @param    string $endpoint    Api endpoint of mautic.
	 * @param    array  $data        Data to be used in request.
	 * @param    array  $headers     Header to be used in request.
	 * @since    1.0.0
	 * @return   array
	 */
	public function get( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'GET', $endpoint, $data, $headers );
	}

	/**
	 * Post Request.
	 *
	 * @param    string $endpoint    Api endpoint of mautic.
	 * @param    array  $data        Data to be used in request.
	 * @param    array  $headers     Header to be used in request.
	 * @since    1.0.0
	 * @return   array
	 */
	public function post( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'POST', $endpoint, $data, $headers );
	}

	/**
	 * Patch Request.
	 *
	 * @param    string $endpoint    Api endpoint of mautic.
	 * @param    array  $data        Data to be used in request.
	 * @param    array  $headers     Header to be used in request.
	 * @since    1.0.0
	 * @return   array
	 */
	public function patch( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'PATCH', $endpoint, $data, $headers );
	}

	/**
	 * Delete Request.
	 *
	 * @param string $endpoint Api endpoint of mautic.
	 * @param array  $data Data to be used in request.
	 * @param array  $headers header to be used in request.
	 */
	public function delete( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'DELETE', $endpoint, $data, $headers );
	}

	/**
	 * Send api request
	 *
	 * @param     string $method      HTTP method.
	 * @param     string $endpoint    Api endpoint.
	 * @param     array  $request     Request data.
	 * @param     array  $headers     Header to be used in request.
	 * @since     1.0.0
	 * @return    array
	 */
	private function request( $method, $endpoint, $request = array(), $headers = array() ) {
		$crm_slug = Mwb_Gf_Integration_With_Salesforce::mwb_get_current_crm_property( 'slug' );
		$method   = strtoupper( trim( $method ) );
		$url      = $this->base_url . $endpoint;
		$headers  = array_merge( $headers, $this->get_headers() );
		$args     = array(
			'method'    => $method,
			'headers'   => $headers,
			'timeout'   => 20,
			'sslverify' => apply_filters( 'mwb_' . $crm_slug . '_gf_use_sslverify', true ),
		);
		if ( ! empty( $request ) ) {
			if ( in_array( $method, array( 'GET', 'DELETE' ), true ) ) {
				$url = add_query_arg( $request, $url );
			} else {
				$args['body'] = $request;
			}
		}

		$args     = apply_filters( 'mwb_' . $crm_slug . '_gf_http_request_args', $args, $url );
		$response = wp_remote_request( $url, $args );

		// Add better exception handling.
		try {
			$data = $this->parse_response( $response );
		} catch ( Exception $e ) {
			$data = $e->getMessage();
		}

		$this->log_request( $method, $url, $request, $response ); // Keep log of all api interactions.

		return $data;
	}

	/**
	 * Parse Api response.
	 *
	 * @param     array $response   Raw response.
	 * @return    array             filtered reponse.
	 * @throws    Exception         Throws   Exception on error.
	 * @since     1.0.0
	 */
	private function parse_response( $response ) {

		if ( $response instanceof WP_Error ) {
			throw new Exception( 'Error', 0 );
		}
		$code    = (int) wp_remote_retrieve_response_code( $response );
		$message = wp_remote_retrieve_response_message( $response );
		$body    = wp_remote_retrieve_body( $response );
		$data    = json_decode( $body, ARRAY_A );
		return compact( 'code', 'message', 'data' );
	}

	/**
	 * Get headers.
	 *
	 * @since    1.0.0
	 * @return   array   Headers.
	 */
	public function get_headers() {
		return array();
	}

	/**
	 * Log request in sync log.
	 *
	 * @param  string $method   Request Method.
	 * @param  string $url      Request Url.
	 * @param  array  $request  Request data.
	 * @param  array  $response Response data.
	 */
	private function log_request( $method, $url, $request, $response ) {

		$crm_slug        = Mwb_Gf_Integration_With_Salesforce::mwb_get_current_crm_property( 'slug' );
		$crm_name        = Mwb_Gf_Integration_With_Salesforce::mwb_get_current_crm_property( 'name' );
		$connect         = 'Mwb_Gf_Integration_Connect_' . $crm_name . '_Framework';
		$connect_manager = $connect::get_instance();
		$path            = $connect_manager->create_log_folder( 'mwb-' . $crm_slug . '-gf-logs' );
		$log_dir         = $path . '/mwb-' . $crm_slug . '-gf-' . gmdate( 'Y-m-d' ) . '.log';

		$log  = 'Url : ' . $url . PHP_EOL;
		$log .= 'Method : ' . $method . PHP_EOL;
		$log .= 'Time: ' . current_time( 'F j, Y  g:i a' ) . PHP_EOL;
		$log .= 'Request : ' . wp_json_encode( $request ) . PHP_EOL;
		$log .= 'Response : ' . wp_json_encode( $response ) . PHP_EOL;
		$log .= '------------------------------------' . PHP_EOL;
		file_put_contents( $log_dir, $log, FILE_APPEND ); //phpcs:ignore
	}

	/**
	 * Send webto request
	 *
	 * @param     string $url         URL to post.
	 * @param     array  $request     Request data.
	 * @param     array  $headers     Header to be used in request.
	 * @since     1.0.0
	 * @return    array
	 */
	public function post_webto( $url, $request = array(), $headers = array() ) {

		$args = array(
			'headers' => $headers,
			'timeout' => 20,
			'body'    => $request,
		);

		$result = wp_remote_post( $url, $args );

		// Add better exception handling.
		try {
			$data = $this->parse_webto_response( $result );
		} catch ( Exception $e ) {
			$data = $e->getMessage();
		}

		// Keep log of all api interactions.
		$this->log_request( 'POST', $url, $request, $result );

		return $data;
	}

	/**
	 * Parse webto response.
	 *
	 * @param     array $response   Raw response.
	 * @return    array             filtered reponse.
	 * @throws    Exception         Throws   Exception on error.
	 * @since     1.0.0
	 */
	private function parse_webto_response( $response ) {

		if ( $response instanceof WP_Error ) {
			throw new Exception( 'Error', 0 );
		}

		$code    = (int) wp_remote_retrieve_response_code( $response );
		$message = wp_remote_retrieve_response_message( $response );
		$body    = wp_remote_retrieve_body( $response );
		$data    = json_decode( $body, ARRAY_A );
		if ( ! isset( $response['headers']['is-processed'] ) ) {
			return compact( 'code', 'message', 'data' );
		} elseif ( 'true' === $response['headers']['is-processed'] ) {
			return compact( 'code', 'message', 'data' );
		}
	}
}
