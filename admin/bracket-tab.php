<?php
/**
 * Bracket Tab (Admin)
 * Generates tournament brackets (Quarter Finals, Semi Finals, Finals).
 */
?>
<div class="bjj-tab-content">
    <h2><?php _e( 'Bracket', 'bjj' ); ?></h2>
    <p><?php _e( 'Tournament brackets are generated based on competitor data.', 'bjj' ); ?></p>
    <div class="bjj-bracket">
        <div class="bracket-round quarterfinals">
            <h3><?php _e( 'Quarter Finals', 'bjj' ); ?></h3>
            <ul>
                <li>Match 1: Competitor A vs Competitor B</li>
                <li>Match 2: Competitor C vs Competitor D</li>
            </ul>
        </div>
        <div class="bracket-round semifinals">
            <h3><?php _e( 'Semi Finals', 'bjj' ); ?></h3>
            <ul>
                <li>Match 3: Winner Match 1 vs Winner Match 2</li>
            </ul>
        </div>
        <div class="bracket-round finals">
            <h3><?php _e( 'Finals', 'bjj' ); ?></h3>
            <ul>
                <li>Match 4: Winner Semi Final vs TBD</li>
            </ul>
        </div>
    </div>
</div>
