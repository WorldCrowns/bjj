<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BJJ_Competition_AJAX {

    public function __construct() {
        // Category CRUD
        add_action( 'wp_ajax_bjj_add_category', array( $this, 'bjj_add_category' ) );
        add_action( 'wp_ajax_bjj_get_categories', array( $this, 'bjj_get_categories' ) );
        add_action( 'wp_ajax_bjj_delete_category', array( $this, 'bjj_delete_category' ) );

        // Academy CRUD
        add_action( 'wp_ajax_bjj_add_academy', array( $this, 'bjj_add_academy' ) );
        // You can add more AJAX actions for listing, editing, deleting academies as needed

        // Reset All Data
        add_action( 'wp_ajax_bjj_competition_reset_all', array( $this, 'bjj_competition_reset_all' ) );
    }

    // --- Example: Add Academy ---
    public function bjj_add_academy() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );

        global $wpdb;
        $table = $wpdb->prefix . 'bjj_competition_academies';

        $name        = sanitize_text_field( $_POST['name'] );
        $main_coach  = sanitize_text_field( $_POST['main_coach'] );
        $address     = sanitize_text_field( $_POST['address'] );
        $email       = sanitize_email( $_POST['email'] );
        $phone       = sanitize_text_field( $_POST['phone'] );
        $affiliation = sanitize_text_field( $_POST['affiliation'] );
        $icon        = esc_url_raw( $_POST['icon'] );

        $result = $wpdb->insert(
            $table,
            array(
                'name'             => $name,
                'main_coach_name'  => $main_coach,
                'address'          => $address,
                'email'            => $email,
                'phone'            => $phone,
                'affiliation'      => $affiliation,
                'icon'             => $icon
            ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );

        if ( $result ) {
            wp_send_json_success( array( 'message' => 'Academy added successfully.' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Error adding academy.' ) );
        }
    }

    // --- Example: Add Category (existing handlers) ---
    public function bjj_add_category() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );

        global $wpdb;
        $table = $wpdb->prefix . 'bjj_competition_categories';

        $category_name = sanitize_text_field( $_POST['category_name'] );
        $belt_division = sanitize_text_field( $_POST['belt_division'] );

        $wpdb->insert(
            $table,
            array(
                'category_name' => $category_name,
                'belt_division' => $belt_division
            ),
            array( '%s', '%s' )
        );

        wp_send_json_success( array( 'message' => 'Category added successfully.' ) );
    }

    public function bjj_get_categories() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_competition_categories';
        $results = $wpdb->get_results( "SELECT * FROM $table" );
        wp_send_json_success( $results );
    }

    public function bjj_delete_category() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_competition_categories';
        $id = intval( $_POST['id'] );
        $wpdb->delete( $table, array( 'id' => $id ), array( '%d' ) );
        wp_send_json_success( array( 'message' => 'Category deleted.' ) );
    }

    public function bjj_competition_reset_all() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );
        global $wpdb;
        $tables = array(
            'bjj_competition_categories',
            'bjj_competition_academies',
            'bjj_competition_competitors',
            'bjj_competition_mats',
            'bjj_competition_matches',
            'bjj_competition_results',
            'bjj_competition_podium'
        );
        foreach( $tables as $tbl ) {
            $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}{$tbl}" );
        }
        wp_send_json_success( array( 'message' => 'All data has been reset.' ) );
    }
}
