<?php

use App\Mail\NewNotification;
use App\Support\Notification\NotificationType;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Overrides stripe webhook controller to disable notification
$router->post('/webhook/stripe', 'Application\StripeWebhookController@handleWebhook');

 // Overlay Tour Controller
$router->get('/tours/{slug}', 'OverlayTourController@show');

if (class_exists('Rap2hpoutre\LaravelLogViewer\LogViewerController')) {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('developer');
}

// Application
Route::get('/home', 'Application\DashboardController@redirectHome');
$router->group(['middleware' => 'auth', 'prefix' => 'dashboard', 'namespace' => 'Application'], function ($router) {
    // Dashboard
    $router->get('/', 'DashboardController@show')
           ->name('dashboard');
    $router->delete('/module-dismiss', 'DashboardModuleDismissController@destroy')
        ->name('dashboard.module-dismiss');

    // File Preview
    $router->post('file/preview', 'FilePreviewController@show')
           ->name('file.preview');
    $router->get('file/preview', 'FilePreviewController@show')
           ->name('file.preview');

    // Styleguide
    // When adding new style guide add a route name using the styleguide.show.{slug}.
    // This will be used to create the pages name, turns - or _ into spaces and uppercases the words.
    // Along with looking for the view in /resources/views/app/sections/styleguide/show/{slug}
    $router->get('styleguide', 'StyleguideController@index')
                ->name('styleguide');
    $router->get('styleguide/inputs', 'StyleguideController@show')->name('styleguide.show.inputs');
    $router->post('styleguide/inputs', 'StyleguideController@store')->name('styleguide.store.inputs');
    $router->patch('styleguide/inputs', 'StyleguideController@store')->name('styleguide.store.inputs');
    $router->get('styleguide/alerts', 'StyleguideController@show')
                ->name('styleguide.show.alerts');
    $router->get('styleguide/general', 'StyleguideController@show')
                ->name('styleguide.show.general');
    $router->get('styleguide/parts', 'StyleguideController@show')
                ->name('styleguide.show.parts');
    $router->get('styleguide/notifications', 'StyleguideController@show')
                ->name('styleguide.show.notifications');
    $router->get('styleguide/notification', 'StyleguideController@show')
        ->name('styleguide.show.notification');

    // Watchlists
    $router->get('watchlists', 'WatchlistController@index')
           ->name('watchlists.index');
    $router->get('watchlists/{id}/show', 'WatchlistController@show')
           ->name('watchlists.show')
           ->middleware('saved-search-owner');
    $router->post('watchlists', 'WatchlistController@store')
           ->name('watchlists.store');
    $router->put('watchlists/{id}', 'WatchlistController@update')
           ->name('watchlists.update')
           ->middleware('saved-search-owner');
    $router->patch('watchlists/{id}', 'WatchlistController@update')
           ->name('watchlists.update')
           ->middleware('saved-search-owner');

    // Saved Search
    $this->post('saved-searches/store', 'SavedSearchController@store')
         ->name('saved-searches.store');
    $this->post('saved-searches/{id}/update', 'SavedSearchController@update')
         ->name('saved-searches.update')
         ->middleware('saved-search-owner');
    $this->delete('saved-searches/{id}/destroy', 'SavedSearchController@destroy')
         ->name('saved-searches.destroy')
         ->middleware('saved-search-owner');

    // Saved Search List
    $router->get('/saved-search/list', 'SavedSearchListController@index')->name('saved-search.list');
    $router->get('/saved-search/{id}', 'SavedSearchListController@show')->name('saved-search.list');

    // Admin Section
    $router->get('admin/user-table', 'UserTableController@index')
               ->name('admin.user-table')
               ->middleware('developer');
    $router->get('admin/user-filter/export-csv', 'UserTableExportCSVController@index')
               ->name('admin.user-filter.export-csv')
               ->middleware('developer');

    $router->get('admin/lcs-custom-penalty', 'LCSCustomPenaltyController@index')
           ->name('lcs-custom-penalty.index')
           ->middleware('developer');
    $router->post('admin/lcs-custom-penalty', 'LCSCustomPenaltyController@index')
           ->name('lcs-custom-penalty.index')
           ->middleware('developer');
    $router->get('admin/lcs-custom-penalty/{id}', 'LCSCustomPenaltyController@edit')
           ->name('lcs-custom-penalty.edit')
           ->middleware('developer');
    $router->post('admin/lcs-custom-penalty/{id}/update', 'LCSCustomPenaltyController@update')
           ->name('lcs-custom-penalty.update')
           ->middleware('developer');

    $router->get('admin/admin-settings', 'AdminSettingsController@index')
        ->name('admin-settings.index')
        ->middleware('developer');
    $router->post('admin/admin-settings/{id}/update', 'SystemMessageController@update')
        ->name('system-message.update')
        ->middleware('developer');

    // Admin - User
    $router->delete('admin/user/{id}/destroy', 'UserAdministrationController@destroy')
           ->name('user-administration.destroy')
           ->middleware('developer');

    // /dashboard/admin/user/330/destroy



    // Profile
    $router->get('profile/edit', 'ProfileEditController@edit')
           ->name('profile.edit');
    $router->patch('profile/update', 'ProfileEditController@update')
           ->name('profile.update');
    $router->get('profile/settings/edit', 'ProfileSettingsController@edit')
               ->name('profile.settings.edit');
    $router->patch('profile/settings/update', 'ProfileSettingsController@update')
               ->name('profile.settings.update');
    $router->get('profile/notifications/edit', 'ProfileNotificationsController@edit')
               ->name('profile.notifications.edit');
    $router->patch('profile/notifications/update', 'ProfileNotificationsController@update')
               ->name('profile.notifications.update');
    $router->get('profile/payments/edit', 'ProfilePaymentsController@edit')
               ->name('profile.payments.edit');
    $router->patch('profile/payments/update', 'ProfilePaymentsController@update')
               ->name('profile.payments.update');
    $router->get('profile/preview', 'ProfilePreviewController@show')
           ->name('profile.preview');
    $router->post('profile/preview', 'ProfilePreviewController@update')
           ->name('profile.preview');
    $router->patch('profile/preview', 'ProfilePreviewController@update')
           ->name('profile.preview');
    $router->get('profile/subscriptions', 'ProfileSubscriptionController@index');
    $router->get('profile/subscription', 'ProfileSubscriptionController@show');

    $router->get('profile/{id}/account-balance', 'UserAccountBalanceController@show')
           ->name('profile.account-balance.show')
           ->middleware('can-access-profile');
    $router->get('profile/{id}/images', 'ProfileImagesController@show')
           ->name('profile.images.show')
           ->middleware('can-access-profile');

    $router->delete('profile/close', 'ProfileActiveController@destroy')->name('profile.destroy');

    // Listings - Preview
    $router->post('listing/{id}/preview', 'ListingPreviewController@update')
           ->name('listing.preview.update')
           ->middleware('listing-owner');
    $router->patch('listing/{id}/preview', 'ListingPreviewController@update')
           ->name('listing.preview.update')
           ->middleware('listing-owner');
    $router->get('listing/{id}/preview', 'ListingPreviewController@show')
           ->name('listing.preview')
           ->middleware('listing-owner');
    $router->get('listing/{id}/historical-financials/preview', 'ListingHistoricalFinancialsPreviewController@show')
           ->name('listing.historical-financials.preview')
           ->middleware('listing-owner');
    $router->get('listing/{id}/adjusted-financials/preview', 'ListingAdjustedFinancialsPreviewController@show')
           ->name('listing.adjusted-financials.preview')
           ->middleware('listing-owner');

    $router->get('listing/{id}/republishable', 'ListingRepublishableController@show')
        ->name('listing.index');

    // Listings Encouragement Modal
    $router->delete('listing/display-encouragement-modal/{id}/destroy', 'ListingEncouragementModalShouldDisplayController@destroy')
           ->name('listing-encouragement-modal-should-display.destroy')
           ->middleware('listing-owner');

    // Listings
    $router->get('listings', 'ListingDetailsController@index')
           ->name('listing.index');
    $router->get('listing/create', 'ListingDetailsController@create')
           ->name('listing.create');
    $router->post('listing/create', 'ListingDetailsController@store')
           ->name('listing.details.store');
    $router->get('listing/{id}/details/edit', 'ListingDetailsController@edit')
           ->name('listing.details.edit')
           ->middleware('listing-owner');
    $router->post('listing/{id}/details', 'ListingDetailsController@update')
           ->name('listing.details.update')
           ->middleware('listing-owner');
    $router->patch('listing/{id}/details', 'ListingDetailsController@update')
           ->name('listing.details.update')
           ->middleware('listing-owner');
    $router->delete('listing/{id}/destroy', 'ListingDetailsController@destroy')
           ->name('listing.destroy')
           ->middleware('listing-owner');

    // Listings - Publish
    $router->post('listing/{id}/publish', 'ListingPublishController@store')
           ->name('listing.publish');
    $router->delete('listing/{id}/unpublish', 'ListingPublishController@destroy')
           ->name('listing.unpublish');

    // Listings - Historical Financials
    $router->get('listing/{id}/historical-financials/edit', 'ListingHistoricalFinancialsController@edit')
           ->name('listing.historical-financials.edit')
           ->middleware('listing-owner');
    $router->patch('listing/{id}/historical-financials', 'ListingHistoricalFinancialsController@update')
           ->name('listing.historical-financials.update')
           ->middleware('listing-owner');
    $router->post('listing/{id}/historical-financials', 'ListingHistoricalFinancialsController@update')
        ->name('listing.historical-financials.update')
        ->middleware('listing-owner');

    // Listings - LCS
    $router->get('listing/{id}/completion-score', 'ListingCompletionScoreController@index')
           ->name('listing.completion-score.index')
           ->middleware('listing-owner');
    $router->any('listing/0/completion-score/{type}/{section}/{subsection?}', 'ListingCompletionScoreController@create')
           ->name('listing.completion-score.show');
    $router->any('listing/{id}/completion-score/{type}/{section}/{subsection?}', 'ListingCompletionScoreController@edit')
           ->name('listing.completion-score.show')
           ->middleware('listing-owner');

    // Listings - Exit Survey
    $router->post('/listing/{id}/exit-survey', 'ListingExitSurveyController@store')
          ->name('listing.exit-survey.store')
          ->middleware('listing-owner');

    //Business Inquiry
    $router->get('business-inquiries', 'BuyerInquiryController@index')
           ->name('business-inquiry.index');
    $router->post('business-inquiries', 'BuyerInquiryController@index')
           ->name('business-inquiry.index');
    $router->post('business-inquiry', 'BuyerInquiryController@store')
           ->name('business-inquiry.store');
    $router->get('business-inquiry/{id}', 'BuyerInquiryController@show')
           ->name('business-inquiry.show')
           ->middleware('exchange-space-member');
    $router->get('business-inquiry/{id}/closed', 'ExchangeSpaceClosedController@show')
           ->name('business-inquiry.closed.show')
           ->middleware('exchange-space-member');



    // Business Inquiry - Acceptance
    $router->post('business-inquiry/{id}/acceptance', 'BuyerInquiryAcceptanceController@store')
           ->name('business-inquiry.acceptance.store')
           ->middleware('exchange-space-seller');
    $router->delete('business-inquiry/{id}/acceptance', 'BuyerInquiryAcceptanceController@destroy')
           ->name('business-inquiry.acceptance.destroy')
           ->middleware('exchange-space-seller');

    // Business Inquiry - Display Welcome
    $router->delete('business-inquiry/display-intro', 'BuyerInquiryDisplayWelcomeController@destroy')
    ->name('business-inquiry.display-intro.destroy');

    // Business Inquiry - Conversation
    $router->post('/business-inquiry/{id}/conversation/{c_id}', 'BuyerInquiryConversationController@store')
           ->name('business-inquiry.conversation.store')
           ->middleware('conversation-member');
    $router->patch('/business-inquiry/{id}/conversation/{c_id}', 'BuyerInquiryConversationController@store')
           ->name('business-inquiry.conversation.update')
           ->middleware('conversation-member');

    //Exchange Spaces
    $router->get('exchange-spaces', 'ExchangeSpaceController@index')
           ->name('exchange-spaces.index');
    $router->post('exchange-spaces', 'ExchangeSpaceController@index')
           ->name('exchange-spaces.index');
    $router->get('exchange-spaces/{id}', 'ExchangeSpaceController@show')
           ->name('exchange-spaces.show')
           ->middleware('exchange-space-member');
    $router->delete('exchange-spaces/{id}/destroy', 'ExchangeSpaceController@destroy')
           ->name('exchange-spaces.destroy')
           ->middleware('exchange-space-seller');

    $router->get('exchange-spaces/{id}/closed', 'ExchangeSpaceClosedController@show')
           ->name('exchange-spaces.closed.show')
        ->middleware('exchange-space-closed');

    // Exchange Space - Advance Stage
    $router->put('exchange-spaces/{id}/advance-stage', 'ExchangeSpaceAdvanceStage@update')
           ->name('exchange-spaces.advance-stage.update')
           ->middleware('exchange-space-seller');
    $router->patch('exchange-spaces/{id}/advance-stage', 'ExchangeSpaceAdvanceStage@update')
           ->name('exchange-spaces.advance-stage.update')
           ->middleware('exchange-space-seller');

    // Exchange Space - Custom Title
    $router->put('exchange-spaces/{id}/member-title', 'ExchangeSpaceTitleController@update')
        ->name('exchange-spaces.member-title.update')
        ->middleware('exchange-space-member');
    $router->patch('exchange-spaces/{id}/member-title', 'ExchangeSpaceTitleController@update')
        ->name('exchange-spaces.member-title.update')
        ->middleware('exchange-space-member');

    // Exchange Space - New Member
    $router->post('exchange-spaces/{id}/member', 'ExchangeSpaceMemberController@index')
           ->name('exchange-spaces.member.index')
           ->middleware('exchange-space-member');
    $router->post('exchange-spaces/{id}/member/store', 'ExchangeSpaceMemberController@store')
           ->name('exchange-spaces.member.store');
    $router->delete('exchange-spaces/{id}/member/{m_id}/destroy', 'ExchangeSpaceMemberController@destroy')
           ->name('exchange-spaces.member.destroy');

    // Exchange Space - Dashboard
    $router->post('exchange-spaces/{id}/dashboard', 'ExchangeSpacesDashboardController@store')
           ->name('exchange-spaces.dashboard.store')
           ->middleware('exchange-space-member');
    $router->delete('exchange-spaces/{id}/dashboard', 'ExchangeSpacesDashboardController@destroy')
           ->name('exchange-spaces.dashboard.destroy')
           ->middleware('exchange-space-member');

    //Diligence Center
    $router->get('exchange-spaces/{id}/diligence-center', 'ExchangeSpaceConversationController@index')
        ->name('exchange-spaces.conversations.index')
        ->middleware('exchange-space-member');
    $router->post('exchange-spaces/{id}/diligence-center', 'ExchangeSpaceConversationController@index')
        ->name('exchange-spaces.conversations.index')
        ->middleware('exchange-space-member');
    $router->get('exchange-spaces/{id}/diligence-center/conversation/create', 'ExchangeSpaceConversationController@create')
        ->name('exchange-spaces.conversations.create')
        ->middleware('exchange-space-member');
    $router->get('exchange-spaces/{id}/diligence-center/conversation/{c_id}', 'ExchangeSpaceConversationController@show')
        ->name('exchange-spaces.conversations.show')
        ->middleware('conversation-member');
    $router->post('exchange-spaces/{id}/diligence-center/conversation/store/', 'ExchangeSpaceConversationController@store')
        ->name('exchange-spaces.conversations.store')
        ->middleware('exchange-space-member');
    $router->patch('exchange-spaces/{id}/diligence-center/conversation/{c_id}/update', 'ExchangeSpaceConversationController@update')
        ->name('exchange-spaces.conversations.update')
        ->middleware('conversation-member');
    $router->post('exchange-spaces/{id}/diligence-center/conversation/{c_id}/update', 'ExchangeSpaceConversationController@update')
        ->name('exchange-spaces.conversations.update')
        ->middleware('conversation-member');
    $router->delete('exchange-spaces/{id}/diligence-center/conversation/{c_id}/destroy', 'ExchangeSpaceConversationController@destroy')
        ->name('exchange-spaces.conversations.destroy')
        ->middleware('conversation-member');

    // Messages
    $router->delete('message/{id}/destroy', 'MessageController@destroy')
    ->name('conversation.message.destroy')
    ->middleware('developer');

    // Exchange Space - File
    $router->post('exchange-spaces/{id}/file/destroy', 'ExchangeSpaceFileController@destroy')
           ->name('exchange-spaces.file.destroy')
           ->middleware('exchange-space-member');

    // Diligence Center - Conversation Resolve
    $router->post('exchange-spaces/conversation/{c_id}/resolve', 'ExchangeSpaceConversationResolveController@store')
           ->name('exchange-spaces.conversations.resolve.store')
           ->middleware('conversation-member');
    $router->delete('exchange-spaces/conversation/{c_id}/resolve', 'ExchangeSpaceConversationResolveController@destroy')
           ->name('exchange-spaces.conversations.resolve.destroy')
           ->middleware('conversation-member');
    $router->get('exchange-spaces/conversation/{c_id}/resolve', 'ExchangeSpaceConversationResolveController@store')
           ->name('exchange-spaces.conversations.resolve.get')
           ->middleware('conversation-member');

    //Historical Financials
    $router->get('exchange-spaces/{id}/historical-financials', 'ExchangeSpaceHistoricalFinancialsController@show')
    ->name('exchange-spaces.historical-financials.show')
    ->middleware('exchange-space-member');
    $router->get('exchange-spaces/{id}/historical-financials/csv', 'HistoricalFinancialsCSVController@index')
    ->name('exchange-spaces.historical-financials.csv.index')
    ->middleware('exchange-space-historical-financials-access');
    $router->post('exchange-spaces/{id}/historical-financials', 'ExchangeSpaceHistoricalFinancialsController@update')
    ->name('exchange-spaces.historical-financials.update')
    ->middleware('exchange-space-seller');

    //Adjusted Financials & Trends
    $router->get('exchange-spaces/{id}/adjusted-financials-trends', 'ExchangeSpaceAdjustedFinancialsTrendsController@show')
    ->name('exchange-spaces.adjusted-financials-trends.show')
    ->middleware('exchange-space-member');
    $router->get('exchange-spaces/{id}/adjusted-financials-trends/csv', 'AdjustedFinancialsTrendsCSVController@index')
    ->name('exchange-spaces.adjusted-financials-trends.csv.index')
    ->middleware('exchange-space-adjusted-financials-trends-access');
    $router->post('exchange-spaces/{id}/adjusted-financials-trends', 'ExchangeSpaceAdjustedFinancialsTrendsController@update')
    ->name('exchange-spaces.adjusted-financials-trends.update')
    ->middleware('exchange-space-seller');

    //Notifications
    $router->get('exchange-spaces/{id}/notifications', 'ExchangeSpaceNotificationController@index')->name('exchange-spaces.notifications.index')
    ->middleware('exchange-space-member');
    $router->post('exchange-spaces/{id}/notifications', 'ExchangeSpaceNotificationController@update')->name('exchange-spaces.notifications.update')
    ->middleware('exchange-space-member');

    //Favorites
    $router->get('favorites', 'FavoriteController@index')
           ->name('favorites.index');
    $router->post('favorites', 'FavoriteController@index')
           ->name('favorites.index');
    $router->post('favorites/store', 'FavoriteController@store')
            ->name('favorites.store');
    $router->delete('favorites/{id}/destroy', 'FavoriteController@destroy')
            ->name('favorites.destroy')
            ->middleware('favorite-owner');

    //Notifications
    $router->get('notifications', 'NotificationController@index')->name('notifications.index');
    $router->post('notifications/{id}/update', 'NotificationController@update')->name('notifications.update');
    $router->patch('notifications/{id}/update', 'NotificationController@update')->name('notifications.update');
    $router->put('notifications/{id}/update', 'NotificationController@update')->name('notifications.update');
    $router->delete('notifications/{id}/destroy', 'NotificationController@destroy')->name('notifications.destroy');
});

