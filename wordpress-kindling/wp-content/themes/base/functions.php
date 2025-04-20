<?php
/**
 * Theme files.
 * Only place files to load here. You can place any helper functions in libs/helpers.php
 *
 * @package Kindling_Theme
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 */

require_once 'libs/helpers.php';
require_once 'libs/post-types.php';
require_once 'libs/actions.php';
require_once 'libs/filters.php';
require_once 'libs/image-sizes.php';

do_action('kindling_parent_loaded');
