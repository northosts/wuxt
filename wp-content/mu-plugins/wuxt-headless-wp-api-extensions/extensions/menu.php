<?php
    /**
     * Adds a menu endpoint
     */
    require_once(dirname(__FILE__) . '/../classes/menu-array.class.php');
    add_action('rest_api_init', 'wuxt_route_menu');


    function wuxt_route_menu() {
        register_rest_route('wuxt', '/v1/menu', array(
            'methods' => 'GET',
            'callback' => 'wuxt_get_menu',
        ));
    }


    function wuxt_get_menu($params) {
        $params = $params->get_params();
        $theme_locations = get_nav_menu_locations();

        if ( ! isset( $params['location'] ) ) {
            $params['location'] = 'main';
        }

        if ( !isset( $theme_locations[$params['location']] ) ) {
            return new WP_Error( 'wuxt_menu_error', __( 'Menu location does not exist' ), array( 'status' => 404 ) );
        }

        $menu_obj = get_term( $theme_locations[$params['location']], 'nav_menu' );

        $menu_name = $menu_obj->slug;

        $menu = new Menu( $menu_name );

        return $menu->getTree();
    }
