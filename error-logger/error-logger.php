error-logger.php
<?php
/**
 * Plugin Name: Custom Error Logger
 * Plugin URI: https://example.com/
 * Description: Catches PHP errors, exceptions, and fatal errors and logs them to a custom file for debugging.
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
 * Custom error handler
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
    // Return false to let the default PHP error handler run as well.
    return false;
}
set_error_handler( 'cel_error_handler' );

/**
 * Custom exception handler
 *
 * @param Exception $exception The exception that was thrown.
 */
function cel_exception_handler( $exception ) {
    $date    = date( 'Y-m-d H:i:s' );
    $message = "[$date] Uncaught Exception: " . $exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine() . PHP_EOL;
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


