<?php
/**
 * Competitors Tab (Admin) - CRUD functionality
 */

$competitors = get_option('bjj_competitors', array());

// Process deletion request.
if ( isset($_GET['action']) && $_GET['action'] === 'delete_competitor' && isset($_GET['id']) ) {
    $delete_id = intval($_GET['id']);
    if ( isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'delete_competitor_' . $delete_id) ) {
        if ( isset($competitors[$delete_id]) ) {
            unset($competitors[$delete_id]);
            update_option('bjj_competitors', $competitors);
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Competitor deleted successfully!', 'bjj') . '</p></div>';
        }
    }
}

// Initialize editing variables.
$editing = false;
$editing_id = 0;
$edit_data = array(
    'athlete_type'         => '',
    'first_name'           => '',
    'last_name'            => '',
    'phone'                => '',
    'email'                => '',
    'country'              => '',
    'belt'                 => '',
    'academy_id'           => '',
    'competition_category' => '',
    'age'                  => '',
    'weight'               => '',
    'image_id'             => '',
    'mat_assignment'       => '',
);

// Process "edit" action.
if ( isset($_GET['action']) && $_GET['action'] === 'edit_competitor' && isset($_GET['id']) ) {
    $editing = true;
    $editing_id = intval($_GET['id']);
    if ( isset($competitors[$editing_id]) ) {
        $edit_data = $competitors[$editing_id];
    }
}

// Process form submission for adding/updating competitor.
if ( isset($_POST['bjj_competitor_add_nonce']) && wp_verify_nonce($_POST['bjj_competitor_add_nonce'], 'bjj_save_competitor') ) {
    $new_data = array(
        'athlete_type'         => sanitize_text_field($_POST['athlete_type']),
        'first_name'           => sanitize_text_field($_POST['first_name']),
        'last_name'            => sanitize_text_field($_POST['last_name']),
        'phone'                => sanitize_text_field($_POST['phone']),
        'email'                => sanitize_email($_POST['email']),
        'country'              => sanitize_text_field($_POST['country']),
        'belt'                 => sanitize_text_field($_POST['belt']),
        'academy_id'           => isset($_POST['academy_id']) ? intval($_POST['academy_id']) : 0,
        'competition_category' => sanitize_text_field($_POST['competition_category']),
        'age'                  => intval($_POST['age']),
        'weight'               => sanitize_text_field($_POST['weight']),
        'image_id'             => isset($_POST['image_id']) ? intval($_POST['image_id']) : 0,
        'mat_assignment'       => sanitize_text_field($_POST['mat_assignment']),
    );

    if ( isset($_POST['editing_id']) && !empty($_POST['editing_id']) ) {
        $edit_id = intval($_POST['editing_id']);
        $competitors[$edit_id] = $new_data;
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Competitor updated successfully!', 'bjj') . '</p></div>';
    } else {
        $new_id = time() . rand(10, 99);
        $competitors[$new_id] = $new_data;
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Competitor added successfully!', 'bjj') . '</p></div>';
    }
    update_option('bjj_competitors', $competitors);
    $editing = false;
    $edit_data = array(); // Reset editing data.
}

// Retrieve academies and weight options.
$academies = get_option('bjj_academies', array());
$weight_options = get_option('bjj_weight_options', array());

