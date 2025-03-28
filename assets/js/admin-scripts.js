jQuery(document).ready(function($) {

    // ===============================
    // ORDER OF FIGHTS - DYNAMIC DRAG & DROP
    // ===============================
    var $fightOrderList = $("#fight-order-list");
    
    // Make the list sortable if the element exists
    if ($fightOrderList.length > 0) {
        $fightOrderList.sortable({
            placeholder: "ui-state-highlight"
        });
        $fightOrderList.disableSelection();
    }

    // On form submit, gather the order and place it in the hidden input
    $("form").on("submit", function() {
        if ($fightOrderList.length > 0) {
            // Get the current order of the items (based on data-id)
            var order = $fightOrderList.sortable("toArray", { attribute: "data-id" });
            // Set the comma-separated list in the hidden input
            $("#fight_order_list_input").val(order.join(","));
        }
    });

    // ===============================
    // LIVE TAB - AJAX REFRESH
    // ===============================
    $("#refresh-live").on("click", function(e) {
        e.preventDefault();
        refreshLiveQueue();
    });

    function refreshLiveQueue() {
        $.ajax({
            url: bjj_ajax_object.ajax_url,
            method: "POST",
            data: {
                action: "bjj_update_live_data"
            },
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

    // Optionally auto-refresh the live queue every 10 seconds
    setInterval(refreshLiveQueue, 10000);

});
