<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BJJ_Competition_Shortcodes {

    public function __construct() {
        add_shortcode( 'bjj_tournament_event', array( $this, 'render_event' ) );
        add_shortcode( 'bjj_tournament_categories', array( $this, 'render_categories' ) );
        add_shortcode( 'bjj_tournament_weight', array( $this, 'render_weight' ) );
        add_shortcode( 'bjj_tournament_academies', array( $this, 'render_academies' ) );
        add_shortcode( 'bjj_tournament_competitors', array( $this, 'render_competitors' ) );
        add_shortcode( 'bjj_tournament_mats', array( $this, 'render_mats' ) );
        add_shortcode( 'bjj_tournament_matchmaking', array( $this, 'render_matchmaking' ) );
        add_shortcode( 'bjj_tournament_bracket', array( $this, 'render_bracket' ) );
        add_shortcode( 'bjj_tournament_schedule', array( $this, 'render_schedule' ) );
        add_shortcode( 'bjj_tournament_results', array( $this, 'render_results' ) );
    }

    public function render_event() {
        ob_start();
        echo '<div class="bjj-event">';
        echo '<h3>Event Shortcode</h3>';
        // Add your event UI here
        echo '</div>';
        return ob_get_clean();
    }

    public function render_categories() {
        ob_start();
        // A simple form to add categories & belt divisions
        ?>
        <div class="bjj-categories">
            <h3>Create / Manage Categories & Divisions</h3>
            <form id="bjj-category-form">
                <label>Category Name (e.g. MALE ADULT GI):</label>
                <input type="text" name="category_name" required />
                <label>Belt/Division (e.g. White, Blue, Purple...):</label>
                <input type="text" name="belt_division" required />
                <button type="submit" class="button button-primary">Add Category</button>
            </form>
            <hr/>
            <div id="bjj-category-list">
                <!-- Existing categories will load via AJAX -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_weight() {
        ob_start();
        ?>
        <div class="bjj-weight">
            <h3>Weight Classes</h3>
            <p>(Not fully specified in your request. You can adapt similarly.)</p>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_academies() {
        ob_start();
        ?>
        <div class="bjj-academies">
            <h3>Create / Manage Academies</h3>
            <form id="bjj-academy-form">
                <label>Academy Name:</label>
                <input type="text" name="name" required />
                <label>Main Coach Name:</label>
                <input type="text" name="main_coach_name" />
                <label>Address:</label>
                <input type="text" name="address" />
                <label>Email:</label>
                <input type="email" name="email" />
                <label>Phone:</label>
                <input type="text" name="phone" />
                <label>Affiliation:</label>
                <input type="text" name="affiliation" />
                <label>Icon (URL or Media Library):</label>
                <input type="text" name="icon" />
                <button type="submit" class="button button-primary">Add Academy</button>
            </form>
            <hr/>
            <div id="bjj-academy-list">
                <!-- Existing academies load via AJAX -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_competitors() {
        ob_start();
        ?>
        <div class="bjj-competitors">
            <h3>Create / Manage Competitors</h3>
            <form id="bjj-competitor-form">
                <label>Full Name:</label>
                <input type="text" name="full_name" required />
                <label>Gender:</label>
                <select name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="boy">Boy</option>
                    <option value="girl">Girl</option>
                </select>
                <label>Email:</label>
                <input type="email" name="email" />
                <label>Phone:</label>
                <input type="text" name="phone" />
                <label>Weight (lbs):</label>
                <input type="number" step="0.1" name="weight" />
                <label>Age:</label>
                <input type="number" name="age" />
                <label>Nationality:</label>
                <input type="text" name="nationality" />
                <label>Profile Photo (URL or Media Library):</label>
                <input type="text" name="profile_photo" />
                <label>Belt:</label>
                <select name="belt">
                    <option value="white">White</option>
                    <option value="gray">Gray</option>
                    <option value="yellow">Yellow</option>
                    <option value="orange">Orange</option>
                    <option value="green">Green</option>
                    <option value="blue">Blue</option>
                    <option value="purple">Purple</option>
                    <option value="brown">Brown</option>
                    <option value="black">Black</option>
                </select>
                <label>Category:</label>
                <!-- We'll load categories dynamically, but for now, just a text or placeholder -->
                <select name="category_id" id="bjj-competitor-category"></select>
                
                <label>Academy:</label>
                <select name="academy_id" id="bjj-competitor-academy"></select>

                <button type="submit" class="button button-primary">Add Competitor</button>
            </form>
            <hr/>
            <div id="bjj-competitor-list">
                <!-- Existing competitors load via AJAX -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_mats() {
        ob_start();
        ?>
        <div class="bjj-mats">
            <h3>Create / Manage Mats</h3>
            <form id="bjj-mat-form">
                <label>Mat Name/Number:</label>
                <input type="text" name="mat_name" required />
                <button type="submit" class="button button-primary">Add Mat</button>
            </form>
            <hr/>
            <div id="bjj-mat-list">
                <!-- Existing mats load via AJAX -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_matchmaking() {
        ob_start();
        echo '<h3>Matchmaking Shortcode</h3>';
        // You can create a custom UI for matchmaking
        return ob_get_clean();
    }

    public function render_bracket() {
        ob_start();
        ?>
        <div class="bjj-bracket">
            <h3>Bracket Shortcode</h3>
            <p>This can embed a 3rd-party bracket plugin using a separate shortcode or custom logic.</p>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_schedule() {
        ob_start();
        ?>
        <div class="bjj-schedule">
            <h3>Schedule Matches</h3>
            <p>Drag and drop to set fight order, assign mat, set time, etc.</p>
            <div id="bjj-schedule-list">
                <!-- Schedule items load via AJAX with drag/drop to reorder -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_results() {
        ob_start();
        ?>
        <div class="bjj-results">
            <h3>Enter / View Results</h3>
            <div id="bjj-results-list">
                <!-- Ongoing and completed fights load here. Admin can pick a winner, points, or submission. -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
