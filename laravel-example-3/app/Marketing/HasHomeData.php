<?php

namespace App\Marketing;

trait HasHomeData
{
    protected function getHomeData()
    {
        return [
            'home' => [
                'featureSwitcher' => $this->getHomeFeatureSwitcher(),
                'userFeatureCards' => $this->getHomeUserFeatureCards(),
                'search' => [
                    'min' => [
                        'Minimum',
                        'No Minimum',
                        '$100,000',
                        '$250,000',
                        '$500,000',
                        '$1,000,000',
                    ],

                    'max' => [
                        'Maximum',
                        '$100,000',
                        '$250,000',
                        '$500,000',
                        '$1,000,000',
                        'No Maximum',
                    ],
                ],
            ],
        ];
    }

    protected function getHomeFeatureSwitcher()
    {
        return [
            [
                'title' => 'Historical Financials Tool',
                'iconClass' => 'historical-financials-icon',
                // @codingStandardsIgnoreStart
                'content' => 'Historical financial information is key to understanding the value of a business. Never has a platform put that information front and center—until now. Sellers upload their information via an easy-to-use tool and retain complete control over who can see the data and when. Buyers get a standardized, exportable set of records that lets them crunch the numbers. This is what people mean when they say "win-win."',
                // @codingStandardsIgnoreEnd
                'imageUrl' => '/img/fe_history_center.jpg',
                'href' => '',
            ],

            [
                'title' => 'Listing Completion Rating',
                'iconClass' => 'fa fa-star',
                // @codingStandardsIgnoreStart
                'content' => 'Our Listing Completion Rating (LC Rating) prioritizes the most prepared and transparent sellers and provides buyers a better search experience. Each listing is rated on how much information is provided. The LC Rating is shown on a 5-star scale, with 5 stars meaning more details have been provided. The higher the LC Rating, the higher the placement in search results. Sellers are rewarded for their efforts rather than being pressed to spend more money each month.',
                // @codingStandardsIgnoreEnd
                'imageUrl' => '/img/LCRating_Star_System.jpg',
                'href' => '',
            ],


            [
                'title' => 'Exchange Space',
                'iconClass' => 'exchange-space-icon',
                // @codingStandardsIgnoreStart
                'content' => 'The deal process can be cumbersome, so we have pioneered a new concept that streamlines and organizes everything. Called an Exchange Space, this innovative deal management platform allows sellers to have separate conversations with each potential buyer, choosing what information to share and when. Whether you’re selling one business or twenty, this unparalleled system will save you time and keep your deal on track.',
                // @codingStandardsIgnoreEnd
                'imageUrl' => '/img/fe_exsp_table.jpg',
                'href' => '',
            ],

            [
                'title' => 'Diligence Center',
                'iconClass' => 'fa fa-check-square',
                // @codingStandardsIgnoreStart
                'content' => 'A critical part of every deal is conducting due diligence. Using our built-in Diligence Center, all parties to a deal can correspond in a single location that clusters messages into conversations, as specific or as broad as you need them to be. Our software also allows users to share and store documents in one convenient location, allowing our clients to easily access what they need, when they need it.',
                // @codingStandardsIgnoreEnd
                'imageUrl' => '/img/fe-diligence.jpg',
                'href' => '',
            ],

            [
                'title' => 'Professional Directory',
                'iconClass' => 'professionals-icon',
                // @codingStandardsIgnoreStart
                'content' => 'Firm Exchange is also a place for business brokers, accountants, lawyers, and other advisors to showcase their services to the small business community. Easily create and list your profile in the Professional Directory for free. No ads, no fees -- just another way we help get deals done more efficiently.',
                // @codingStandardsIgnoreEnd
                'imageUrl' => '/img/fe-home-professional-directory.png',
                'href' => '',
            ],
        ];
    }

    protected function getHomeUserFeatureCards()
    {
        return [
            'buyers' => [
                'iconClass' => 'buyers-icon',
                'title' => 'For Buyers',
                'listItems' => [
                    // @codingStandardsIgnoreStart
                    'Quickly and efficiently search for businesses, utilizing a Favorites List to track the most promising listings',
                    'Engage with sellers through Exchange Spaces and the included Diligence Center to better navigate the entire process from start to finish',
                    'Save a search or create a Watch List and receive notifications when a listing that matches your personalized criteria is added to the platform',
                    // @codingStandardsIgnoreEnd
                ],
                'button' => [
                    'href' => url('buy-a-business'),
                    'label' => 'Learn More',
                ],
            ],

            'sellers' => [
                'iconClass' => 'sellers-icon',
                'title' => 'For Sellers',
                'listItems' => [
                    // @codingStandardsIgnoreStart
                    'Easily create professional-looking listings that help you figure out what information is needed to sell your business',
                    'Painlessly manage the sale of your business by working with multiple would-be buyers, each organized into their own Exchange Space',
                    'Get rewarded for your thoroughness with our Listing Completion Rating—listing placement driven by content, not how much you pay',
                    // @codingStandardsIgnoreEnd
                ],
                'button' => [
                    'href' => url('sell-my-business'),
                    'label' => 'Learn More',
                ],
            ],

            'brokers' => [
                'iconClass' => 'brokers-icon',
                'title' => 'For Brokers',
                'listItems' => [
                    // @codingStandardsIgnoreStart
                    'Efficiently add and manage multiple listings, making sure you never lose track of a lead or potential client',
                    'Convert inquiries into Exchange Spaces, where you can invite your clients and any advisors to collaborate, keeping everyone on the same page',
                    'Make your profile visible in the Professional Directory so clients in your area can easily find you and tap into your wealth of experience',
                    // @codingStandardsIgnoreEnd
                ],
                'button' => [
                    'href' => url('business-brokers'),
                    'label' => 'Learn More',
                ],
            ],
        ];
    }
}
