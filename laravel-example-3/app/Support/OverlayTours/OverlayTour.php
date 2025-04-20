<?php

namespace App\Support\OverlayTours;

/**
 * OverlayTour
 *
 * phpcs:disable
 * To add a new tour "My Example" you will want to do the following...
 *
 * - Create a protected method below of myExampleTour that returns an array of the steps see dashboardTour for an example.
 *     - NOTE: The name should be $slug camel cased with 'Test' appended to the end. Slug 'my-example' would be 'myExampleTour'.
 *     - The element parameter should correspond to a Javascript selector.
 *         - You can use #overlay_tour_page_title_step to target admin page titles.
 *         - You can #overlay_tour_final_step if you want to add a final step not associated with and element like on the dashboard
 *     - The intro parameter is the content in the pop up.
 * - Add the following to your view data. See App\Http\Controllers\Application\DashboardController for an example.
 *     - 'tourUrl' => '/tours/my-example'
 *     - 'tourEnabled' => true
 * - Add a boolean my_example_viewed column to the user_overlay_tours table
 *     - NOTE: The name should be $slug snake cased with 'viewed' appended to the end. Slug 'my-example' would be 'my_example_viewed'.
 *     - You may also want to add 'my_example_viewed' => 'boolean' to the UserOverlayTour::$casts to automatically cast 'my_example_viewed' to boolean
 * - Add the my_example_viewed column to the $viewedColumns property.
 * phpcs:enable
 */
class OverlayTour
{
    protected $slug;
    protected $name;
    protected $column;
    protected $viewedColumns = [
        'dashboard_viewed',
        'listing_create_viewed',
        'listing_edit_viewed',
        'lcs_index_viewed',
        'listing_historical_financials_edit_viewed',
        'exchange_space_index_viewed',
        'exchange_space_show_viewed',
        'diligence_center_index_viewed',
    ];

    public function __construct($slug)
    {
        $this->slug = $slug;
        $this->name = str_replace(['_', '-'], ' ', $this->slug);
        $this->column = snake_case("{$this->name}_viewed");
    }

    public function getTourSteps()
    {
        $this->updateColumn();

        $method = camel_case("{$this->name}Tour");

        return $this->wrapIntrosInParagraphTags(
            method_exists($this, $method) ? $this->$method() : []
        );
    }

    protected function wrapIntrosInParagraphTags($tour)
    {
        return collect($tour)->map(function ($step) {
            $step['intro'] = $this->wrapIntroInParagraphTags($step['intro']);

            return $step;
        });
    }

    protected function wrapIntroInParagraphTags($intro)
    {
        return collect(explode("\n", $intro ?? ''))
        ->map(function ($line) {
            return trim($line) ? '<p>' . trim($line) . '</p>' : $line;
        })->implode('');
    }

    protected function updateColumn()
    {
        // Set the viewed column if not already set
        if (!$this->shouldUpdateColumn()) {
            return;
        }

        $column = $this->column;
        auth()->user()->tour->$column = true;
        auth()->user()->tour->save();
    }

    protected function shouldUpdateColumn()
    {
        return in_array($column = $this->column, $this->viewedColumns) and !auth()->user()->tour->$column;
    }

    protected function dashboardTour()
    {
        $icon = '<i class="fa fa-map" aria-hidden="true"></i>';
        $question_icon = '<i class="fa fa-question-circle-o" aria-hidden="true"></i>';

        return [
            [
                'element' => '#overlay_tour_page_title_step',
                // phpcs:disable
                'intro' => "The Dashboard is your home base within the Firm Exchange platform.  This guide will take you on a brief tour of what Firm Exchange has to offer.
                You can \"Skip\" this tour at any time.  To restart the tour, click the {$icon} icon at the top right corner—if the page has a tour (not all do).",
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_step_2',
                // phpcs:disable
                'intro' => 'This is your Navigation panel. From here you can access the various areas within the application.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_step_3',
                // phpcs:disable
                'intro' => "When there's something you need to know about, a notification will show up here.  You can click on a notification to go straight to the area in question, if applicable.
                Additionally, you may get an e-mail depending on the settings you've chosen.",
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_step_4',
                // phpcs:disable
                'intro' => 'Our deal management areas are called "Exchange Spaces."  This area lets you keep track of the Exchange Spaces that are most important to you.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_step_5',
                // phpcs:disable
                'intro' => 'For the safety of everyone in the Firm Exchange community, we suggest you log out at the end of your session, especially if using a laptop or public computer.  Do so from this drop-down.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_contact_link',
                // phpcs:disable
                'intro' => "That's it for now!
                We recommend you begin by completing your profile.
                Don't forget to click the {$question_icon} icon if there's anything we can do to help.",
                // phpcs:enable

            ],
        ];
    }

    protected function listingCreateTour()
    {
        return [
            [
                'element' => '#overlay_tour_form_title_step',
                // phpcs:disable
                'intro' => 'The Create Listing tool is the jumping off place for selling your business.
                Information and data are critical to completing a sale.  The more you provide, the higher your Listing Completion Rating will climb.  This demonstrates to would-be buyers that you’re serious about selling.
                Don’t feel like you must complete this in one sitting!  You might find that you need to gather more information.  Only the first section must be completed before you can save your progress.
                If you’re not sure what a field is asking for, check the tooltip for more information.',
                // phpcs:enable
            ],

            [
                'element' => '.btn-model-submit',
                // phpcs:disable
                'intro' => 'Once you’ve filled out the first section ("Required Information"), you will be able to save your progress.
                We encourage you to Save your work often, just as you do when working with any piece of software.',
                // phpcs:enable
            ],
        ];
    }

