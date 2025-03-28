<?php
/**
 * Live Tab (Admin)
 * Shows a live queue of upcoming matches. Intended for display on a TV.
 */
?>
<div class="bjj-tab-content">
    <h2><?php _e( 'Live Queue', 'bjj' ); ?></h2>
    <p><?php _e( 'Upcoming matches will be displayed here.', 'bjj' ); ?></p>
    <div id="live-queue">
        <ul>
            <li>Next Match: John Doe vs Jane Smith</li>
            <li>Following: Competitor C vs Competitor D</li>
        </ul>
    </div>
    <button id="refresh-live" class="button"><?php _e( 'Refresh Now', 'bjj' ); ?></button>
</div>
