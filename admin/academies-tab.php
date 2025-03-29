academies-tab.php
<?php
/**
 * Academies Tab (Admin)
 * Allows the admin to add and manage Academy/School entries.
 * Each academy includes a name and an icon (selected via the Media Library).
 */

// Process form submission for adding a new academy.
if ( isset($_POST['bjj_academy_add_nonce']) && wp_verify_nonce($_POST['bjj_academy_add_nonce'], 'bjj_save_academy') ) {
    $academies = get_option('bjj_academies', array());
    $new_academy = array(
       'name'    => sanitize_text_field($_POST['academy_name']),
       'icon_id' => isset($_POST['academy_icon_id']) ? intval($_POST['academy_icon_id']) : 0,
    );
    // Generate a unique ID for the academy.
    $new_id = time() . rand(10,99);
    $academies[$new_id] = $new_academy;
    update_option('bjj_academies', $academies);
    echo '<div class="notice notice-success is-dismissible"><p>' . __('Academy added successfully!', 'bjj') . '</p></div>';
}

$academies = get_option('bjj_academies', array());
?>

<div class="bjj-tab-content">
  <h2><?php _e('Add New Academy/School', 'bjj'); ?></h2>
  <form method="post" action="">
      <?php wp_nonce_field('bjj_save_academy', 'bjj_academy_add_nonce'); ?>
      <table class="form-table">
          <tr>
              <th><label for="academy_name"><?php _e('Academy/School Name', 'bjj'); ?></label></th>
              <td><input type="text" name="academy_name" id="academy_name" required></td>
          </tr>
          <tr>
              <th><label for="academy_icon_id"><?php _e('Academy Icon', 'bjj'); ?></label></th>
              <td>
                  <input type="hidden" name="academy_icon_id" id="academy_icon_id" value="0">
                  <div style="margin-bottom:10px;">
                      <img id="academy-icon-preview" src="" style="max-width:100px; display:none;">
                  </div>
                  <button id="upload-academy-icon-button" class="button" type="button">
                      <?php _e('Select Academy Icon', 'bjj'); ?>
                  </button>
              </td>
          </tr>
      </table>
      <p><input type="submit" class="button button-primary" value="<?php esc_attr_e('Add Academy', 'bjj'); ?>"></p>
  </form>
  
  <hr>
  
  <h2><?php _e('Existing Academies', 'bjj'); ?></h2>
  <?php if(!empty($academies)) : ?>
      <table class="wp-list-table widefat fixed striped">
          <thead>
              <tr>
                  <th><?php _e('Icon', 'bjj'); ?></th>
                  <th><?php _e('Name', 'bjj'); ?></th>
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
                          if($icon_url): ?>
                              <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($academy['name']); ?>" style="max-width:50px;">
                          <?php else: ?>
                              <?php _e('No Icon', 'bjj'); ?>
                          <?php endif; ?>
                      </td>
                      <td><?php echo esc_html($academy['name']); ?></td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>
  <?php else: ?>
      <p><?php _e('No academies found.', 'bjj'); ?></p>
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
