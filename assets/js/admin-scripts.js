admin-scripts.js
jQuery(document).ready(function($) {
    // Initialize sortable list for Order of Fights tab.
    $("#fight-order-list").sortable({
        placeholder: "ui-state-highlight"
    });
    $("#fight-order-list").disableSelection();

    // Example: Save order button click handler.
    $("#save-fight-order").on("click", function(e) {
        e.preventDefault();
        var order = $("#fight-order-list").sortable("toArray", { attribute: "data-id" });
        // Send "order" via AJAX to save in the database.
        console.log("Fight order:", order);
        alert("Fight order saved (placeholder)!");
    });

    // Live tab: refresh button handler.
    $("#refresh-live").on("click", function(e) {
        e.preventDefault();
        refreshLiveQueue();
    });

    // Function to refresh live queue via AJAX.
    function refreshLiveQueue() {
        $.ajax({
            url: tm_ajax_object.ajax_url,
            method: "POST",
            data: {
                action: "tm_update_live_data"
            },
            success: function(response) {
                if (response.status === "success") {
                    // Update the live queue div with returned data.
                    $("#live-queue").html("<p>" + response.data + "</p>");
                }
            },
            error: function() {
                console.error("Error updating live data");
            }
        });
    }

    // Optionally, refresh the live queue automatically every 10 seconds.
    setInterval(refreshLiveQueue, 10000);
});

