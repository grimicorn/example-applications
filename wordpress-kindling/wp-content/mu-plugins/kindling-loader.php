<?php
/**
 * Kindling Loader
 * Requires all must use plugins
 */

if (!file_exists($autoload_path = ABSPATH . '/vendor/autoload.php')) {
    // phpcs:disable
    wp_die('Please run <strong><code>composer install</code></strong> in your terminal in your root install directory <strong>('.ABSPATH.')</strong> to get started.');
    // phpcs:enable
}

require $autoload_path;

do_action('kindling_ready');
