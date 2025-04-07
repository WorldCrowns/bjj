// Enqueue necessary JavaScript and localize AJAX data
function bjj_competition_enqueue_scripts() {
    wp_enqueue_script('bjj-competition-script', plugin_dir_url(__FILE__) . 'bjj-competition-script.js', array('jquery'), null, true);

    wp_localize_script('bjj-competition-script', 'bjjCompetitionAjax', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('bjj_competition_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'bjj_competition_enqueue_scripts');

// Handle Academy Form AJAX submission
add_action('wp_ajax_bjj_add_academy', 'bjj_add_academy_callback');
function bjj_add_academy_callback() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'bjj_competition_nonce')) {
        wp_send_json_error(array('message' => 'Nonce verification failed.'));
        exit;
    }

    // Process form data
    $academy_name    = sanitize_text_field($_POST['name']);
    $main_coach      = sanitize_text_field($_POST['main_coach']);
    $address         = sanitize_text_field($_POST['address']);
    $email           = sanitize_email($_POST['email']);
    $phone           = sanitize_text_field($_POST['phone']);
    $affiliation     = sanitize_text_field($_POST['affiliation']);
    $icon            = sanitize_text_field($_POST['icon']);

    // Insert academy data into your database
    $academy_id = wp_insert_post(array(
        'post_title'   => $academy_name,
        'post_content' => $address,
        'post_type'    => 'academy', // Your custom post type for academies
        'post_status'  => 'publish',
    ));

    // Attach custom fields if necessary
    update_post_meta($academy_id, '_main_coach', $main_coach);
    update_post_meta($academy_id, '_email', $email);
    update_post_meta($academy_id, '_phone', $phone);
    update_post_meta($academy_id, '_affiliation', $affiliation);
    update_post_meta($academy_id, '_icon', $icon);

    // Return success response
    wp_send_json_success(array('message' => 'Academy added successfully.'));
}

// Handle Category Form AJAX submission
add_action('wp_ajax_bjj_add_category', 'bjj_add_category_callback');
function bjj_add_category_callback() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'bjj_competition_nonce')) {
        wp_send_json_error(array('message' => 'Nonce verification failed.'));
        exit;
    }

    // Process form data
    $category_name = sanitize_text_field($_POST['category_name']);
    $belt_division = sanitize_text_field($_POST['belt_division']);

    // Insert category data into your database
    $category_id = wp_insert_term($category_name, 'category', array(
        'description' => $belt_division,
        'slug'        => sanitize_title($category_name),
    ));

    // Return success response
    if (!is_wp_error($category_id)) {
        wp_send_json_success(array('message' => 'Category added successfully.'));
    } else {
        wp_send_json_error(array('message' => 'Error adding category.'));
    }
}
