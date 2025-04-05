<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BJTTournamentShortcodes {
    public function __construct() {
        add_shortcode('bjj_tournament_event', array($this, 'event_shortcode'));
        add_shortcode('bjj_tournament_categories', array($this, 'categories_shortcode'));
        add_shortcode('bjj_tournament_weight', array($this, 'weight_shortcode'));
        add_shortcode('bjj_tournament_academies', array($this, 'academies_shortcode'));
        add_shortcode('bjj_tournament_competitors', array($this, 'competitors_shortcode'));
        add_shortcode('bjj_tournament_mats', array($this, 'mats_shortcode'));
        add_shortcode('bjj_tournament_matchmaking', array($this, 'matchmaking_shortcode'));
        add_shortcode('bjj_tournament_bracket', array($this, 'bracket_shortcode'));
        add_shortcode('bjj_tournament_schedule', array($this, 'schedule_shortcode'));
        add_shortcode('bjj_tournament_results', array($this, 'results_shortcode'));
    }

    public function event_shortcode() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_events';
        $events = $wpdb->get_results("SELECT * FROM $table");
        ob_start();
        ?>
        <h2>Events</h2>
        <?php if ($events): ?>
            <ul>
                <?php foreach ($events as $evt): ?>
                    <li><?php echo esc_html($evt->event_name); ?> (<?php echo date('Y-m-d H:i', strtotime($evt->start_date)); ?> - <?php echo date('Y-m-d H:i', strtotime($evt->end_date)); ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No events yet.</p>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    public function categories_shortcode() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_categories';
        $categories = $wpdb->get_results("SELECT * FROM $table");
        ob_start();
        ?>
        <h2>Categories & Divisions</h2>
        <?php if ($categories): ?>
            <ul>
                <?php foreach ($categories as $cat): ?>
                    <li><?php echo esc_html($cat->category_name); ?> - <?php echo esc_html($cat->division); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No categories available.</p>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    public function weight_shortcode() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_weight_classes';
        $weights = $wpdb->get_results("SELECT * FROM $table");
        ob_start();
        ?>
        <h2>Weight Classes</h2>
        <?php if ($weights): ?>
            <ul>
                <?php foreach ($weights as $w): ?>
                    <li><?php echo esc_html($w->weight); ?> lbs</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No weight classes available.</p>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    public function academies_shortcode() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_academies';
        $academies = $wpdb->get_results("SELECT * FROM $table");
        ob_start();
        ?>
        <h2>Academies</h2>
        <?php if ($academies): ?>
            <ul>
                <?php foreach ($academies as $acad): ?>
                    <li><?php echo esc_html($acad->academy_name); ?> (Coach: <?php echo esc_html($acad->main_coach); ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No academies available.</p>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    public function competitors_shortcode() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_competitors';
        $competitors = $wpdb->get_results("SELECT * FROM $table");
        ob_start();
        ?>
        <h2>Competitors</h2>
        <?php if ($competitors): ?>
            <ul>
                <?php foreach ($competitors as $comp): ?>
                    <li><?php echo esc_html($comp->name); ?> - <?php echo esc_html($comp->belt); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No competitors available.</p>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    public function mats_shortcode() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_mats';
        $mats = $wpdb->get_results("SELECT * FROM $table");
        ob_start();
        ?>
        <h2>Mats</h2>
        <?php if ($mats): ?>
            <ul>
                <?php foreach ($mats as $mat): ?>
                    <li><?php echo esc_html($mat->mat_name); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No mats available.</p>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    public function matchmaking_shortcode() {
        ob_start();
        ?>
        <h2>Matchmaking</h2>
        <p>This section will display the match queue and allow assignment of competitors to fights.</p>
        <?php
        return ob_get_clean();
    }

    public function bracket_shortcode() {
        ob_start();
        ?>
        <h2>Bracket</h2>
        <p>This section will display the tournament bracket dynamically based on match results.</p>
        <?php
        return ob_get_clean();
    }

    public function schedule_shortcode() {
        ob_start();
        ?>
        <h2>Schedule Matches</h2>
        <p>This section will display current and upcoming matches.</p>
        <?php
        return ob_get_clean();
    }

    public function results_shortcode() {
        ob_start();
        ?>
        <h2>Results</h2>
        <p>This section will display match results and allow entering new results.</p>
        <?php
        return ob_get_clean();
    }
}
