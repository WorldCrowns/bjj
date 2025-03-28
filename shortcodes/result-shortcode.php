<div class="bjj-shortcode-result">
    <h2><?php _e( 'Enter Results', 'bjj' ); ?></h2>
    <p><?php _e( 'Select the match and update the result.', 'bjj' ); ?></p>
    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_result', 'bjj_result_nonce' ); ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="match"><?php _e( 'Match', 'bjj' ); ?></label></th>
                <td>
                    <select name="match" id="match">
                        <option value=""><?php _e( 'Select a match', 'bjj' ); ?></option>
                        <option value="match1">Match 1: John Doe vs Jane Smith</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="result"><?php _e( 'Result', 'bjj' ); ?></label></th>
                <td>
                    <select name="result" id="result">
                        <option value=""><?php _e( 'Select result', 'bjj' ); ?></option>
                        <option value="win">Win</option>
                        <option value="lose">Lose</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php submit_button( __( 'Save Result', 'bjj' ) ); ?>
    </form>
    <div class="academy-wins">
        <h3><?php _e( 'Academy Wins Count', 'bjj' ); ?></h3>
        <p><?php _e( 'Academies with the most wins:', 'bjj' ); ?></p>
        <ul>
            <li><img src="https://via.placeholder.com/20" alt="Alpha Academy" class="bjj-academy-icon"> Alpha Academy: 3 wins</li>
            <li><img src="https://via.placeholder.com/20" alt="Beta School" class="bjj-academy-icon"> Beta School: 2 wins</li>
        </ul>
    </div>
</div>
