<?php
/**
 * Breadcrumbs.
 * Requires Breadcrumb NavXT plugin.
 *
 * @package Kindling
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 * @see https://wordpress.org/plugins/breadcrumb-navxt/
 */

if (!function_exists('bcn_display') || !display_breadcrumbs()) {
    return;
}
?>
<div class="container">
    <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
        <?php bcn_display(); ?>
    </div>
</div>
