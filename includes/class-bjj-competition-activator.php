<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BJJ_Competition_Activator {

    public static function activate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Table for Categories/Divisions
        $table_categories = $wpdb->prefix . 'bjj_competition_categories';
        $sql_categories = "CREATE TABLE IF NOT EXISTS $table_categories (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            category_name VARCHAR(255) NOT NULL,
            belt_division VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Table for Academies
        $table_academies = $wpdb->prefix . 'bjj_competition_academies';
        $sql_academies = "CREATE TABLE IF NOT EXISTS $table_academies (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            main_coach_name VARCHAR(255),
            address VARCHAR(255),
            email VARCHAR(255),
            phone VARCHAR(50),
            affiliation VARCHAR(255),
            icon VARCHAR(255),
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Table for Competitors
        $table_competitors = $wpdb->prefix . 'bjj_competition_competitors';
        $sql_competitors = "CREATE TABLE IF NOT EXISTS $table_competitors (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            full_name VARCHAR(255) NOT NULL,
            gender VARCHAR(50),
            email VARCHAR(255),
            phone VARCHAR(50),
            weight FLOAT,
            age INT,
            nationality VARCHAR(100),
            profile_photo VARCHAR(255),
            belt VARCHAR(50),
            category_id BIGINT UNSIGNED,
            academy_id BIGINT UNSIGNED,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Table for Mats
        $table_mats = $wpdb->prefix . 'bjj_competition_mats';
        $sql_mats = "CREATE TABLE IF NOT EXISTS $table_mats (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            mat_name VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Table for Matches (Schedule)
        $table_matches = $wpdb->prefix . 'bjj_competition_matches';
        $sql_matches = "CREATE TABLE IF NOT EXISTS $table_matches (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            competitor1_id BIGINT UNSIGNED NOT NULL,
            competitor2_id BIGINT UNSIGNED NOT NULL,
            category_id BIGINT UNSIGNED,
            mat_id BIGINT UNSIGNED,
            fight_order INT,
            fight_time VARCHAR(50),
            status VARCHAR(50) DEFAULT 'queued',
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Table for Results
        $table_results = $wpdb->prefix . 'bjj_competition_results';
        $sql_results = "CREATE TABLE IF NOT EXISTS $table_results (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            match_id BIGINT UNSIGNED NOT NULL,
            winner_id BIGINT UNSIGNED,
            points VARCHAR(50),
            submission VARCHAR(50),
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Table for Podium
        $table_podium = $wpdb->prefix . 'bjj_competition_podium';
        $sql_podium = "CREATE TABLE IF NOT EXISTS $table_podium (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            category_id BIGINT UNSIGNED,
            first_place BIGINT UNSIGNED,
            second_place BIGINT UNSIGNED,
            third_place BIGINT UNSIGNED,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_categories );
        dbDelta( $sql_academies );
        dbDelta( $sql_competitors );
        dbDelta( $sql_mats );
        dbDelta( $sql_matches );
        dbDelta( $sql_results );
        dbDelta( $sql_podium );
    }
}
