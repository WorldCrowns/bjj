jQuery(document).ready(function($) {

    // ========== CATEGORIES ==========
    // Add Category
    $('#bjj-category-form').on('submit', function(e) {
        e.preventDefault();
        let data = {
            action: 'bjj_add_category',
            nonce: bjjCompetitionAjax.nonce,
            category_name: $(this).find('input[name="category_name"]').val(),
            belt_division: $(this).find('input[name="belt_division"]').val()
        };
        $.post(bjjCompetitionAjax.ajaxUrl, data, function(response){
            if(response.success) {
                alert(response.data.message);
                $('#bjj-category-form')[0].reset();
                loadCategories();
            } else {
                alert('Error adding category');
            }
        });
    });

    // Load Categories
    function loadCategories() {
        let data = {
            action: 'bjj_get_categories',
            nonce: bjjCompetitionAjax.nonce
        };
        $.post(bjjCompetitionAjax.ajaxUrl, data, function(response){
            if(response.success) {
                let list = response.data;
                let html = '<table class="widefat"><thead><tr><th>ID</th><th>Category Name</th><th>Belt Division</th><th>Actions</th></tr></thead><tbody>';
                $.each(list, function(i, cat){
                    html += '<tr>';
                    html += '<td>' + cat.id + '</td>';
                    html += '<td>' + cat.category_name + '</td>';
                    html += '<td>' + cat.belt_division + '</td>';
                    html += '<td><button class="button bjj-delete-category" data-id="'+ cat.id +'">Delete</button></td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
                $('#bjj-category-list').html(html);
            }
        });
    }
    loadCategories();

    // Delete Category
    $(document).on('click', '.bjj-delete-category', function(){
        if(!confirm('Are you sure you want to delete this category?')) return;
        let id = $(this).data('id');
        let data = {
            action: 'bjj_delete_category',
            nonce: bjjCompetitionAjax.nonce,
            id: id
        };
        $.post(bjjCompetitionAjax.ajaxUrl, data, function(response){
            if(response.success) {
                alert(response.data.message);
                loadCategories();
            } else {
                alert('Error deleting category');
            }
        });
    });

    // ========== Repeat similar patterns for Academies, Competitors, etc. ==========

});
