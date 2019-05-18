<?php

    /**
     * Register meta fields for some common plugins like WordPress SEO or ACF
     */
    add_action( 'init', 'wuxt_register_acf_meta' );
    add_action( 'init', 'wuxt_register_yoast_meta' );

    function wuxt_register_acf_meta() {

        if ( function_exists( 'acf_get_field_groups' ) ) {
            $result = array();
            $acf_field_groups = acf_get_field_groups();
            foreach( $acf_field_groups as $acf_field_group) {
                foreach($acf_field_group['location'] as $group_locations) {
                    foreach($group_locations as $rule) {
                        foreach(acf_get_fields( $acf_field_group ) as $field) {
                            register_meta( 'post', $field['name'], array( 'show_in_rest' => true ) );
                        }
                    }
                }
            }
        }
    }


    function wuxt_register_yoast_meta() {
        if(in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins')))){

            $allowed_yoast_keywords = array(
                '_yoast_wpseo_title',
                '_yoast_wpseo_bctitle',
                '_yoast_wpseo_metadesc',
                '_yoast_wpseo_focuskw',
                '_yoast_wpseo_meta-robots-noindex',
                '_yoast_wpseo_meta-robots-nofollow',
                '_yoast_wpseo_meta-robots-adv',
                '_yoast_wpseo_canonical',
                '_yoast_wpseo_redirect',
                '_yoast_wpseo_opengraph-description',
            );

            foreach( $allowed_yoast_keywords as $field) {
                register_meta( 'post', $field, array( 'show_in_rest' => true ) );
            }
            
        }
    }
