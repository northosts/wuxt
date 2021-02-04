<?php

    /**
     * Adds a front-page endpoint for generell front-page settings in the
     * Front-end
     */
    add_action('rest_api_init', 'wuxt_front_page_route');


    function wuxt_front_page_route() {
        register_rest_route('wuxt', '/v1/front-page', array(
            'methods'  => 'GET',
            'callback' => 'wuxt_get_front_page',
            'permission_callback' => function () {
                return '__return_true';
            },
        ));
    }


    function wuxt_get_front_page( $object ) {

        $request  = new WP_REST_Request( 'GET', '/wp/v2/posts' );

        $frontpage_id = get_option( 'page_on_front' );
        if ( $frontpage_id ) {
            $request  = new WP_REST_Request( 'GET', '/wp/v2/pages/' . $frontpage_id );
        }

        $response = rest_do_request( $request );
        if ($response->is_error()) {
            return new WP_Error( 'wuxt_request_error', __( 'Request Error' ), array( 'status' => 500 ) );
        }

        $embed = $object->get_param( '_embed' ) !== NULL;
        $data = rest_get_server()->response_to_data( $response, $embed );

        return $data;

    }
