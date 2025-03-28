order-of-fights-tab.php
<?php
/**
 * Order of Fights Tab
 * Displays the order of fights with drag and drop functionality.
 * Replace the placeholder data with your dynamic fight order list.
 */
?>
<div class="tm-tab-content">
    <h2><?php _e( 'Order of Fights', 'tournament-manager' ); ?></h2>
    <p><?php _e( 'Drag and drop to rearrange the order of fights.', 'tournament-manager' ); ?></p>
    
    <ul id="fight-order-list">
        <?php
        // Placeholder: Replace with dynamic fight order list.
        $fights = array(
            'Fight 1: John Doe vs Jane Smith',
            'Fight 2: Competitor C vs Competitor D',
            'Fight 3: Competitor E vs Competitor F'
        );
        foreach ( $fights as $fight ) {
            echo '<li class="ui-state-default">' . esc_html( $fight ) . '</li>';
        }
        ?>
    </ul>
    <button id="save-fight-order" class="button button-primary"><?php _e( 'Save Order', 'tournament-manager' ); ?></button>
</div>
