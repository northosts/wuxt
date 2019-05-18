<?php


    /**
     * Theme setup.
     */
    add_action('after_setup_theme', 'wuxt_setup');
    function wuxt_setup() {

        add_theme_support('post-thumbnails');

    }


    add_action('init', 'wuxt_register_menu');
    function wuxt_register_menu() {
        register_nav_menu('main', __('Main meny'));
    }    


    /**
    *  Include Custom post types
    */
    if ( is_dir(  dirname(__FILE__) . '/cpts/' ) ) {
      foreach ( scandir( dirname(__FILE__) . '/cpts/' ) as $filename ) {
          $path = dirname(__FILE__) . '/cpts/' . $filename;
          if ( is_file( $path ) ) {
              require_once( $path );
          }
      }
    }
