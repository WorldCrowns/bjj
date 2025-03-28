competitors-shortcode.php

<div class="tm-shortcode-competitors">
    <h2><?php _e( 'Competitors', 'tournament-manager' ); ?></h2>
    <p><?php _e( 'Below is the list of competitors organized by category, belt, weight, etc.', 'tournament-manager' ); ?></p>
    <table class="tm-table">
        <thead>
            <tr>
                <th><?php _e( 'Picture', 'tournament-manager' ); ?></th>
                <th><?php _e( 'First Name', 'tournament-manager' ); ?></th>
                <th><?php _e( 'Last Name', 'tournament-manager' ); ?></th>
                <th><?php _e( 'Phone', 'tournament-manager' ); ?></th>
                <th><?php _e( 'Email', 'tournament-manager' ); ?></th>
                <th><?php _e( 'Country', 'tournament-manager' ); ?></th>
                <th><?php _e( 'Belt', 'tournament-manager' ); ?></th>
                <th><?php _e( 'Academy/School', 'tournament-manager' ); ?></th>
                <th><?php _e( 'Category', 'tournament-manager' ); ?></th>
                <th><?php _e( 'Age', 'tournament-manager' ); ?></th>
                <th><?php _e( 'MAT Assignment', 'tournament-manager' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Placeholder data; replace with dynamic competitor retrieval.
            $competitors = array(
                array(
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
                array(
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
            if ( ! empty( $competitors ) ) {
                foreach ( $competitors as $comp ) {
                    echo '<tr>';
                    echo '<td><img src="' . esc_url( $comp['image_url'] ) . '" alt="' . esc_attr( $comp['first_name'] ) . '" class="tm-competitor-img" /></td>';
                    echo '<td>' . esc_html( $comp['first_name'] ) . '</td>';
                    echo '<td>' . esc_html( $comp['last_name'] ) . '</td>';
                    echo '<td>' . esc_html( $comp['phone'] ) . '</td>';
                    echo '<td>' . esc_html( $comp['email'] ) . '</td>';
                    echo '<td>' . esc_html( $comp['country'] ) . '</td>';
                    echo '<td>' . esc_html( $comp['belt'] ) . '</td>';
                    echo '<td>';
                    if ( ! empty( $comp['academy_icon'] ) ) {
                        echo '<img src="' . esc_url( $comp['academy_icon'] ) . '" alt="' . esc_attr( $comp['academy'] ) . '" class="tm-academy-icon" /> ';
                    }
                    echo esc_html( $comp['academy'] );
                    echo '</td>';
                    echo '<td>' . esc_html( $comp['category'] ) . '</td>';
                    echo '<td>' . esc_html( $comp['age'] ) . '</td>';
                    echo '<td>';
                    ?>
                    <select name="mat_assignment">
                        <option value=""><?php _e( 'Select MAT', 'tournament-manager' ); ?></option>
                        <option value="mat1">MAT 1</option>
                        <option value="mat2">MAT 2</option>
                        <option value="mat3">MAT 3</option>
                    </select>
                    <?php
                    echo '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>
