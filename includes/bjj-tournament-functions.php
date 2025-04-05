<?php
if (!defined('ABSPATH')) exit;

/**
 * Create necessary database tables on plugin activation
 */
function bjj_tournament_create_tables() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Table: Events
    $table_events = $wpdb->prefix . 'bjj_tournament_events';
    $sql_events = "CREATE TABLE $table_events (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        event_name varchar(255) NOT NULL,
        event_image varchar(255),
        start_date datetime NOT NULL,
        end_date datetime NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Table: Categories
    $table_categories = $wpdb->prefix . 'bjj_tournament_categories';
    $sql_categories = "CREATE TABLE $table_categories (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        category_name varchar(100) NOT NULL,
        division varchar(100) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Table: Weight Classes (changed to varchar(50))
    $table_weight = $wpdb->prefix . 'bjj_tournament_weight_classes';
    $sql_weight = "CREATE TABLE $table_weight (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        weight varchar(50) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Table: Academies
    $table_academies = $wpdb->prefix . 'bjj_tournament_academies';
    $sql_academies = "CREATE TABLE $table_academies (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        academy_name varchar(255) NOT NULL,
        main_coach varchar(255),
        address text,
        email varchar(255),
        phone varchar(50),
        icon varchar(255),
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Table: Competitors
    $table_competitors = $wpdb->prefix . 'bjj_tournament_competitors';
    $sql_competitors = "CREATE TABLE $table_competitors (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        gender varchar(20),
        email varchar(255),
        phone varchar(50),
        birthday date,
        age int,
        weight varchar(50),
        nationality varchar(100),
        profile_photo varchar(255),
        belt varchar(50),
        category_id mediumint(9),
        academy_id mediumint(9),
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Table: Mats
    $table_mats = $wpdb->prefix . 'bjj_tournament_mats';
    $sql_mats = "CREATE TABLE $table_mats (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        mat_name varchar(100) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Table: Matches
    $table_matches = $wpdb->prefix . 'bjj_tournament_matches';
    $sql_matches = "CREATE TABLE $table_matches (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        event_id mediumint(9),
        competitor1_id mediumint(9),
        competitor2_id mediumint(9),
        mat_id mediumint(9),
        fight_time datetime,
        status varchar(50),
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Table: Brackets
    $table_brackets = $wpdb->prefix . 'bjj_tournament_brackets';
    $sql_brackets = "CREATE TABLE $table_brackets (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        match_id mediumint(9),
        round varchar(50),
        bracket_position varchar(50),
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Table: Results
    $table_results = $wpdb->prefix . 'bjj_tournament_results';
    $sql_results = "CREATE TABLE $table_results (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        match_id mediumint(9),
        winner_id mediumint(9),
        score varchar(50),
        method varchar(50),
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    dbDelta($sql_events);
    dbDelta($sql_categories);
    dbDelta($sql_weight);
    dbDelta($sql_academies);
    dbDelta($sql_competitors);
    dbDelta($sql_mats);
    dbDelta($sql_matches);
    dbDelta($sql_brackets);
    dbDelta($sql_results);
}

/**
 * AJAX Endpoint: Get ongoing matches for real-time updates
 */
add_action('wp_ajax_bjj_tournament_get_matches', 'bjj_tournament_get_matches');
add_action('wp_ajax_nopriv_bjj_tournament_get_matches', 'bjj_tournament_get_matches');
function bjj_tournament_get_matches() {
    check_ajax_referer('bjj_tournament_nonce', 'nonce');
    global $wpdb;
    $table = $wpdb->prefix . 'bjj_tournament_matches';
    $matches = $wpdb->get_results("SELECT * FROM $table WHERE status = 'ongoing'");
    wp_send_json_success($matches);
}
