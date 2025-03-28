<?php
/**
 * Competitors Tab (Admin)
 * Allows manual entry of competitor data (including using the media uploader for the competitor photo)
 * and displays the competitor list.
 */

// Process form submission for adding a new competitor.
if ( isset( $_POST['bjj_competitor_add_nonce'] ) && wp_verify_nonce( $_POST['bjj_competitor_add_nonce'], 'bjj_save_competitor' ) ) {
    // Retrieve existing competitors from the options (or initialize as an empty array).
    $competitors = get_option( 'bjj_competitors', array() );
    
    // Build the new competitor entry from POST data.
    $new_competitor = array(
        'first_name'     => sanitize_text_field( $_POST['first_name'] ),
        'last_name'      => sanitize_text_field( $_POST['last_name'] ),
        'phone'          => sanitize_text_field( $_POST['phone'] ),
        'email'          => sanitize_email( $_POST['email'] ),
        'country'        => sanitize_text_field( $_POST['country'] ),
        'belt'           => sanitize_text_field( $_POST['belt'] ),
        'academy'        => sanitize_text_field( $_POST['academy'] ),
        'academy_icon'   => esc_url_raw( $_POST['academy_icon'] ),
        'category'       => sanitize_text_field( $_POST['category'] ),
        'age'            => intval( $_POST['age'] ),
        'image_url'      => esc_url_raw( $_POST['image_url'] ), // Competitor photo URL selected via Media Library.
        'mat_assignment' => sanitize_text_field( $_POST['mat_assignment'] ),
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
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Add New Competitor', 'bjj' ); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_competitor', 'bjj_competitor_add_nonce' ); ?>
        <table class="form-table">
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
                <td><input type="text" name="country" id="country"></td>
            </tr>
            <tr>
                <th><label for="belt"><?php _e( 'Belt', 'bjj' ); ?></label></th>
                <td><input type="text" name="belt" id="belt"></td>
            </tr>
            <tr>
                <th><label for="academy"><?php _e( 'Academy/School', 'bjj' ); ?></label></th>
                <td><input type="text" name="academy" id="academy"></td>
            </tr>
            <tr>
                <th><label for="academy_icon"><?php _e( 'Academy Icon URL', 'bjj' ); ?></label></th>
                <td><input type="url" name="academy_icon" id="academy_icon" placeholder="http://"></td>
            </tr>
            <tr>
                <th><label for="category"><?php _e( 'Category', 'bjj' ); ?></label></th>
                <td><input type="text" name="category" id="category"></td>
            </tr>
            <tr>
                <th><label for="age"><?php _e( 'Age', 'bjj' ); ?></label></th>
                <td><input type="number" name="age" id="age"></td>
            </tr>
            <tr>
                <th><label for="image_url"><?php _e( 'Competitor Photo', 'bjj' ); ?></label></th>
                <td>
                    <!-- Readonly text field to hold the image URL -->
                    <input type="text" name="image_url" id="image_url" placeholder="<?php _e( 'Select a photo from the media library', 'bjj' ); ?>" readonly>
                    <button id="upload-photo-button" class="button"><?php _e( 'Upload Photo', 'bjj' ); ?></button>
                    <br>
                    <!-- Image preview -->
                    <img id="competitor-photo-preview" src="" style="max-width:100px; margin-top:10px;">
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
                    <th><?php _e( 'Photo', 'bjj' ); ?></th>
                    <th><?php _e( 'First Name', 'bjj' ); ?></th>
                    <th><?php _e( 'Last Name', 'bjj' ); ?></th>
                    <th><?php _e( 'Phone', 'bjj' ); ?></th>
                    <th><?php _e( 'Email', 'bjj' ); ?></th>
                    <th><?php _e( 'Country', 'bjj' ); ?></th>
                    <th><?php _e( 'Belt', 'bjj' ); ?></th>
                    <th><?php _e( 'Academy/School', 'bjj' ); ?></th>
                    <th><?php _e( 'Category', 'bjj' ); ?></th>
                    <th><?php _e( 'Age', 'bjj' ); ?></th>
                    <th><?php _e( 'MAT Assignment', 'bjj' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( ! empty( $competitors ) ) : ?>
                    <?php foreach ( $competitors as $id => $comp ) : ?>
                        <tr>
                            <td>
                                <?php if ( ! empty( $comp['image_url'] ) ) : ?>
                                    <img src="<?php echo esc_url( $comp['image_url'] ); ?>" alt="<?php echo esc_attr( $comp['first_name'] ); ?>" style="max-width:50px;">
                                <?php else: ?>
                                    <?php _e( 'No Photo', 'bjj' ); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html( $comp['first_name'] ); ?></td>
                            <td><?php echo esc_html( $comp['last_name'] ); ?></td>
                            <td><?php echo esc_html( $comp['phone'] ); ?></td>
                            <td><?php echo esc_html( $comp['email'] ); ?></td>
                            <td><?php echo esc_html( $comp['country'] ); ?></td>
                            <td><?php echo esc_html( $comp['belt'] ); ?></td>
                            <td><?php echo esc_html( $comp['academy'] ); ?></td>
                            <td><?php echo esc_html( $comp['category'] ); ?></td>
                            <td><?php echo esc_html( $comp['age'] ); ?></td>
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
                        <td colspan="11"><?php _e( 'No competitors found.', 'bjj' ); ?></td>
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
    $('#upload-photo-button').on('click', function(e) {
        e.preventDefault();
        // If the uploader object has already been created, reopen the dialog.
        if ( mediaUploader ) {
            mediaUploader.open();
            return;
        }
        // Create a new media uploader.
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e( 'Choose Competitor Photo', 'bjj' ); ?>',
            button: {
                text: '<?php _e( 'Choose Photo', 'bjj' ); ?>'
            },
            multiple: false
        });
        // When a file is selected, grab its URL and set it as the value of our input field, and update the preview.
        mediaUploader.on('select', function(){
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#image_url').val(attachment.url);
            $('#competitor-photo-preview').attr('src', attachment.url);
        });
        // Open the uploader dialog.
        mediaUploader.open();
    });
});
</script>
