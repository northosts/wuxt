<?php
    /**
     * Adds geo parameters to all post queries
     */
    add_action( 'pre_get_posts', 'wuxt_geo_query' );
    add_filter( 'posts_fields', 'wuxt_posts_fields', 10, 2 );
    add_filter( 'posts_join', 'wuxt_posts_join', 10, 2 );
    add_filter( 'posts_where', 'wuxt_posts_where', 10, 2 );
    add_filter( 'posts_orderby', 'wuxt_posts_orderby', 10, 2 );


    function wuxt_geo_query( $wp_query ) {

        // bail early when not a rest request
        if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
        	  return;
        }

        // check if we want to force an "and" relation
        if ( ! isset( $_GET['coordinates'] ) || !$_GET['coordinates'] || ! isset( $_GET['distance'] ) || !$_GET['distance'] ) {
            return;
        }

        preg_match( '/^(.*?):?(-?[0-9]+?\.?[0-9]*?),(.*?):?(-?[0-9]+?\.?[0-9]*?$)/', $_GET['coordinates'], $matches );
        if ( count( $matches ) != 5 ) {
            return new WP_Error( 'wuxt_geo_error', __( 'The coordinate parameter couldn\'t be parsed' ), array( 'status' => 500 ) );
        }

        $lat_meta_key = ( $matches[1] ) ? sanitize_key( $matches[1] ) : 'lat';
        $lng_meta_key = ( $matches[3] ) ? sanitize_key( $matches[3] ) : 'lng';
        $lat = floatVal( $matches[2] );
        $lng = floatVal( $matches[4] );

        preg_match( '/([0-9]+\.?[0-9]*)(.*)/', $_GET['distance'], $matches );
        if ( count( $matches ) != 3 ) {
            return new WP_Error( 'wuxt_geo_error', __( 'The distance parameter couldn\'t be parsed' ), array( 'status' => 500 ) );
        }

        $unit = ( $matches[2] == 'km' || $matches[2] == 'm' ) ? $matches[2] : 'km';
        $distance = floatVal( $matches[1] );

        $geo_query = array(
            'lat' => $lat,
            'lng' => $lng,
            'distance' => $distance,
            'unit' => $unit,
            'lat_key' => $lat_meta_key,
            'lng_key' => $lng_meta_key,
        );

        $wp_query->set( 'geo', $geo_query );
        $wp_query->set( 'orderby', 'distance' );
        $wp_query->set( 'order', 'ASC' );

    }


    function wuxt_haversine( $geo_query ) {
        global $wpdb;

        $radius = 6371;
        if ( ! empty( $geo_query['unit'] ) && 'm' == $geo_query['unit'] ) {
            $radius = 3959;
        }

        $haversine  = '( ' . $radius . ' * ' .
            'acos( cos( radians(%f) ) * cos( radians( geo_lat.meta_value ) ) * ' .
            'cos( radians( geo_lng.meta_value ) - radians(%f) ) + ' .
            'sin( radians(%f) ) * sin( radians( geo_lat.meta_value ) ) ) ' .
        ')';

        return $wpdb->prepare( $haversine, array( $geo_query['lat'], $geo_query['lng'], $geo_query['lat'] ) );
    }


		function wuxt_posts_fields( $sql_query, $wp_query ) {

  			global $wpdb;

  			$geo_query = $wp_query->get( 'geo' );

  			if ( $geo_query ) {
    				$sql_query .= ', ' . wuxt_haversine( $geo_query ) . ' AS geo_distance';
        }

  			return $sql_query;

    }


		function wuxt_posts_join( $sql_query, $wp_query ) {

  			global $wpdb;

  			$geo_query = $wp_query->get( 'geo' );

        if ( $geo_query ) {
    				$sql_query .= ' INNER JOIN ' . $wpdb->prefix . 'postmeta AS geo_lat ON ( ' . $wpdb->prefix . 'posts.ID = geo_lat.post_id ) ' .
    				    'INNER JOIN ' . $wpdb->prefix . 'postmeta AS geo_lng ON ( ' . $wpdb->prefix . 'posts.ID = geo_lng.post_id ) ';
        }

  			return $sql_query;

		}


		function wuxt_posts_where( $sql_query, $wp_query ) {

        global $wpdb;

        $geo_query = $wp_query->get( 'geo' );

        if ( $geo_query ) {

    				$haversine = wuxt_haversine( $geo_query );

    				$sql = ' AND ( geo_lat.meta_key = %s AND geo_lng.meta_key = %s AND ' . $haversine . ' <= %f )';
    				$sql_query .= $wpdb->prepare( $sql, $geo_query['lat_key'], $geo_query['lng_key'], $geo_query['distance'] );

        }

        return $sql_query;

  	}


		function wuxt_posts_orderby( $sql_query, $wp_query ) {

  			$geo_query = $wp_query->get( 'geo' );

        if ( $geo_query && 'distance' == $wp_query->get( 'orderby' ) ) {
            $sql_query = 'geo_distance ASC';
  			}

  			return $sql_query;

    }
