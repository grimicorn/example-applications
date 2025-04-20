<?php
/**
 * Post Type: Page
 *
 * @package Kindling_Post_Types
 * @author  Matchbox Design Group <info@matchboxdesigngroup.com>
 */

namespace Kindling\Theme\Types;

use Kindling\PostTypes\Base;

/**
 * Page post type.
 */
class Page extends Base
{
    /**
     * Creates the Page post type.
     */
    public function __construct()
    {
        parent::__construct('page', 'Pages', 'Page');
    }
}
