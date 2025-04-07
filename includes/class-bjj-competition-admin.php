<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BJJ_Competition_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_admin_pages' ) );
    }

    public function register_admin_pages() {
        // Main menu
        $main_slug = 'bjj_competition_main';
        add_menu_page(
            __( 'BJJ Tournament', 'bjj-competition' ),
            __( 'BJJ Tournament', 'bjj-competition' ),
            'manage_options',
            $main_slug,
            array( $this, 'render_main_page' ),
            'dashicons-groups',
            6
        );

        // Categories/Divisions
        add_submenu_page(
            $main_slug,
            __( 'Categories & Divisions', 'bjj-competition' ),
            __( 'Categories & Divisions', 'bjj-competition' ),
            'manage_options',
            'bjj_competition_categories',
            array( $this, 'render_categories_page' )
        );

        // Academies
        add_submenu_page(
            $main_slug,
            __( 'Academies', 'bjj-competition' ),
            __( 'Academies', 'bjj-competition' ),
            'manage_options',
            'bjj_competition_academies',
            array( $this, 'render_academies_page' )
        );

        // Competitors
        add_submenu_page(
            $main_slug,
            __( 'Competitors', 'bjj-competition' ),
            __( 'Competitors', 'bjj-competition' ),
            'manage_options',
            'bjj_competition_competitors',
            array( $this, 'render_competitors_page' )
        );

        // Mats
        add_submenu_page(
            $main_slug,
            __( 'Mats', 'bjj-competition' ),
            __( 'Mats', 'bjj-competition' ),
            'manage_options',
            'bjj_competition_mats',
            array( $this, 'render_mats_page' )
        );

        // Brackets
        add_submenu_page(
            $main_slug,
            __( 'Brackets', 'bjj-competition' ),
            __( 'Brackets', 'bjj-competition' ),
            'manage_options',
            'bjj_competition_brackets',
            array( $this, 'render_brackets_page' )
        );

        // Schedule Matches
        add_submenu_page(
            $main_slug,
            __( 'Schedule Matches', 'bjj-competition' ),
            __( 'Schedule Matches', 'bjj-competition' ),
            'manage_options',
            'bjj_competition_schedule',
            array( $this, 'render_schedule_page' )
        );

        // Results
        add_submenu_page(
            $main_slug,
            __( 'Results', 'bjj-competition' ),
            __( 'Results', 'bjj-competition' ),
            'manage_options',
            'bjj_competition_results',
            array( $this, 'render_results_page' )
        );

        // Podium
        add_submenu_page(
            $main_slug,
            __( 'Podium', 'bjj-competition' ),
            __( 'Podium', 'bjj-competition' ),
            'manage_options',
            'bjj_competition_podium',
            array( $this, 'render_podium_page' )
        );
    }

    public function render_main_page() {
        echo '<div class="wrap"><h1>BJJ Tournament Main</h1>';
        echo '<p>Welcome to the BJJ Tournament management plugin!</p>';
        echo '</div>';
    }

    public function render_categories_page() {
        echo '<div class="wrap"><h1>Categories & Divisions</h1>';
        echo do_shortcode('[bjj_tournament_categories]');
        echo '</div>';
    }

    public function render_academies_page() {
        echo '<div class="wrap"><h1>Academies</h1>';
        echo do_shortcode('[bjj_tournament_academies]');
        echo '</div>';
    }

    public function render_competitors_page() {
        echo '<div class="wrap"><h1>Competitors</h1>';
        echo do_shortcode('[bjj_tournament_competitors]');
        echo '</div>';
    }

    public function render_mats_page() {
        echo '<div class="wrap"><h1>Mats</h1>';
        echo do_shortcode('[bjj_tournament_mats]');
        echo '</div>';
    }

    public function render_brackets_page() {
        echo '<div class="wrap"><h1>Brackets</h1>';
        echo do_shortcode('[bjj_tournament_bracket]');
        echo '</div>';
    }

    public function render_schedule_page() {
        echo '<div class="wrap"><h1>Schedule Matches</h1>';
        echo do_shortcode('[bjj_tournament_schedule]');
        echo '</div>';
    }

    public function render_results_page() {
        echo '<div class="wrap"><h1>Results</h1>';
        echo do_shortcode('[bjj_tournament_results]');
        echo '</div>';
    }

    public function render_podium_page() {
        echo '<div class="wrap"><h1>Podium</h1>';
        echo '<p>Use shortcodes or custom UI to define podium positions.</p>';
        echo '</div>';
    }
}