    protected function listingEditTour()
    {
        return [
            [
                'element' => '.page-navigation-menu',
                // phpcs:disable
                'intro' => 'After you’ve saved your listing for the first time, you are now editing a listing and you’ll see new tabs pop up at the top of the page.
                The Listing Details tab is the same page you have been using to add information about your listing.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_listing_action_buttons',
                // phpcs:disable
                'intro' => 'Previewing your listing will show exactly how it will appear to the wider community once you’ve posted it.
                Posting a listing makes it available for the Firm Exchange community to see and inquire about.  To post a listing, you must have an active subscription or pay a one-time fee for the listing in question.  You can continue to edit a listing after it has been posted.
                Now let’s get started listing your business!',
                // phpcs:enable
            ]
        ];
    }

    protected function lcsIndexTour()
    {
        return [
            [
                'element' => '.page-navigation-menu > li.active',
                // phpcs:disable
                'intro' => 'The Listing Completion Rating (LC Rating) tab provides detailed information about how your listing\'s LC Rating is being calculated.
                You can click each line item (e.g. "More About the Business") and be taken directly to that area.  The more information you provide, the higher your score will climb.  This score is the default sort for all listing searches conducted on Firm Exchange.',
                // phpcs:enable
            ],
        ];
    }

    protected function listingHistoricalFinancialsEditTour()
    {
        return [
            [
                'element' => '.page-navigation-menu > li.active',
                // phpcs:disable
                'intro' => 'The Historical Financials tab is where you will input financial data about your business.
                This information will not be viewable by other users until you have invited them into an Exchange Space AND granted them access.  It’s your data and you retain control over who sees it.
                Whether or not it’s visible to other users, the presence of this information will contribute to your LC Rating.  As such, we suggest you input the data early in the process and keep it updated as time passes.',
                // phpcs:enable
            ],
        ];
    }

    protected function exchangeSpaceIndexTour()
    {
        return [
            [
                'element' => '#overlay_tour_page_title_step',
                // phpcs:disable
                'intro' => 'This landing page is where you will manage your various Exchange Spaces.  Whenever you join an Exchange Space, it will appear here.
                From here, you’ll easily be able to track the stages of your various deals and see which Exchange Spaces have notifications that require your attention.',
                // phpcs:enable
            ],

            [
                'element' => '.sort-table-body-row .add-to-dashboard-icon',
                // phpcs:disable
                'intro' => 'Clicking the "dashboard" icon next to a listing will add it to your main dashboard.  Use this feature to stay up to speed on the Exchange Spaces that matter most.',
                // phpcs:enable
            ],

            [
                'element' => '.sort-table-body-row .edit-subtitle',
                // phpcs:disable
                'intro' => 'Access an Exchange Space by clicking on its name.
                You can use the "pencil" icon next to the name to give it a custom name that will only be visible to you.',
                // phpcs:enable
            ],
        ];
    }

    protected function exchangeSpaceShowTour()
    {
        return [
            [
                'element' => '.page-navigation',
                // phpcs:disable
                'intro' => 'The landing page of an Exchange Space allows you quickly and easily stay abreast of any recent developments.
                Navigate within an Exchange Space using these tabs.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_stages_step',
                // phpcs:disable
                'intro' => 'The progress of each Exchange Space is tracked by a status bar that helps guide everyone through the process.  Each deal stage and intermediate step has a tool tip that suggests possible actions and milestones.
                The seller is the only person that can advance a deal to the next stage.  Deals cannot move backwards.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_exchange_space_members_step',
                // phpcs:disable
                'intro' => 'Easily identify who is in an Exchange Space using the Members panel.  Clicking on a name will take you to that person’s profile.
                Anyone can request a member be added to an Exchange Space, but the seller has the final call.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_notifications_accordion_step',
                // phpcs:disable
                'intro' => 'This Notifications panel tracks alerts specific to the given Exchange Space.  You can click on a notification to go straight to the area of the site referenced, if applicable.',
                // phpcs:enable
            ],

        ];
    }

    protected function diligenceCenterIndexTour()
    {
        return [
            [
                'element' => '.form-action-bar',
                // phpcs:disable
                'intro' => 'The Diligence Center keeps all deal-related conversations in one convenient location.
                Conversations can be filtered by topic or status and every message is searchable.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_diligence_conversation_step',
                // phpcs:disable
                'intro' => 'Messages are grouped into conversations, each of which has a topic assigned to it.
                Clicking a user’s name will take you to his or her profile, while the orange title of the conversation leads to the individual messages.
                Quickly identify which conversations have unread message by the alert badge on the right.',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_diligence_unresolved_step',
                // phpcs:disable
                'intro' => 'Forgotten questions keep deals from getting done.
                This area shows you how many unresolved conversations remain. Clicking on the link will filter the list to show only the outstanding issues.',
                // phpcs:enable
            ],

            [
                'element' => '.conversation-resolve-input',
                // phpcs:disable
                'intro' => 'Any participant can mark a conversation resolved (or mark it unresolved if something',
                // phpcs:enable
            ],

            [
                'element' => '#overlay_tour_documents_step',
                // phpcs:disable
                'intro' => 'All documents uploaded within a conversation will appear here for ease of access.
                The search bar allows you to quickly find the document you’re looking for but WILL NOT search within documents for security and privacy reasons.',
                // phpcs:enable
            ],
        ];
    }
}
