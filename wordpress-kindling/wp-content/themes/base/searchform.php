<?php
/**
 * Search form template.
 *
 * @package Kindling_Theme
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url()); ?>">
    <label class="search-label-wrap" for="search-field">
        <span class="search-label">Search <?php echo get_bloginfo('name') ?>:</span>
    </label>
    <div class="search-field-wrap">
        <input type="search" id="search-field" class="search-field" placeholder="Search <?php echo get_bloginfo('name') ?>..." value="" name="s">
        <button type="submit" class="btn search-button">Search</button>
    </div>
</form>
