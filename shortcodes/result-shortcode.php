result-shortcode.php

<div class="tm-shortcode-result">
    <h2><?php _e( 'Enter Results', 'tournament-manager' ); ?></h2>
    <p><?php _e( 'Select the match and update the result.', 'tournament-manager' ); ?></p>
    
    <form method="post" action="">
        <?php wp_nonce_field( 'tm_save_result', 'tm_result_nonce' ); ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="match"><?php _e( 'Match', 'tournament-manager' ); ?></label></th>
                <td>
                    <select name="match" id="match">
                        <option value=""><?php _e( 'Select a match', 'tournament-manager' ); ?></option>
                        <option value="match1">Match 1: John Doe vs Jane Smith</option>
                        <!-- Populate dynamically -->
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="result"><?php _e( 'Result', 'tournament-manager' ); ?></label></th>
                <td>
                    <select name="result" id="result">
                        <option value=""><?php _e( 'Select result', 'tournament-manager' ); ?></option>
                        <option value="win">Win</option>
                        <option value="lose">Lose</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php submit_button( __( 'Save Result', 'tournament-manager' ) ); ?>
    </form>
    <div class="academy-wins">
        <h3><?php _e( 'Academy Wins Count', 'tournament-manager' ); ?></h3>
        <p><?php _e( 'Display which academy/school has the most wins.', 'tournament-manager' ); ?></p>
        <ul>
            <li><img src="https://via.placeholder.com/20" alt="Alpha Academy" class="tm-academy-icon"> Alpha Academy: 3 wins</li>
            <li><img src="https://via.placeholder.com/20" alt="Beta School" class="tm-academy-icon"> Beta School: 2 wins</li>
        </ul>
    </div>
</div>
