jQuery(document).ready(function($) {

    // --- WP Media Uploader for Academy Icon field ---
    let file_frame;
    $('#academy-icon-button').on('click', function(e) {
        e.preventDefault();

        // Reopen existing frame if available
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create new media frame
        file_frame = wp.media({
            title: 'Select or Upload an Icon',
            button: { text: 'Use this icon' },
            multiple: false
        });

        // When an image is selected, get its URL and set it in the field
        file_frame.on('select', function() {
            const attachment = file_frame.state().get('selection').first().toJSON();
            $('#academy-icon-field').val(attachment.url);
        });

        // Open the media uploader
        file_frame.open();
    });

    // --- AJAX: Submit Academy Form ---
    $('#bjj-academy-form').on('submit', function(e) {
        e.preventDefault();

        // Build form data
        const formData = {
            action: 'bjj_add_academy',  // Matches the AJAX handler action in PHP
            nonce: bjjCompetitionAjax.nonce,
            name:        $(this).find('input[name="name"]').val(),
            main_coach:  $(this).find('input[name="main_coach_name"]').val(),
            address:     $(this).find('input[name="address"]').val(),
            email:       $(this).find('input[name="email"]').val(),
            phone:       $(this).find('input[name="phone"]').val(),
            affiliation: $(this).find('input[name="affiliation"]').val(),
            icon:        $(this).find('input[name="icon"]').val()
        };

        // Send data via AJAX
        $.post(bjjCompetitionAjax.ajaxUrl, formData, function(response) {
            if (response.success) {
                alert(response.data.message);
                // Optionally, reset the form or reload the academy list
                $('#bjj-academy-form')[0].reset();
            } else {
                alert('Error: ' + (response.data.message || 'Unknown error'));
            }
        });
    });

    // --- AJAX: Submit Category Form ---
    $('#bjj-category-form').on('submit', function(e) {
        e.preventDefault();

        // Build form data
        const formData = {
            action: 'bjj_add_category',  // Matches the AJAX handler action in PHP
            nonce: bjjCompetitionAjax.nonce,
            category_name: $(this).find('input[name="category_name"]').val(),
            belt_division: $(this).find('input[name="belt_division"]').val(),
        };

        // Send data via AJAX
        $.post(bjjCompetitionAjax.ajaxUrl, formData, function(response) {
            if (response.success) {
                alert(response.data.message);
                // Optionally, reset the form or reload the category list
                $('#bjj-category-form')[0].reset();
            } else {
                alert('Error: ' + (response.data.message || 'Unknown error'));
            }
        });
    });
});
