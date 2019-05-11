<?php


    /**
     * Theme setup.
     */
    add_action('after_setup_theme', 'wuxt_setup');
    function wuxt_setup() {

        add_theme_support('post-thumbnails');

    }


    /**
    *  REST API functions for nuxt.js
    */
    require_once(dirname(__FILE__) . '/api-extensions/front-page.php');
    require_once(dirname(__FILE__) . '/api-extensions/menu.php');
    require_once(dirname(__FILE__) . '/api-extensions/slug.php');
    require_once(dirname(__FILE__) . '/api-extensions/meta.php');
    require_once(dirname(__FILE__) . '/api-extensions/relation.php');


    /**
    *  Include Custom post types
    */
    foreach ( scandir( dirname(__FILE__) . '/cpts/' ) as $filename ) {
        $path = dirname(__FILE__) . '/cpts/' . $filename;
        if ( is_file( $path ) ) {
            require_once( $path );
        }
    }
