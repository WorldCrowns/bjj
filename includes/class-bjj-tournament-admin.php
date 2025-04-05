<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BJTTournamentAdmin {
    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_post_bjj_tournament_save', array($this, 'save_data'));
        add_action('admin_post_bjj_tournament_delete', array($this, 'delete_data'));
    }

    public function register_admin_menu() {
        add_menu_page(
            'BJJ Tournament',
            'BJJ Tournament',
            'manage_options',
            'bjj-tournament',
            array($this, 'admin_dashboard'),
            'dashicons-awards',
            6
        );

        add_submenu_page(
            'bjj-tournament',
            'Event',
            'Event',
            'manage_options',
            'bjj-tournament-event',
            array($this, 'event_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Categories & Divisions',
            'Categories',
            'manage_options',
            'bjj-tournament-categories',
            array($this, 'categories_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Weight Classes',
            'Weight Classes',
            'manage_options',
            'bjj-tournament-weight',
            array($this, 'weight_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Academies',
            'Academies',
            'manage_options',
            'bjj-tournament-academies',
            array($this, 'academies_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Competitors',
            'Competitors',
            'manage_options',
            'bjj-tournament-competitors',
            array($this, 'competitors_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Mats',
            'Mats',
            'manage_options',
            'bjj-tournament-mats',
            array($this, 'mats_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Matchmaking',
            'Matchmaking',
            'manage_options',
            'bjj-tournament-matchmaking',
            array($this, 'matchmaking_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Bracket',
            'Bracket',
            'manage_options',
            'bjj-tournament-bracket',
            array($this, 'bracket_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Schedule Matches',
            'Schedule Matches',
            'manage_options',
            'bjj-tournament-schedule',
            array($this, 'schedule_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Results',
            'Results',
            'manage_options',
            'bjj-tournament-results',
            array($this, 'results_page')
        );

        add_submenu_page(
            'bjj-tournament',
            'Reset',
            'Reset',
            'manage_options',
            'bjj-tournament-reset',
            array($this, 'reset_page')
        );
    }

    public function admin_dashboard() {
        echo '<div class="wrap"><h1>BJJ Tournament Dashboard</h1>';
        echo '<p>Select a section from the submenu.</p></div>';
    }

    // 1. Event page with separate datetime-local fields
    public function event_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_events';
        $edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
        $editing_event = null;
        $start_value = '';
        $end_value = '';

        if ($edit_id > 0) {
            $editing_event = $wpdb->get_row("SELECT * FROM $table WHERE id = $edit_id");
            if ($editing_event) {
                $start_value = date('Y-m-d\TH:i', strtotime($editing_event->start_date));
                $end_value   = date('Y-m-d\TH:i', strtotime($editing_event->end_date));
            }
        }

        $events = $wpdb->get_results("SELECT * FROM $table");
        ?>
        <div class="wrap">
            <h1>Event</h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('bjj_tournament_event'); ?>
                <input type="hidden" name="action" value="bjj_tournament_save">
                <input type="hidden" name="data_type" value="event">
                <?php if ($edit_id && $editing_event): ?>
                    <input type="hidden" name="edit_id" value="<?php echo esc_attr($edit_id); ?>">
                <?php endif; ?>
                <table class="form-table">
                    <tr>
                        <th><label for="event_name">Event Name</label></th>
                        <td>
                            <input type="text" name="event_name" id="event_name" 
                                   value="<?php echo ($editing_event) ? esc_attr($editing_event->event_name) : ''; ?>"
                                   required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="event_image">Event Image</label></th>
                        <td>
                            <input type="text" class="bjj-upload-image" name="event_image" id="event_image" placeholder="Media Library URL"
                                   value="<?php echo ($editing_event) ? esc_attr($editing_event->event_image) : ''; ?>">
                            <button type="button" class="button bjj-upload-image-button">Upload</button>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="start_date">Start Date/Time</label></th>
                        <td>
                            <input type="datetime-local" name="start_date" id="start_date"
                                   value="<?php echo esc_attr($start_value); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="end_date">End Date/Time</label></th>
                        <td>
                            <input type="datetime-local" name="end_date" id="end_date"
                                   value="<?php echo esc_attr($end_value); ?>" required>
                        </td>
                    </tr>
                </table>
                <?php submit_button( ($edit_id) ? 'Update Event' : 'Save Event'); ?>
            </form>

            <h2>Existing Events</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($events): foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo esc_html($event->id); ?></td>
                            <td><?php echo esc_html($event->event_name); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($event->start_date)); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($event->end_date)); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=bjj-tournament-event&edit=' . $event->id); ?>">Edit</a> |
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=bjj_tournament_delete&data_type=event&id=' . $event->id), 'bjj_tournament_event'); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // 2. Categories & Divisions page
    public function categories_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_categories';
        $categories = $wpdb->get_results("SELECT * FROM $table");
        ?>
        <div class="wrap">
            <h1>Categories & Divisions</h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('bjj_tournament_category'); ?>
                <input type="hidden" name="action" value="bjj_tournament_save">
                <input type="hidden" name="data_type" value="category">
                <table class="form-table">
                    <tr>
                        <th><label for="category_name">Category Name</label></th>
                        <td>
                            <select name="category_name" id="category_name" required>
                                <option value="">Select Category</option>
                                <option value="MALE ADULT GI">MALE ADULT GI</option>
                                <option value="MALE ADULT NOGI">MALE ADULT NOGI</option>
                                <option value="FEMALE ADULT GI">FEMALE ADULT GI</option>
                                <option value="FEMALE ADULT NOGI">FEMALE ADULT NOGI</option>
                                <option value="BOYS GI">BOYS GI</option>
                                <option value="BOYS NOGI">BOYS NOGI</option>
                                <option value="GIRLS GI">GIRLS GI</option>
                                <option value="GIRLS NOGI">GIRLS NOGI</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="division">Division (Belt)</label></th>
                        <td>
                            <select name="division" id="division" required>
                                <option value="">Select Division</option>
                                <option value="White">White</option>
                                <option value="Blue">Blue</option>
                                <option value="Purple">Purple</option>
                                <option value="Brown">Brown</option>
                                <option value="Black">Black</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save Category'); ?>
            </form>
            <h2>Existing Categories</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Division</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($categories): foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo esc_html($cat->id); ?></td>
                            <td><?php echo esc_html($cat->category_name); ?></td>
                            <td><?php echo esc_html($cat->division); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=bjj-tournament-categories&edit=' . $cat->id); ?>">Edit</a> |
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=bjj_tournament_delete&data_type=category&id=' . $cat->id), 'bjj_tournament_category'); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // 3. Weight Classes page (weight field as text)
    public function weight_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_weight_classes';
        $weights = $wpdb->get_results("SELECT * FROM $table");
        ?>
        <div class="wrap">
            <h1>Weight Classes</h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('bjj_tournament_weight'); ?>
                <input type="hidden" name="action" value="bjj_tournament_save">
                <input type="hidden" name="data_type" value="weight">
                <table class="form-table">
                    <tr>
                        <th><label for="weight">Weight (lbs)</label></th>
                        <td><input type="text" name="weight" id="weight" required></td>
                    </tr>
                </table>
                <?php submit_button('Save Weight Class'); ?>
            </form>
            <h2>Existing Weight Classes</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Weight (lbs)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($weights): foreach ($weights as $w): ?>
                        <tr>
                            <td><?php echo esc_html($w->id); ?></td>
                            <td><?php echo esc_html($w->weight); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=bjj-tournament-weight&edit=' . $w->id); ?>">Edit</a> |
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=bjj_tournament_delete&data_type=weight&id=' . $w->id), 'bjj_tournament_weight'); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // 4. Academies page with media uploader for academy icon
    public function academies_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_academies';
        $academies = $wpdb->get_results("SELECT * FROM $table");
        ?>
        <div class="wrap">
            <h1>Academies / Schools / Affiliations</h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('bjj_tournament_academy'); ?>
                <input type="hidden" name="action" value="bjj_tournament_save">
                <input type="hidden" name="data_type" value="academy">
                <table class="form-table">
                    <tr>
                        <th><label for="academy_name">Academy Name</label></th>
                        <td><input type="text" name="academy_name" id="academy_name" required></td>
                    </tr>
                    <tr>
                        <th><label for="main_coach">Main Coach Name</label></th>
                        <td><input type="text" name="main_coach" id="main_coach"></td>
                    </tr>
                    <tr>
                        <th><label for="address">Address</label></th>
                        <td><textarea name="address" id="address"></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="email">Email</label></th>
                        <td><input type="email" name="email" id="email"></td>
                    </tr>
                    <tr>
                        <th><label for="phone">Phone Number</label></th>
                        <td><input type="text" name="phone" id="phone"></td>
                    </tr>
                    <tr>
                        <th><label for="icon">Academy Icon</label></th>
                        <td>
                            <input type="text" class="bjj-upload-image" name="icon" id="icon" placeholder="Media Library URL">
                            <button type="button" class="button bjj-upload-image-button">Upload</button>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save Academy'); ?>
            </form>
            <h2>Existing Academies</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Academy Name</th>
                        <th>Main Coach</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($academies): foreach ($academies as $academy): ?>
                        <tr>
                            <td><?php echo esc_html($academy->id); ?></td>
                            <td><?php echo esc_html($academy->academy_name); ?></td>
                            <td><?php echo esc_html($academy->main_coach); ?></td>
                            <td><?php echo esc_html($academy->email); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=bjj-tournament-academies&edit=' . $academy->id); ?>">Edit</a> |
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=bjj_tournament_delete&data_type=academy&id=' . $academy->id), 'bjj_tournament_academy'); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // 5. Competitors page with media uploader and full country list for Nationality
    public function competitors_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_competitors';
        $competitors = $wpdb->get_results("SELECT * FROM $table");
        $weights = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bjj_tournament_weight_classes");
        $categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bjj_tournament_categories");
        $academies = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bjj_tournament_academies");

        $countries = array(
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola',
            'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria',
            'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados',
            'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan',
            'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
            'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia',
            'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile',
            'China', 'Colombia', 'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica',
            'Croatia', 'Cuba', 'Cyprus', 'Czechia (Czech Republic)', 'Democratic Republic of the Congo',
            'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador',
            'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia',
            'Eswatini (fmr. "Swaziland")', 'Ethiopia', 'Fiji', 'Finland', 'France',
            'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana',
            'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau',
            'Guyana', 'Haiti', 'Holy See', 'Honduras', 'Hungary',
            'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq',
            'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan',
            'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait',
            'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho',
            'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg',
            'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali',
            'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico',
            'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro',
            'Morocco', 'Mozambique', 'Myanmar (formerly Burma)', 'Namibia', 'Nauru',
            'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger',
            'Nigeria', 'North Korea', 'North Macedonia', 'Norway', 'Oman',
            'Pakistan', 'Palau', 'Palestine State', 'Panama', 'Papua New Guinea',
            'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal',
            'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis',
            'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe',
            'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone',
            'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia',
            'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka',
            'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria',
            'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo',
            'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan',
            'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom',
            'United States of America', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Venezuela',
            'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
        );
        ?>
        <div class="wrap">
            <h1>Competitors</h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('bjj_tournament_competitor'); ?>
                <input type="hidden" name="action" value="bjj_tournament_save">
                <input type="hidden" name="data_type" value="competitor">
                <table class="form-table">
                    <tr>
                        <th><label for="name">Name</label></th>
                        <td><input type="text" name="name" id="name" required></td>
                    </tr>
                    <tr>
                        <th><label for="gender">Gender</label></th>
                        <td>
                            <select name="gender" id="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="email">Email</label></th>
                        <td><input type="email" name="email" id="email"></td>
                    </tr>
                    <tr>
                        <th><label for="phone">Phone Number</label></th>
                        <td><input type="text" name="phone" id="phone"></td>
                    </tr>
                    <tr>
                        <th><label for="birthday">Birthday</label></th>
                        <td><input type="date" name="birthday" id="birthday"></td>
                    </tr>
                    <tr>
                        <th><label for="weight">Weight</label></th>
                        <td>
                            <select name="weight" id="weight">
                                <?php foreach ($weights as $w): ?>
                                    <option value="<?php echo esc_attr($w->weight); ?>"><?php echo esc_html($w->weight); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="nationality">Nationality</label></th>
                        <td>
                            <select name="nationality" id="nationality">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?php echo esc_attr($country); ?>"><?php echo esc_html($country); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="profile_photo">Profile Photo</label></th>
                        <td>
                            <input type="text" class="bjj-upload-image" name="profile_photo" id="profile_photo" placeholder="Media Library URL">
                            <button type="button" class="button bjj-upload-image-button">Upload</button>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="belt">Belt</label></th>
                        <td>
                            <select name="belt" id="belt">
                                <option value="White">White</option>
                                <option value="Gray">Gray</option>
                                <option value="Yellow">Yellow</option>
                                <option value="Orange">Orange</option>
                                <option value="Green">Green</option>
                                <option value="Blue">Blue</option>
                                <option value="Purple">Purple</option>
                                <option value="Brown">Brown</option>
                                <option value="Black">Black</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="category_id">Category</label></th>
                        <td>
                            <select name="category_id" id="category_id">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo esc_attr($cat->id); ?>"><?php echo esc_html($cat->category_name); ?> - <?php echo esc_html($cat->division); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="academy_id">Academy</label></th>
                        <td>
                            <select name="academy_id" id="academy_id">
                                <?php foreach ($academies as $acad): ?>
                                    <option value="<?php echo esc_attr($acad->id); ?>"><?php echo esc_html($acad->academy_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save Competitor'); ?>
            </form>
            <h2>Existing Competitors</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Academy</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($competitors): foreach ($competitors as $comp): ?>
                        <tr>
                            <td><?php echo esc_html($comp->id); ?></td>
                            <td><?php echo esc_html($comp->name); ?></td>
                            <td><?php echo esc_html($comp->category_id); ?></td>
                            <td><?php echo esc_html($comp->academy_id); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=bjj-tournament-competitors&edit=' . $comp->id); ?>">Edit</a> |
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=bjj_tournament_delete&data_type=competitor&id=' . $comp->id), 'bjj_tournament_competitor'); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // 6. Mats page
    public function mats_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_mats';
        $mats = $wpdb->get_results("SELECT * FROM $table");
        ?>
        <div class="wrap">
            <h1>Mats</h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('bjj_tournament_mat'); ?>
                <input type="hidden" name="action" value="bjj_tournament_save">
                <input type="hidden" name="data_type" value="mat">
                <table class="form-table">
                    <tr>
                        <th><label for="mat_name">Mat Name</label></th>
                        <td><input type="text" name="mat_name" id="mat_name" required></td>
                    </tr>
                </table>
                <?php submit_button('Save Mat'); ?>
            </form>
            <h2>Existing Mats</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mat Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($mats): foreach ($mats as $mat): ?>
                        <tr>
                            <td><?php echo esc_html($mat->id); ?></td>
                            <td><?php echo esc_html($mat->mat_name); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=bjj-tournament-mats&edit=' . $mat->id); ?>">Edit</a> |
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=bjj_tournament_delete&data_type=mat&id=' . $mat->id), 'bjj_tournament_mat'); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // 7. Matchmaking page – Updated to display competitors in a draggable list.
    public function matchmaking_page() {
        global $wpdb;
        $competitors_table = $wpdb->prefix . 'bjj_tournament_competitors';
        // Retrieve competitors sorted by category_id, belt, and weight
        $competitors = $wpdb->get_results("
            SELECT * 
            FROM $competitors_table
            ORDER BY category_id, belt, weight
        ");

        echo '<div class="wrap">';
        echo '  <h1>Matchmaking</h1>';
        echo '  <p>This interface displays competitors sorted by category, belt, and weight. Drag and drop items to re-order them.</p>';
        echo '  <div id="matchmaking-container">';
        
        if ($competitors) {
            echo '<ul id="matchmaking-list" class="matchmaking-list">';
            foreach ($competitors as $comp) {
                $display  = esc_html($comp->name) . ' | ';
                $display .= 'Belt: ' . esc_html($comp->belt) . ' | ';
                $display .= 'Weight: ' . esc_html($comp->weight);
                echo '<li class="matchmaking-item" data-id="' . esc_attr($comp->id) . '">' . $display . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No competitors found.</p>';
        }
        echo '  </div>';
        echo '</div>';
    }

    // 8. Bracket page
    public function bracket_page() {
        ?>
        <div class="wrap">
            <h1>Bracket</h1>
            <p>The bracket will be autogenerated based on match entries and will advance as results are entered. Use the buttons on the header to switch between category/division brackets.</p>
            <div id="bracket-container">
                <p>Bracket will be displayed here.</p>
            </div>
        </div>
        <?php
    }

    // 9. Schedule Matches page
    public function schedule_page() {
        ?>
        <div class="wrap">
            <h1>Schedule Matches</h1>
            <p>This section displays current fights, next fights, and the queue. Icons indicate current, next, and queued matches.</p>
            <div id="schedule-container">
                <p>Loading match schedule...</p>
            </div>
        </div>
        <?php
    }

    // 10. Results page
    public function results_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_tournament_matches';
        $matches = $wpdb->get_results("SELECT * FROM $table WHERE status = 'ongoing'");
        ?>
        <div class="wrap">
            <h1>Results</h1>
            <p>Select the ongoing match and enter the result. Choose the winner, record points (if applicable) or submission, and the bracket will update automatically.</p>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('bjj_tournament_result'); ?>
                <input type="hidden" name="action" value="bjj_tournament_save">
                <input type="hidden" name="data_type" value="result">
                <table class="form-table">
                    <tr>
                        <th><label for="match_id">Match ID</label></th>
                        <td>
                            <select name="match_id" id="match_id">
                                <?php foreach ($matches as $match): ?>
                                    <option value="<?php echo esc_attr($match->id); ?>">Match <?php echo esc_html($match->id); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="winner_id">Winner Competitor ID</label></th>
                        <td><input type="number" name="winner_id" id="winner_id" required></td>
                    </tr>
                    <tr>
                        <th><label for="score">Score / Points</label></th>
                        <td><input type="text" name="score" id="score"></td>
                    </tr>
                    <tr>
                        <th><label for="method">Method (Points or Submission)</label></th>
                        <td>
                            <select name="method" id="method">
                                <option value="Points">Points</option>
                                <option value="Submission">Submission</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Submit Result'); ?>
            </form>
        </div>
        <?php
    }

    // 11. Reset page
    public function reset_page() {
        ?>
        <div class="wrap">
            <h1>Reset Tournament Data</h1>
            <p>Click the button below to clear all matches, results, and competitors.</p>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" onsubmit="return confirm('Are you sure you want to reset all tournament data?');">
                <?php wp_nonce_field('bjj_tournament_reset'); ?>
                <input type="hidden" name="action" value="bjj_tournament_reset_action">
                <?php submit_button('Reset Tournament'); ?>
            </form>
        </div>
        <?php
    }

    // Save data handler for all forms
    public function save_data() {
        global $wpdb;
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }
        $data_type = sanitize_text_field($_POST['data_type']);
        switch ($data_type) {
            case 'event':
                check_admin_referer('bjj_tournament_event');
                $table = $wpdb->prefix . 'bjj_tournament_events';
                $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
                $data = array(
                    'event_name'  => sanitize_text_field($_POST['event_name']),
                    'event_image' => esc_url_raw($_POST['event_image']),
                    'start_date'  => sanitize_text_field($_POST['start_date']),
                    'end_date'    => sanitize_text_field($_POST['end_date'])
                );
                if ($edit_id > 0) {
                    $wpdb->update($table, $data, array('id' => $edit_id));
                } else {
                    $wpdb->insert($table, $data);
                }
                break;
            case 'category':
                check_admin_referer('bjj_tournament_category');
                $table = $wpdb->prefix . 'bjj_tournament_categories';
                $data = array(
                    'category_name' => sanitize_text_field($_POST['category_name']),
                    'division'      => sanitize_text_field($_POST['division'])
                );
                $wpdb->insert($table, $data);
                break;
            case 'weight':
                check_admin_referer('bjj_tournament_weight');
                $table = $wpdb->prefix . 'bjj_tournament_weight_classes';
                $data = array(
                    'weight' => sanitize_text_field($_POST['weight'])
                );
                $wpdb->insert($table, $data);
                break;
            case 'academy':
                check_admin_referer('bjj_tournament_academy');
                $table = $wpdb->prefix . 'bjj_tournament_academies';
                $data = array(
                    'academy_name' => sanitize_text_field($_POST['academy_name']),
                    'main_coach'   => sanitize_text_field($_POST['main_coach']),
                    'address'      => sanitize_textarea_field($_POST['address']),
                    'email'        => sanitize_email($_POST['email']),
                    'phone'        => sanitize_text_field($_POST['phone']),
                    'icon'         => esc_url_raw($_POST['icon'])
                );
                $wpdb->insert($table, $data);
                break;
            case 'competitor':
                check_admin_referer('bjj_tournament_competitor');
                $table = $wpdb->prefix . 'bjj_tournament_competitors';
                $birthday = sanitize_text_field($_POST['birthday']);
                $age = !empty($birthday) ? date_diff(date_create($birthday), date_create('now'))->y : 0;
                $data = array(
                    'name'          => sanitize_text_field($_POST['name']),
                    'gender'        => sanitize_text_field($_POST['gender']),
                    'email'         => sanitize_email($_POST['email']),
                    'phone'         => sanitize_text_field($_POST['phone']),
                    'birthday'      => $birthday,
                    'age'           => $age,
                    'weight'        => sanitize_text_field($_POST['weight']),
                    'nationality'   => sanitize_text_field($_POST['nationality']),
                    'profile_photo' => esc_url_raw($_POST['profile_photo']),
                    'belt'          => sanitize_text_field($_POST['belt']),
                    'category_id'   => intval($_POST['category_id']),
                    'academy_id'    => intval($_POST['academy_id'])
                );
                $wpdb->insert($table, $data);
                break;
            case 'mat':
                check_admin_referer('bjj_tournament_mat');
                $table = $wpdb->prefix . 'bjj_tournament_mats';
                $data = array(
                    'mat_name' => sanitize_text_field($_POST['mat_name'])
                );
                $wpdb->insert($table, $data);
                break;
            case 'result':
                check_admin_referer('bjj_tournament_result');
                $table = $wpdb->prefix . 'bjj_tournament_results';
                $data = array(
                    'match_id'  => intval($_POST['match_id']),
                    'winner_id' => intval($_POST['winner_id']),
                    'score'     => sanitize_text_field($_POST['score']),
                    'method'    => sanitize_text_field($_POST['method'])
                );
                $wpdb->insert($table, $data);
                $wpdb->update($wpdb->prefix . 'bjj_tournament_matches', array('status' => 'finished'), array('id' => intval($_POST['match_id'])));
                break;
            default:
                break;
        }
        wp_redirect(wp_get_referer());
        exit;
    }

    // Delete data handler
    public function delete_data() {
        global $wpdb;
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }
        $data_type = sanitize_text_field($_GET['data_type']);
        $id = intval($_GET['id']);
        switch ($data_type) {
            case 'event':
                check_admin_referer('bjj_tournament_event');
                $table = $wpdb->prefix . 'bjj_tournament_events';
                $wpdb->delete($table, array('id' => $id));
                break;
            case 'category':
                check_admin_referer('bjj_tournament_category');
                $table = $wpdb->prefix . 'bjj_tournament_categories';
                $wpdb->delete($table, array('id' => $id));
                break;
            case 'weight':
                check_admin_referer('bjj_tournament_weight');
                $table = $wpdb->prefix . 'bjj_tournament_weight_classes';
                $wpdb->delete($table, array('id' => $id));
                break;
            case 'academy':
                check_admin_referer('bjj_tournament_academy');
                $table = $wpdb->prefix . 'bjj_tournament_academies';
                $wpdb->delete($table, array('id' => $id));
                break;
            case 'competitor':
                check_admin_referer('bjj_tournament_competitor');
                $table = $wpdb->prefix . 'bjj_tournament_competitors';
                $wpdb->delete($table, array('id' => $id));
                break;
            case 'mat':
                check_admin_referer('bjj_tournament_mat');
                $table = $wpdb->prefix . 'bjj_tournament_mats';
                $wpdb->delete($table, array('id' => $id));
                break;
            default:
                break;
        }
        wp_redirect(wp_get_referer());
        exit;
    }
}

add_action('admin_post_bjj_tournament_reset_action', 'bjj_tournament_reset_data');
function bjj_tournament_reset_data() {
    global $wpdb;
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }
    check_admin_referer('bjj_tournament_reset');
    $tables = array(
        'bjj_tournament_matches',
        'bjj_tournament_results',
        'bjj_tournament_competitors'
    );
    foreach ($tables as $table) {
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}$table");
    }
    wp_redirect(wp_get_referer());
    exit;
}