// Email Tests
include_once 'mailable.php';

// Tools
include_once 'tools.php';

Route::group(['prefix' => 'abuse-report'], function ($router) {
    // Email notification abuse reports
    $router->get('/', 'AbuseReportController@index')
        ->name('abuse-report.index');
    $router->get('/{id}/store', 'AbuseReportController@store')
        ->name('abuse-report.store');
    $router->post('/{id}/store', 'AbuseReportController@store')
        ->name('abuse-report.store');
});

// Marketing
Route::group(['namespace' => 'Marketing'], function ($router) {
    // Professionals Search
    $router->redirect('professionals', '/professionals/search/');
    $router->get('/professionals/search/', 'ProfessionalsSearchLandingController@index')
    ->name('professionals.search-landing');
    $router->get('/professionals/search/results', 'ProfessionalsController@index')
                ->name('professional.index');
    $router->post('/professionals/search/results', 'ProfessionalsController@index')
                ->name('professional.search');
    $router->get('/professional/{id}', 'ProfessionalsController@show')
                ->name('professional.show');
    $router->get('/professional/{id}/contact', 'ProfessionalContactFormController@show')
               ->name('professional.contact.show');
    $router->post('/professional/{id}/contact', 'ProfessionalContactFormController@store')
               ->middleware('auth')
               ->name('professional.contact.store');

    //Businesses Search
    $router->redirect('businesses', '/businesses/search/');
    $router->get('/businesses/search/', 'BusinessesSearchLandingController@index')
    ->name('businesses.search-landing');
    $router->get('/businesses/search/results', 'BusinessesSearchController@index')->name('businesses.index');
    $router->post('/businesses/search/results', 'BusinessesSearchController@index')->name('businesses.index');
    $router->get('/business/{id}', 'BusinessesSearchController@show')->name('businesses.show')->middleware('listing-publish-access');

    // Allows blog.firmexchange.com to retrieve the header/footer markup.
    $router->get('/blog-wrap', 'BlogWrap@index');

    // Speedbump
    $router->get('external', 'ExternalLinkSpeedBumpController@index')->name('speed-bump-external');

    // General pages.
    $router->get('/terms', 'SiteController@redirectTerms');
    $router->get('/', 'SiteController@index')->name('home');
    $router->post('/contact', 'ContactFormController@store');
    $router->get('/lc-rating', 'LCRatingController@index');
    $router->get('/pricing', 'PricingController@index');
    $router->get('/{segment1}/{segment2?}/{segment3?}', 'SiteController@show');
});
