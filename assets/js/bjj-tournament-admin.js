/* assets/js/bjj-tournament-admin.js */
jQuery(document).ready(function($) {
    $('.bjj-upload-image-button').on('click', function(e) {
        e.preventDefault();
        var inputField = $(this).prev('.bjj-upload-image');
        var custom_uploader = wp.media({
            title: 'Select an Image',
            library: { type: 'image' },
            button: { text: 'Use this image' },
            multiple: false
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            inputField.val(attachment.url);
        })
        .open();
    });
});
