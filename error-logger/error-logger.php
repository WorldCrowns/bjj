<?php
/**
 * Plugin Name: Custom Error Logger
 * Plugin URI: https://example.com/
 * Description: Catches PHP errors, exceptions, and fatal errors and logs them to a custom file for debugging. Also adds an admin page to view (and clear) the log.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://example.com/
 * Text Domain: custom-error-logger
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define the log file path. This example uses the wp-content directory.
if ( ! defined( 'CEL_LOG_FILE' ) ) {
    define( 'CEL_LOG_FILE', WP_CONTENT_DIR . '/custom-error-log.txt' );
}

/**
 * Custom error handler.
 *
 * @param int    $errno   Error number.
 * @param string $errstr  Error message.
 * @param string $errfile Filename where error occurred.
 * @param int    $errline Line number.
 * @return bool
 */
function cel_error_handler( $errno, $errstr, $errfile, $errline ) {
    // Respect error suppression with @.
    if ( 0 === error_reporting() ) {
        return false;
    }
    $date    = date( 'Y-m-d H:i:s' );
    $message = "[$date] Error [$errno]: $errstr in $errfile on line $errline" . PHP_EOL;
    error_log( $message, 3, CEL_LOG_FILE );
    // Return false to allow the default PHP error handler to run.
    return false;
}
set_error_handler( 'cel_error_handler' );

/**
 * Custom exception handler.
 *
 * @param Exception $exception The exception that was thrown.
 */
function cel_exception_handler( $exception ) {
    $date    = date( 'Y-m-d H:i:s' );
    $message = "[$date] Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . PHP_EOL;
    error_log( $message, 3, CEL_LOG_FILE );
}
set_exception_handler( 'cel_exception_handler' );

/**
 * Shutdown handler to catch fatal errors.
 */
function cel_shutdown_handler() {
    $error = error_get_last();
    if ( $error !== NULL ) {
        $date    = date( 'Y-m-d H:i:s' );
        $message = "[$date] Fatal Error [{$error['type']}]: {$error['message']} in {$error['file']} on line {$error['line']}" . PHP_EOL;
        error_log( $message, 3, CEL_LOG_FILE );
    }
}
register_shutdown_function( 'cel_shutdown_handler' );

/**
 * Add an admin sub-menu under Tools to view the error log.
 */
function cel_add_admin_menu() {
    add_submenu_page(
        'tools.php',                 // Parent slug (Tools menu)
        'Error Log',                 // Page title
        'Error Log',                 // Menu title
        'manage_options',            // Capability
        'custom-error-log',          // Menu slug
        'cel_admin_page_callback'    // Callback function to render the page
    );
}
add_action( 'admin_menu', 'cel_add_admin_menu' );

/**
 * Admin page callback to display (and clear) the error log.
 */
function cel_admin_page_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Process clear log request.
    if ( isset( $_POST['cel_clear_log_nonce'] ) && wp_verify_nonce( $_POST['cel_clear_log_nonce'], 'cel_clear_log' ) ) {
        if ( file_exists( CEL_LOG_FILE ) ) {
            file_put_contents( CEL_LOG_FILE, '' );
            echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Log file cleared successfully!', 'custom-error-logger' ) . '</p></div>';
        }
    }
    
    echo '<div class="wrap"><h1>Error Log</h1>';
    $log_file = CEL_LOG_FILE;
    if ( file_exists( $log_file ) && is_readable( $log_file ) ) {
        $contents = file_get_contents( $log_file );
        if ( empty( $contents ) ) {
            echo '<p>' . __( 'The error log is empty.', 'custom-error-logger' ) . '</p>';
        } else {
            echo '<pre>' . esc_html( $contents ) . '</pre>';
        }
    } else {
        echo '<p>' . __( 'Error log file not found or not readable.', 'custom-error-logger' ) . '</p>';
    }
    // Form to clear the log file.
    echo '<form method="post">';
    wp_nonce_field( 'cel_clear_log', 'cel_clear_log_nonce' );
    echo '<p><input type="submit" class="button button-secondary" value="' . esc_attr__( 'Clear Log', 'custom-error-logger' ) . '"></p>';
    echo '</form></div>';
}

// No closing PHP tag to prevent accidental output
