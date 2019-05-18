<?php

    /**
     * Ads AND relation on rest category filter queries
     */
    add_action( 'pre_get_posts', 'wuxt_override_relation' );

    function wuxt_override_relation( $query ) {

          // bail early when not a rest request
        	if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
        		  return;
        	}

          // check if we want to force an "and" relation
          if ( ! isset( $_GET['and'] ) || !$_GET['and'] || 'false' === $_GET['and'] || !is_array( $tax_query = $query->get( 'tax_query' ) ) ) {
        		  return;
        	}

          foreach ( $tax_query as $index => $tax ) {
              $tax_query[$index]['operator'] = 'AND';
          }

        	$query->set( 'tax_query', $tax_query );

    }
