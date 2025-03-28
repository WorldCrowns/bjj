<div class="bjj-shortcode-order-of-fights">
    <h2><?php _e( 'Order of Fights', 'bjj' ); ?></h2>
    <p><?php _e( 'Drag and drop to rearrange the order of fights.', 'bjj' ); ?></p>
    <ul id="fight-order-list">
        <?php
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
    <button id="save-fight-order" class="button button-primary"><?php _e( 'Save Order', 'bjj' ); ?></button>
</div>
