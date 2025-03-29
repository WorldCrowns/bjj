<?php
/**
 * Competitors Tab (Admin)
 * Allows manual entry of competitor data including:
 * - Athlete Type (Adult/Teen) with dynamic Belt and Competition Category dropdowns.
 * - Country dropdown.
 * - Weight dropdown (populated from bjj_weight_options).
 * - Academy/School dropdown (populated from bjj_academies).
 * - Media Library selectors for Competitor Photo.
 */

// Process form submission for adding a new competitor.
if ( isset( $_POST['bjj_competitor_add_nonce'] ) && wp_verify_nonce( $_POST['bjj_competitor_add_nonce'], 'bjj_save_competitor' ) ) {
    $competitors = get_option( 'bjj_competitors', array() );

    // Build the new competitor entry from POST data.
    $new_competitor = array(
        'athlete_type'         => sanitize_text_field( $_POST['athlete_type'] ), // Adult or Teen
        'first_name'           => sanitize_text_field( $_POST['first_name'] ),
        'last_name'            => sanitize_text_field( $_POST['last_name'] ),
        'phone'                => sanitize_text_field( $_POST['phone'] ),
        'email'                => sanitize_email( $_POST['email'] ),
        'country'              => sanitize_text_field( $_POST['country'] ),
        'belt'                 => sanitize_text_field( $_POST['belt'] ),
        // Instead of a free text input, we now store academy as an ID (from the academies tab)
        'academy_id'           => isset( $_POST['academy_id'] ) ? intval( $_POST['academy_id'] ) : 0,
        'competition_category' => sanitize_text_field( $_POST['competition_category'] ),
        'age'                  => intval( $_POST['age'] ),
        'weight'               => sanitize_text_field( $_POST['weight'] ),
        'image_id'             => isset( $_POST['image_id'] ) ? intval( $_POST['image_id'] ) : 0,
        'mat_assignment'       => sanitize_text_field( $_POST['mat_assignment'] ),
    );

    // Generate a unique ID for the new competitor.
    $new_id = time() . rand(10, 99);
    $competitors[ $new_id ] = $new_competitor;

    update_option( 'bjj_competitors', $competitors );
    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Competitor added successfully!', 'bjj' ) . '</p></div>';
}

// Process form submission for updating MAT assignments for existing competitors.
if ( isset( $_POST['bjj_competitor_assign_nonce'] ) && wp_verify_nonce( $_POST['bjj_competitor_assign_nonce'], 'bjj_save_competitor_assignments' ) ) {
    $competitors = get_option( 'bjj_competitors', array() );
    if ( ! empty( $_POST['mat_assignment'] ) && is_array( $_POST['mat_assignment'] ) ) {
        foreach ( $_POST['mat_assignment'] as $comp_id => $mat ) {
            if ( isset( $competitors[ $comp_id ] ) ) {
                $competitors[ $comp_id ]['mat_assignment'] = sanitize_text_field( $mat );
            }
        }
    }
    update_option( 'bjj_competitors', $competitors );
    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Competitor assignments updated successfully!', 'bjj' ) . '</p></div>';
}

$competitors = get_option( 'bjj_competitors', array() );

// Get weight options.
$weight_options = get_option( 'bjj_weight_options', array() );

// Get academy options from the Academies Tab.
$academies = get_option( 'bjj_academies', array() );

