<?php
/**
 * Competitors Tab (Admin)
 * Displays competitor data, assigns MAT, and saves the assignments.
 */

// Handle form submission for competitor assignments.
if ( isset( $_POST['bjj_competitor_nonce'] ) && wp_verify_nonce( $_POST['bjj_competitor_nonce'], 'bjj_save_competitor_assignments' ) ) {
    // Example approach: store assignments in an option (array of competitor => mat).
    $assignments = array();
    if ( ! empty( $_POST['mat_assignment'] ) && is_array( $_POST['mat_assignment'] ) ) {
        foreach ( $_POST['mat_assignment'] as $competitor_id => $mat ) {
            // Sanitize competitor_id and mat
            $competitor_id = intval( $competitor_id );
            $mat = sanitize_text_field( $mat );
            $assignments[ $competitor_id ] = $mat;
        }
    }
    update_option( 'bjj_competitor_assignments', $assignments );
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Competitor assignments saved successfully!', 'bjj' ); ?></p>
    </div>
    <?php
}
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Competitors', 'bjj' ); ?></h2>
    <p><?php _e( 'Below is the list of competitors organized by category, belt, weight, etc.', 'bjj' ); ?></p>

    <?php
    // Example competitor data (you’ll replace this with real data).
    $competitors = array(
        1 => array(
            'image_url'    => 'https://via.placeholder.com/50',
            'first_name'   => 'John',
            'last_name'    => 'Doe',
            'phone'        => '1234567890',
            'email'        => 'john@example.com',
            'country'      => 'USA',
            'belt'         => 'Black',
            'academy'      => 'Alpha Academy',
            'academy_icon' => 'https://via.placeholder.com/20',
            'category'     => 'Senior',
            'age'          => 28,
        ),
        2 => array(
            'image_url'    => 'https://via.placeholder.com/50',
            'first_name'   => 'Jane',
            'last_name'    => 'Smith',
            'phone'        => '0987654321',
            'email'        => 'jane@example.com',
            'country'      => 'Canada',
            'belt'         => 'Brown',
            'academy'      => 'Beta School',
            'academy_icon' => 'https://via.placeholder.com/20',
            'category'     => 'Junior',
            'age'          => 22,
        ),
    );

    // Get previously saved assignments (if any).
    $saved_assignments = get_option( 'bjj_competitor_assignments', array() );
    ?>

    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_competitor_assignments', 'bjj_competitor_nonce' ); ?>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e( 'Picture', 'bjj' ); ?></th>
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
                <?php foreach ( $competitors as $id => $comp ) : ?>
                    <tr>
                        <td><img src="<?php echo esc_url( $comp['image_url'] ); ?>" alt="<?php echo esc_attr( $comp['first_name'] ); ?>" class="bjj-competitor-img" /></td>
                        <td><?php echo esc_html( $comp['first_name'] ); ?></td>
                        <td><?php echo esc_html( $comp['last_name'] ); ?></td>
                        <td><?php echo esc_html( $comp['phone'] ); ?></td>
                        <td><?php echo esc_html( $comp['email'] ); ?></td>
                        <td><?php echo esc_html( $comp['country'] ); ?></td>
                        <td><?php echo esc_html( $comp['belt'] ); ?></td>
                        <td>
                            <?php if ( ! empty( $comp['academy_icon'] ) ) : ?>
                                <img src="<?php echo esc_url( $comp['academy_icon'] ); ?>" alt="<?php echo esc_attr( $comp['academy'] ); ?>" class="bjj-academy-icon" />
                            <?php endif; ?>
                            <?php echo esc_html( $comp['academy'] ); ?>
                        </td>
                        <td><?php echo esc_html( $comp['category'] ); ?></td>
                        <td><?php echo esc_html( $comp['age'] ); ?></td>
                        <td>
                            <select name="mat_assignment[<?php echo intval( $id ); ?>]">
                                <option value=""><?php _e( 'Select MAT', 'bjj' ); ?></option>
                                <option value="MAT 1" <?php selected( ( isset( $saved_assignments[ $id ] ) ? $saved_assignments[ $id ] : '' ), 'MAT 1' ); ?>>MAT 1</option>
                                <option value="MAT 2" <?php selected( ( isset( $saved_assignments[ $id ] ) ? $saved_assignments[ $id ] : '' ), 'MAT 2' ); ?>>MAT 2</option>
                                <option value="MAT 3" <?php selected( ( isset( $saved_assignments[ $id ] ) ? $saved_assignments[ $id ] : '' ), 'MAT 3' ); ?>>MAT 3</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>
            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Assignments', 'bjj' ); ?>">
        </p>
    </form>
</div>
