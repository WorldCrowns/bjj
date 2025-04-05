<?php
if (!defined('ABSPATH')) exit;

global $wpdb;

// Set headers for Server-Sent Events (SSE)
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

set_time_limit(0);
$start = time();
while (true) {
    if (connection_aborted()) {
        break;
    }
    
    $table = $wpdb->prefix . 'bjj_tournament_matches';
    $matches = $wpdb->get_results("SELECT * FROM $table WHERE status = 'ongoing'");
    $data = json_encode($matches);
    
    echo "data: {$data}\n\n";
    @ob_flush();
    flush();
    sleep(10);
    if ((time() - $start) > 60) {
        break;
    }
}
exit;