// List of all countries.
$countries = array(
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria",
    "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia",
    "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde",
    "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros",
    "Congo (Congo-Brazzaville)", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia (Czech Republic)", "Democratic Republic of the Congo",
    "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea",
    "Estonia", "Eswatini (formerly Swaziland)", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany",
    "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Holy See", "Honduras", "Hungary",
    "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan",
    "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein",
    "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania",
    "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar (formerly Burma)",
    "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia",
    "Norway", "Oman", "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines",
    "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines",
    "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore",
    "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan",
    "Suriname", "Sweden", "Switzerland", "Syria", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
    "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates",
    "United Kingdom", "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
);
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Add New Competitor', 'bjj' ); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_competitor', 'bjj_competitor_add_nonce' ); ?>
        <table class="form-table">
            <tr>
                <th><label for="athlete_type"><?php _e( 'Athlete Type', 'bjj' ); ?></label></th>
                <td>
                    <select name="athlete_type" id="athlete_type">
                        <option value="Adult"><?php _e( 'Adult', 'bjj' ); ?></option>
                        <option value="Teen"><?php _e( 'Teen', 'bjj' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="first_name"><?php _e( 'First Name', 'bjj' ); ?></label></th>
                <td><input type="text" name="first_name" id="first_name" required></td>
            </tr>
            <tr>
                <th><label for="last_name"><?php _e( 'Last Name', 'bjj' ); ?></label></th>
                <td><input type="text" name="last_name" id="last_name" required></td>
            </tr>
            <tr>
                <th><label for="phone"><?php _e( 'Phone', 'bjj' ); ?></label></th>
                <td><input type="text" name="phone" id="phone"></td>
            </tr>
            <tr>
                <th><label for="email"><?php _e( 'Email', 'bjj' ); ?></label></th>
                <td><input type="email" name="email" id="email"></td>
            </tr>
            <tr>
                <th><label for="country"><?php _e( 'Country', 'bjj' ); ?></label></th>
                <td>
                    <select name="country" id="country">
                        <option value=""><?php _e('Select a Country', 'bjj'); ?></option>
                        <?php foreach($countries as $country): ?>
                            <option value="<?php echo esc_attr($country); ?>"><?php echo esc_html($country); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <!-- Dynamic Belts and Competition Category will be populated via JS based on Athlete Type -->
            <tr>
                <th><label for="belt"><?php _e( 'Belt', 'bjj' ); ?></label></th>
                <td>
                    <select name="belt" id="belt">
                        <!-- Options will be populated by JS -->
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="competition_category"><?php _e( 'Competition Category', 'bjj' ); ?></label></th>
                <td>
                    <select name="competition_category" id="competition_category">
                        <!-- Options will be populated by JS -->
                    </select>
                </td>
            </tr>
            <!-- Academy Dropdown based on Academies Tab entries -->
            <tr>
                <th><label for="academy_id"><?php _e( 'Academy/School', 'bjj' ); ?></label></th>
                <td>
                    <select name="academy_id" id="academy_id">
                        <option value=""><?php _e('Select an Academy/School', 'bjj'); ?></option>
                        <?php 
                        if ( ! empty( $academies ) && is_array( $academies ) ) {
                            foreach ( $academies as $id => $academy ) {
                                echo '<option value="' . esc_attr($id) . '">' . esc_html($academy['name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="age"><?php _e( 'Age', 'bjj' ); ?></label></th>
                <td><input type="number" name="age" id="age"></td>
            </tr>
            <tr>
                <th><label for="weight"><?php _e( 'Weight (lbs)', 'bjj' ); ?></label></th>
                <td>
                    <select name="weight" id="weight">
                        <option value=""><?php _e('Select Weight', 'bjj'); ?></option>
                        <?php 
                        if( !empty($weight_options) && is_array($weight_options) ):
                            foreach( $weight_options as $w ): ?>
                                <option value="<?php echo esc_attr($w); ?>"><?php echo esc_html($w); ?></option>
                            <?php endforeach;
                        endif;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="image_id"><?php _e( 'Competitor Photo', 'bjj' ); ?></label></th>
                <td>
                    <input type="hidden" name="image_id" id="image_id" value="0">
                    <div style="margin-bottom:10px;">
                        <img id="competitor-photo-preview" src="" style="max-width:100px; display:none;">
                    </div>
                    <button id="upload-photo-button" class="button" type="button">
                        <?php _e( 'Select Competitor Photo', 'bjj' ); ?>
                    </button>
                </td>
            </tr>
            <tr>
                <th><label for="mat_assignment"><?php _e( 'MAT Assignment', 'bjj' ); ?></label></th>
                <td>
                    <select name="mat_assignment" id="mat_assignment">
                        <option value=""><?php _e( 'Select MAT', 'bjj' ); ?></option>
                        <option value="MAT 1">MAT 1</option>
                        <option value="MAT 2">MAT 2</option>
                        <option value="MAT 3">MAT 3</option>
                    </select>
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Add Competitor', 'bjj' ); ?>">
        </p>
    </form>

    <hr>

    <h2><?php _e( 'Competitors List', 'bjj' ); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_competitor_assignments', 'bjj_competitor_assign_nonce' ); ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e( 'Academy Icon', 'bjj' ); ?></th>
                    <th><?php _e( 'Competitor Photo', 'bjj' ); ?></th>
                    <th><?php _e( 'First Name', 'bjj' ); ?></th>
                    <th><?php _e( 'Last Name', 'bjj' ); ?></th>
                    <th><?php _e( 'Phone', 'bjj' ); ?></th>
                    <th><?php _e( 'Email', 'bjj' ); ?></th>
                    <th><?php _e( 'Country', 'bjj' ); ?></th>
                    <th><?php _e( 'Belt', 'bjj' ); ?></th>
                    <th><?php _e( 'Academy/School', 'bjj' ); ?></th>
                    <th><?php _e( 'Competition Category', 'bjj' ); ?></th>
                    <th><?php _e( 'Age', 'bjj' ); ?></th>
                    <th><?php _e( 'Weight (lbs)', 'bjj' ); ?></th>
                    <th><?php _e( 'MAT Assignment', 'bjj' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( ! empty( $competitors ) ) : ?>
                    <?php foreach ( $competitors as $id => $comp ) : ?>
                        <tr>
                            <td>
                                <?php
                                $icon_url = '';
                                if ( ! empty( $comp['academy_icon_id'] ) ) {
                                    $icon_url = wp_get_attachment_url( $comp['academy_icon_id'] );
                                }
                                if ( $icon_url ) :
                                ?>
                                    <img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $comp['academy_id'] ); ?>" style="max-width:50px;">
                                <?php else : ?>
                                    <?php _e( 'No Icon', 'bjj' ); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $photo_url = '';
                                if ( ! empty( $comp['image_id'] ) ) {
                                    $photo_url = wp_get_attachment_url( $comp['image_id'] );
                                }
                                if ( $photo_url ) :
                                ?>
                                    <img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $comp['first_name'] ); ?>" style="max-width:50px;">
                                <?php else : ?>
                                    <?php _e( 'No Photo', 'bjj' ); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html( $comp['first_name'] ); ?></td>
                            <td><?php echo esc_html( $comp['last_name'] ); ?></td>
                            <td><?php echo esc_html( $comp['phone'] ); ?></td>
                            <td><?php echo esc_html( $comp['email'] ); ?></td>
                            <td><?php echo esc_html( $comp['country'] ); ?></td>
                            <td><?php echo esc_html( $comp['belt'] ); ?></td>
                            <td>
                                <?php 
                                $academy_name = '';
                                if ( ! empty( $comp['academy_id'] ) ) {
                                    $all_academies = get_option( 'bjj_academies', array() );
                                    if ( isset( $all_academies[ $comp['academy_id'] ] ) ) {
                                        $academy_name = $all_academies[ $comp['academy_id'] ]['name'];
                                    }
                                }
                                echo esc_html( $academy_name );
                                ?>
                            </td>
                            <td><?php echo esc_html( $comp['competition_category'] ); ?></td>
                            <td><?php echo esc_html( $comp['age'] ); ?></td>
                            <td><?php echo esc_html( $comp['weight'] ); ?></td>
                            <td>
                                <select name="mat_assignment[<?php echo intval( $id ); ?>]">
                                    <option value=""><?php _e( 'Select MAT', 'bjj' ); ?></option>
                                    <option value="MAT 1" <?php selected( ( isset( $comp['mat_assignment'] ) ? $comp['mat_assignment'] : '' ), 'MAT 1' ); ?>>MAT 1</option>
                                    <option value="MAT 2" <?php selected( ( isset( $comp['mat_assignment'] ) ? $comp['mat_assignment'] : '' ), 'MAT 2' ); ?>>MAT 2</option>
                                    <option value="MAT 3" <?php selected( ( isset( $comp['mat_assignment'] ) ? $comp['mat_assignment'] : '' ), 'MAT 3' ); ?>>MAT 3</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="13"><?php _e( 'No competitors found.', 'bjj' ); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <p>
            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Assignments', 'bjj' ); ?>">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($){
    var mediaUploader;

    // Competitor Photo Uploader
    $('#upload-photo-button').on('click', function(e) {
        e.preventDefault();
        if ( mediaUploader ) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e( 'Choose Competitor Photo', 'bjj' ); ?>',
            button: { text: '<?php _e( 'Choose Photo', 'bjj' ); ?>' },
            multiple: false
        });
        mediaUploader.on('select', function(){
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#image_id').val(attachment.id);
            if ( attachment.url ) {
                $('#competitor-photo-preview').attr('src', attachment.url).show();
            }
        });
        mediaUploader.open();
    });

    // Academy Icon Uploader
    var iconUploader;
    $('#upload-icon-button').on('click', function(e) {
        e.preventDefault();
        if ( iconUploader ) {
            iconUploader.open();
            return;
        }
        iconUploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e( 'Choose Academy Icon', 'bjj' ); ?>',
            button: { text: '<?php _e( 'Choose Icon', 'bjj' ); ?>' },
            multiple: false
        });
        iconUploader.on('select', function(){
            var attachment = iconUploader.state().get('selection').first().toJSON();
            $('#academy_icon_id').val(attachment.id);
            if ( attachment.url ) {
                $('#academy-icon-preview').attr('src', attachment.url).show();
            }
        });
        iconUploader.open();
    });

    // Dynamic Dropdowns for Athlete Type, Belt, and Competition Category.
    var adultBelts = ['White', 'Blue', 'Purple', 'Brown', 'Black'];
    var teenBelts  = ['White', 'Gray', 'Yellow', 'Green', 'Orange'];

    var adultCategories = ['Male GI - Adult', 'Female GI - Adult', 'Male NOGI - Adult', 'Female NOGI - Adult'];
    var teenCategories  = ['Boys GI - Teens', 'Girls GI - Teens', 'Boys NOGI - Teens', 'Girls NOGI - Teens'];

    function populateBeltAndCategory() {
        var athleteType = $('#athlete_type').val();
        var beltSelect = $('#belt');
        var categorySelect = $('#competition_category');

        beltSelect.empty();
        categorySelect.empty();

        beltSelect.append('<option value="">' + '<?php _e("Select Belt", "bjj"); ?>' + '</option>');
        categorySelect.append('<option value="">' + '<?php _e("Select Competition Category", "bjj"); ?>' + '</option>');

        if ( athleteType === 'Adult' ) {
            $.each(adultBelts, function(i, belt) {
                beltSelect.append('<option value="'+ belt +'">'+ belt +'</option>');
            });
            $.each(adultCategories, function(i, cat) {
                categorySelect.append('<option value="'+ cat +'">'+ cat +'</option>');
            });
        } else if ( athleteType === 'Teen' ) {
            $.each(teenBelts, function(i, belt) {
                beltSelect.append('<option value="'+ belt +'">'+ belt +'</option>');
            });
            $.each(teenCategories, function(i, cat) {
                categorySelect.append('<option value="'+ cat +'">'+ cat +'</option>');
            });
        }
    }

    // Populate on load and when athlete type changes.
    populateBeltAndCategory();
    $('#athlete_type').on('change', function(){
        populateBeltAndCategory();
    });
});
</script>
