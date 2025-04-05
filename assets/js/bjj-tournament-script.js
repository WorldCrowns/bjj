/* assets/js/bjj-tournament-script.js */
jQuery(document).ready(function($) {
    // Using native datetime-local inputs; no additional JS is required for date/time selection.
    
    // Use Server-Sent Events (SSE) if available; fallback to AJAX polling for schedule matches
    if (window.EventSource) {
        var source = new EventSource(bjjTournament.ajax_url + '?bjj_tournament_sse=1');
        source.onmessage = function(event) {
            var matches = JSON.parse(event.data);
            var html = '';
            if (matches.length > 0) {
                html += '<ul>';
                $.each(matches, function(index, match) {
                    html += '<li>Match ' + match.id + ': Competitor ' + match.competitor1_id + ' vs ' + match.competitor2_id + ' at ' + match.fight_time + '</li>';
                });
                html += '</ul>';
            } else {
                html = '<p>No ongoing matches.</p>';
            }
            $('#schedule-container').html(html);
        };
        source.onerror = function(event) {
            console.error('SSE error:', event);
        };
    } else {
        function fetchMatches() {
            $.ajax({
                url: bjjTournament.ajax_url,
                type: 'POST',
                data: {
                    action: 'bjj_tournament_get_matches',
                    nonce: bjjTournament.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var matches = response.data;
                        var html = '';
                        if (matches.length > 0) {
                            html += '<ul>';
                            $.each(matches, function(index, match) {
                                html += '<li>Match ' + match.id + ': Competitor ' + match.competitor1_id + ' vs ' + match.competitor2_id + ' at ' + match.fight_time + '</li>';
                            });
                            html += '</ul>';
                        } else {
                            html = '<p>No ongoing matches.</p>';
                        }
                        $('#schedule-container').html(html);
                    }
                },
                error: function() {
                    $('#schedule-container').html('<p>Error fetching match data.</p>');
                }
            });
        }
        fetchMatches();
        setInterval(fetchMatches, 10000);
    }
    
    // Initialize sortable for matchmaking container
    if ($('#matchmaking-container').length) {
        $('#matchmaking-container').sortable({
            placeholder: "ui-state-highlight"
        });
    }
    
    console.log('BJJ Tournament front-end script loaded.');
});
