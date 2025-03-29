<?php
/**
 * Academies Tab (Admin) - CRUD functionality
 */

$academies = get_option('bjj_academies', array());

// Process deletion.
if ( isset($_GET['action']) && $_GET['action'] === 'delete_academy' && isset($_GET['id']) ) {
    $delete_id = intval($_GET['id']);
    if ( isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'delete_academy_' . $delete_id) ) {
        if ( isset($academies[$delete_id]) ) {
            unset($academies[$delete_id]);
            update_option('bjj_academies', $academies);
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Academy deleted successfully!', 'bjj') . '</p></div>';
        }
    }
}

// Initialize editing variables.
$editing = false;
$editing_id = 0;
$edit_academy = array(
    'name'    => '',
    'icon_id' => 0,
);

// Process editing.
if ( isset($_GET['action']) && $_GET['action'] === 'edit_academy' && isset($_GET['id']) ) {
    $editing = true;
    $editing_id = intval($_GET['id']);
    if ( isset($academies[$editing_id]) ) {
        $edit_academy = $academies[$editing_id];
    }
}

// Process form submission for adding/updating academy.
if ( isset($_POST['bjj_academy_add_nonce']) && wp_verify_nonce($_POST['bjj_academy_add_nonce'], 'bjj_save_academy') ) {
    $new_academy = array(
       'name'    => sanitize_text_field($_POST['academy_name']),
       'icon_id' => isset($_POST['academy_icon_id']) ? intval($_POST['academy_icon_id']) : 0,
    );
    if ( isset($_POST['editing_id']) && !empty($_POST['editing_id']) ) {
        $edit_id = intval($_POST['editing_id']);
        $academies[$edit_id] = $new_academy;
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Academy updated successfully!', 'bjj') . '</p></div>';
    } else {
        $new_id = time() . rand(10, 99);
        $academies[$new_id] = $new_academy;
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Academy added successfully!', 'bjj') . '</p></div>';
    }
    update_option('bjj_academies', $academies);
    $editing = false;
    $edit_academy = array();
}
?>
<div class="bjj-tab-content">
    <h2><?php echo $editing ? __('Edit Academy/School','bjj') : __('Add New Academy/School','bjj'); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field('bjj_save_academy', 'bjj_academy_add_nonce'); ?>
        <?php if($editing): ?>
            <input type="hidden" name="editing_id" value="<?php echo intval($editing_id); ?>">
        <?php endif; ?>
        <table class="form-table">
            <tr>
                <th><label for="academy_name"><?php _e('Academy/School Name','bjj'); ?></label></th>
                <td><input type="text" name="academy_name" id="academy_name" value="<?php echo esc_attr(isset($edit_academy['name']) ? $edit_academy['name'] : ''); ?>" required></td>
            </tr>
            <tr>
                <th><label for="academy_icon_id"><?php _e('Academy Icon','bjj'); ?></label></th>
                <td>
                    <input type="hidden" name="academy_icon_id" id="academy_icon_id" value="<?php echo esc_attr(isset($edit_academy['icon_id']) ? $edit_academy['icon_id'] : 0); ?>">
                    <div style="margin-bottom:10px;">
                        <?php 
                        if(isset($edit_academy['icon_id']) && $edit_academy['icon_id']){
                            $icon_url = wp_get_attachment_url($edit_academy['icon_id']);
                            if($icon_url){
                                echo '<img id="academy-icon-preview" src="'.esc_url($icon_url).'" style="max-width:100px;">';
                            }
                        } else {
                            echo '<img id="academy-icon-preview" src="" style="max-width:100px; display:none;">';
                        }
                        ?>
                    </div>
                    <button id="upload-academy-icon-button" class="button" type="button">
                        <?php _e('Select Academy Icon','bjj'); ?>
                    </button>
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" class="button button-primary" value="<?php echo $editing ? esc_attr__('Update Academy','bjj') : esc_attr__('Add Academy','bjj'); ?>">
        </p>
    </form>

    <hr>

    <h2><?php _e('Existing Academies','bjj'); ?></h2>
    <?php if(!empty($academies)): ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Icon','bjj'); ?></th>
                    <th><?php _e('Name','bjj'); ?></th>
                    <th><?php _e('Actions','bjj'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($academies as $id => $academy): ?>
                    <tr>
                        <td>
                            <?php 
                            $icon_url = '';
                            if(!empty($academy['icon_id'])){
                                $icon_url = wp_get_attachment_url($academy['icon_id']);
                            }
                            if($icon_url):
                            ?>
                                <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($academy['name']); ?>" style="max-width:50px;">
                            <?php else: ?>
                                <?php _e('No Icon','bjj'); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($academy['name']); ?></td>
                        <td>
                            <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit_academy', 'id' => $id ), menu_page_url( 'bjj-academies', false ) ) ); ?>"><?php _e('Edit','bjj'); ?></a> |
                            <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'delete_academy', 'id' => $id, 'nonce' => wp_create_nonce('delete_academy_'.$id) ), menu_page_url( 'bjj-academies', false ) ) ); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this academy?','bjj'); ?>');"><?php _e('Delete','bjj'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><?php _e('No academies found.','bjj'); ?></p>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($){
    var academyIconUploader;
    $('#upload-academy-icon-button').on('click', function(e) {
        e.preventDefault();
        if ( academyIconUploader ) {
            academyIconUploader.open();
            return;
        }
        academyIconUploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e( 'Choose Academy Icon', 'bjj' ); ?>',
            button: { text: '<?php _e( 'Choose Icon', 'bjj' ); ?>' },
            multiple: false
        });
        academyIconUploader.on('select', function(){
            var attachment = academyIconUploader.state().get('selection').first().toJSON();
            $('#academy_icon_id').val(attachment.id);
            if ( attachment.url ) {
                $('#academy-icon-preview').attr('src', attachment.url).show();
            }
        });
        academyIconUploader.open();
    });
});
</script>
