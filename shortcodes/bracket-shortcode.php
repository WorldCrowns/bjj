bracket-shortcode.php
<div class="tm-shortcode-bracket">
    <h2><?php _e( 'Bracket', 'tournament-manager' ); ?></h2>
    <p><?php _e( 'Tournament brackets will be automatically generated based on the competitor data.', 'tournament-manager' ); ?></p>
    
    <div class="tm-bracket">
        <div class="bracket-round quarterfinals">
            <h3><?php _e( 'Quarter Finals', 'tournament-manager' ); ?></h3>
            <ul>
                <li>Match 1: Competitor A vs Competitor B</li>
                <li>Match 2: Competitor C vs Competitor D</li>
            </ul>
        </div>
        <div class="bracket-round semifinals">
            <h3><?php _e( 'Semi Finals', 'tournament-manager' ); ?></h3>
            <ul>
                <li>Match 3: Winner Match 1 vs Winner Match 2</li>
            </ul>
        </div>
        <div class="bracket-round finals">
            <h3><?php _e( 'Finals', 'tournament-manager' ); ?></h3>
            <ul>
                <li>Match 4: Winner Semi Final vs TBD</li>
            </ul>
        </div>
    </div>
</div>
