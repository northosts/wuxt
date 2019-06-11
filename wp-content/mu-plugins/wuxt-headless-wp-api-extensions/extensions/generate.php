<?php

    /**
     * Retrievse all available urls from WordPress to make generation of
     * static sites with dynamic urls possible
     */
    add_action('rest_api_init', 'wuxt_generate_route');


    function wuxt_generate_route() {
        register_rest_route('wuxt', '/v1/generate', array(
            'methods'  => 'GET',
            'callback' => 'wuxt_get_generate_urls'
        ));
    }


    function wuxt_get_generate_urls( $object ) {

        $site_url = get_site_url();

        $published_posts = new WP_Query( array(
          'post_type' => 'any',
          'posts_per_page' => -1,
          'post_status' => 'publish'
        ) );

        $urls = array();

        // published posts
        foreach( $published_posts->posts as $post ) {
            switch ( $post->post_type ) {
                case 'post':
                    $urls[] = str_replace( $site_url, '', get_permalink( $post->ID ) );
                break;
                case 'page':
                    $urls[] = str_replace( $site_url, '', get_page_link( $post->ID ) );
                break;
                case 'attachment':
                    $urls[] = str_replace( $site_url, '', get_attachment_link( $post->ID ) );
                break;
                case 'nav_menu_item':
                    $urls[] = str_replace( $site_url, '', get_post_meta( $post->ID, '_menu_item_url', true ) );
                break;
                case 'revision': break;
                default:
                    $urls[] = str_replace( $site_url, '', get_post_permalink( $post->ID ) );
                break;
            }
        }

        $menus = wp_get_nav_menus();
        $menu_locations = get_nav_menu_locations();
        foreach ( $menus as $menu ) {
            foreach ( wp_get_nav_menu_items( $menu ) as $item ) {

                // check if intern link
                if ( substr( $item->url, 0, strlen( $site_url ) ) === $site_url ) {
                    $urls[] = str_replace( $site_url, '', $item->url );
                }

            }
        }

        return array_values( array_unique( $urls ) );

    }
