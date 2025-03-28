jQuery(document).ready(function($) {
    // Initialize sortable list for Order of Fights.
    $("#fight-order-list").sortable({
        placeholder: "ui-state-highlight"
    });
    $("#fight-order-list").disableSelection();

    // Save fight order handler.
    $("#save-fight-order").on("click", function(e) {
        e.preventDefault();
        var order = $("#fight-order-list").sortable("toArray", { attribute: "data-id" });
        console.log("Fight order:", order);
        alert("Fight order saved (placeholder)!");
    });

    // Refresh live queue handler.
    $("#refresh-live").on("click", function(e) {
        e.preventDefault();
        refreshLiveQueue();
    });

    // AJAX call to refresh the live queue.
    function refreshLiveQueue() {
        $.ajax({
            url: bjj_ajax_object.ajax_url,
            method: "POST",
            data: { action: "bjj_update_live_data" },
            success: function(response) {
                if (response.status === "success") {
                    $("#live-queue").html("<p>" + response.data + "</p>");
                }
            },
            error: function() {
                console.error("Error updating live data");
            }
        });
    }
    // Optionally refresh every 10 seconds.
    setInterval(refreshLiveQueue, 10000);
});