// List of all countries.
$countries = array("Afghanistan", "Albania", "Algeria", /* ... full list ... */ "Zimbabwe");
?>
<div class="bjj-tab-content">
    <h2><?php echo $editing ? __('Edit Competitor','bjj') : __('Add New Competitor','bjj'); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field('bjj_save_competitor', 'bjj_competitor_add_nonce'); ?>
        <?php if($editing): ?>
            <input type="hidden" name="editing_id" value="<?php echo intval($editing_id); ?>">
        <?php endif; ?>
        <table class="form-table">
            <tr>
                <th><label for="athlete_type"><?php _e('Athlete Type','bjj'); ?></label></th>
                <td>
                    <select name="athlete_type" id="athlete_type">
                        <option value="Adult" <?php selected(isset($edit_data['athlete_type']) ? $edit_data['athlete_type'] : '', 'Adult'); ?>><?php _e('Adult','bjj'); ?></option>
                        <option value="Teen" <?php selected(isset($edit_data['athlete_type']) ? $edit_data['athlete_type'] : '', 'Teen'); ?>><?php _e('Teen','bjj'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="first_name"><?php _e('First Name','bjj'); ?></label></th>
                <td><input type="text" name="first_name" id="first_name" value="<?php echo esc_attr(isset($edit_data['first_name']) ? $edit_data['first_name'] : ''); ?>" required></td>
            </tr>
            <tr>
                <th><label for="last_name"><?php _e('Last Name','bjj'); ?></label></th>
                <td><input type="text" name="last_name" id="last_name" value="<?php echo esc_attr(isset($edit_data['last_name']) ? $edit_data['last_name'] : ''); ?>" required></td>
            </tr>
            <tr>
                <th><label for="phone"><?php _e('Phone','bjj'); ?></label></th>
                <td><input type="text" name="phone" id="phone" value="<?php echo esc_attr(isset($edit_data['phone']) ? $edit_data['phone'] : ''); ?>"></td>
            </tr>
            <tr>
                <th><label for="email"><?php _e('Email','bjj'); ?></label></th>
                <td><input type="email" name="email" id="email" value="<?php echo esc_attr(isset($edit_data['email']) ? $edit_data['email'] : ''); ?>"></td>
            </tr>
            <tr>
                <th><label for="country"><?php _e('Country','bjj'); ?></label></th>
                <td>
                    <select name="country" id="country">
                        <option value=""><?php _e('Select a Country','bjj'); ?></option>
                        <?php foreach($countries as $country): ?>
                            <option value="<?php echo esc_attr($country); ?>" <?php selected(isset($edit_data['country']) ? $edit_data['country'] : '', $country); ?>><?php echo esc_html($country); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <!-- Belt and Competition Category dropdowns populated via JS -->
            <tr>
                <th><label for="belt"><?php _e('Belt','bjj'); ?></label></th>
                <td>
                    <select name="belt" id="belt">
                        <!-- Options populated by JS -->
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="competition_category"><?php _e('Competition Category','bjj'); ?></label></th>
                <td>
                    <select name="competition_category" id="competition_category">
                        <!-- Options populated by JS -->
                    </select>
                </td>
            </tr>
            <!-- Academy dropdown from academies-tab -->
            <tr>
                <th><label for="academy_id"><?php _e('Academy/School','bjj'); ?></label></th>
                <td>
                    <select name="academy_id" id="academy_id">
                        <option value=""><?php _e('Select an Academy/School','bjj'); ?></option>
                        <?php 
                        if(!empty($academies) && is_array($academies)):
                           foreach($academies as $a_id => $academy):
                              ?>
                              <option value="<?php echo esc_attr($a_id); ?>" <?php selected(isset($edit_data['academy_id']) ? $edit_data['academy_id'] : '', $a_id); ?>><?php echo esc_html($academy['name']); ?></option>
                           <?php endforeach;
                        endif;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="age"><?php _e('Age','bjj'); ?></label></th>
                <td><input type="number" name="age" id="age" value="<?php echo esc_attr(isset($edit_data['age']) ? $edit_data['age'] : ''); ?>"></td>
            </tr>
            <tr>
                <th><label for="weight"><?php _e('Weight (lbs)','bjj'); ?></label></th>
                <td>
                    <select name="weight" id="weight">
                        <option value=""><?php _e('Select Weight','bjj'); ?></option>
                        <?php 
                        if(!empty($weight_options) && is_array($weight_options)):
                           foreach($weight_options as $w): ?>
                              <option value="<?php echo esc_attr($w); ?>" <?php selected(isset($edit_data['weight']) ? $edit_data['weight'] : '', $w); ?>><?php echo esc_html($w); ?></option>
                           <?php endforeach;
                        endif;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="image_id"><?php _e('Competitor Photo','bjj'); ?></label></th>
                <td>
                    <input type="hidden" name="image_id" id="image_id" value="<?php echo esc_attr(isset($edit_data['image_id']) ? $edit_data['image_id'] : 0); ?>">
                    <div style="margin-bottom:10px;">
                        <?php 
                        if ( isset($edit_data['image_id']) && $edit_data['image_id'] ) {
                            $img_url = wp_get_attachment_url($edit_data['image_id']);
                            if($img_url){
                                echo '<img id="competitor-photo-preview" src="'.esc_url($img_url).'" style="max-width:100px;">';
                            }
                        } else {
                            echo '<img id="competitor-photo-preview" src="" style="max-width:100px; display:none;">';
                        }
                        ?>
                    </div>
                    <button id="upload-photo-button" class="button" type="button"><?php _e('Select Competitor Photo','bjj'); ?></button>
                </td>
            </tr>
            <tr>
                <th><label for="mat_assignment"><?php _e('MAT Assignment','bjj'); ?></label></th>
                <td>
                    <select name="mat_assignment" id="mat_assignment">
                        <option value=""><?php _e('Select MAT','bjj'); ?></option>
                        <option value="MAT 1" <?php selected(isset($edit_data['mat_assignment']) ? $edit_data['mat_assignment'] : '', 'MAT 1'); ?>>MAT 1</option>
                        <option value="MAT 2" <?php selected(isset($edit_data['mat_assignment']) ? $edit_data['mat_assignment'] : '', 'MAT 2'); ?>>MAT 2</option>
                        <option value="MAT 3" <?php selected(isset($edit_data['mat_assignment']) ? $edit_data['mat_assignment'] : '', 'MAT 3'); ?>>MAT 3</option>
                    </select>
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" class="button button-primary" value="<?php echo $editing ? esc_attr__('Update Competitor','bjj') : esc_attr__('Add Competitor','bjj'); ?>">
        </p>
    </form>

    <hr>

    <h2><?php _e('Competitors List','bjj'); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field('bjj_save_competitor_assignments', 'bjj_competitor_assign_nonce'); ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Academy Icon','bjj'); ?></th>
                    <th><?php _e('Competitor Photo','bjj'); ?></th>
                    <th><?php _e('First Name','bjj'); ?></th>
                    <th><?php _e('Last Name','bjj'); ?></th>
                    <th><?php _e('Phone','bjj'); ?></th>
                    <th><?php _e('Email','bjj'); ?></th>
                    <th><?php _e('Country','bjj'); ?></th>
                    <th><?php _e('Belt','bjj'); ?></th>
                    <th><?php _e('Academy/School','bjj'); ?></th>
                    <th><?php _e('Competition Category','bjj'); ?></th>
                    <th><?php _e('Age','bjj'); ?></th>
                    <th><?php _e('Weight (lbs)','bjj'); ?></th>
                    <th><?php _e('MAT Assignment','bjj'); ?></th>
                    <th><?php _e('Actions','bjj'); ?></th>
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
                            <td><?php echo esc_html( $comp['mat_assignment'] ); ?></td>
                            <td>
                                <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit_competitor', 'id' => $id ), menu_page_url( 'bjj', false ) ) ); ?>"><?php _e('Edit', 'bjj'); ?></a> |
                                <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'delete_competitor', 'id' => $id, 'nonce' => wp_create_nonce('delete_competitor_'.$id) ), menu_page_url( 'bjj', false ) ) ); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this competitor?','bjj'); ?>');"><?php _e('Delete', 'bjj'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="14"><?php _e( 'No competitors found.', 'bjj' ); ?></td>
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

    populateBeltAndCategory();
    $('#athlete_type').on('change', function(){
        populateBeltAndCategory();
    });
});
</script>
