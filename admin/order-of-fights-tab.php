<?php
/**
 * Order of Fights Tab (Admin)
 * Demonstrates a dynamic drag-and-drop list with saved order.
 */

// 1. Process Form Submission
if ( isset( $_POST['bjj_order_nonce'] ) && wp_verify_nonce( $_POST['bjj_order_nonce'], 'bjj_save_fight_order' ) ) {
    // The hidden input field 'fight_order_list' contains a comma-separated list of fight IDs.
    $order_data = isset( $_POST['fight_order_list'] ) ? sanitize_text_field( $_POST['fight_order_list'] ) : '';
    // Example: "fight1,fight2,fight3"
    $order_array = array_filter( explode( ',', $order_data ) );

    // Save this order in an option.
    update_option( 'bjj_fight_order', $order_array );

    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Order of fights saved successfully!', 'bjj' ) . '</p></div>';
}

// 2. Retrieve Stored Order
$stored_order = get_option( 'bjj_fight_order', array() );

// 3. Get the list of fights
// In a real scenario, you might build this array from competitor data or a "matches" list.
$all_fights = array(
    'fight1' => 'Fight 1: John Doe vs Jane Smith',
    'fight2' => 'Fight 2: Competitor C vs Competitor D',
    'fight3' => 'Fight 3: Competitor E vs Competitor F',
);

// 4. Merge the stored order with any new or missing fights
$ordered_fights = array();

// First, add any fights in the stored order (in that order)
foreach ( $stored_order as $fight_id ) {
    if ( isset( $all_fights[ $fight_id ] ) ) {
        $ordered_fights[ $fight_id ] = $all_fights[ $fight_id ];
    }
}

// Then, add any fights not yet in the stored order
foreach ( $all_fights as $fight_id => $fight_label ) {
    if ( ! isset( $ordered_fights[ $fight_id ] ) ) {
        $ordered_fights[ $fight_id ] = $fight_label;
    }
}
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Order of Fights', 'bjj' ); ?></h2>
    <p><?php _e( 'Drag and drop to rearrange the order of fights, then click Save Order.', 'bjj' ); ?></p>

    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_fight_order', 'bjj_order_nonce' ); ?>

        <!-- The sortable list -->
        <ul id="fight-order-list" style="list-style: none; padding-left: 0;">
            <?php foreach ( $ordered_fights as $fight_id => $fight_label ) : ?>
                <li class="ui-state-default" data-id="<?php echo esc_attr( $fight_id ); ?>"
                    style="margin: 5px 0; padding: 10px; background: #f7f7f7; border: 1px solid #ddd; cursor: move;">
                    <?php echo esc_html( $fight_label ); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Hidden input to store the order on form submit -->
        <input type="hidden" name="fight_order_list" id="fight_order_list_input" value="" />

        <p>
            <button type="submit" class="button button-primary"><?php _e( 'Save Order', 'bjj' ); ?></button>
        </p>
    </form>
</div>
