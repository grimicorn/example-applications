<?php

namespace Tests\Feature;

use App\Listing;
use Tests\TestCase;
use App\ExchangeSpace;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ExchangeSpaceLastActivityTest extends TestCase
{
    use HasExchangeSpaceCreators,
        RefreshDatabase;

    /**
    * @test
    */
    public function it_updates_the_timestamp_when_activating_a_member()
    {
        $space = $this->spaceInPast();
        $original_updated_at = $space->updated_at;

        $this->signInWithEvents($space->sellerUser());

        $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), [
            'user_id' => factory('App\User')->create()->id,
        ]);

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
    * @test
    */
    public function it_updates_the_timestamp_when_deactivating_a_member()
    {
        $space = $this->spaceInPast();
        $original_updated_at = $space->updated_at;

        $this->signInWithEvents($space->sellerUser());

        $memeber = factory('App\ExchangeSpaceMember')->states('seller-advisor', 'approved')->create([
            'exchange_space_id' => $space->id,
        ]);

        $this->delete(route('exchange-spaces.member.destroy', [ 'id' => $space->id, 'm_id' => $memeber->id ]));

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
    * @test
    */
    public function it_updates_the_timestamp_when_requesting_a_member_review()
    {
        $space = $this->spaceInPast();
        $original_updated_at = $space->updated_at;

        $this->signInWithEvents($space->buyerUser());

        $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), [
            'user_id' => factory('App\User')->create()->id,
        ]);

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
    * @test
    */
    public function it_updates_the_timestamp_when_creating_a_new_conversation()
    {
        $space = $this->spaceInPast();
        $original_updated_at = $space->updated_at;

        $this->signInWithEvents($space->buyerUser());

        $this->post(route('exchange-spaces.conversations.store', ['id' => $space->id]), [
            'title' => $this->faker->words(3, true),
            'category' => $this->getRandomConversationCategory(),
            'body' => $this->faker->paragraphs(3, true),
        ]);

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
    * @test
    */
    public function it_updates_the_timestamp_when_creating_a_new_inquiry_message()
    {
        $space = $this->inquiryInPast();
        $conversation = $space->conversations->first();
        $original_updated_at = $space->updated_at;

        $this->signInWithEvents($space->buyerUser());

        $this->post(route('business-inquiry.conversation.store', ['id' => $space->id, 'c_id' => $conversation->id]), [
            'body' => $this->faker->paragraphs(3, true),
        ]);

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
     * @test
     */
    public function it_updates_the_timestamp_when_creating_a_new_diligence_message()
    {
        $space = $this->spaceInPast();
        $conversation = $space->conversations->first();
        $original_updated_at = $space->updated_at;

        $this->signInWithEvents($space->buyerUser());

        $this->patch(
            route('exchange-spaces.conversations.update', ['id' => $conversation->space->id, 'c_id' => $conversation->id]),
            ['body' => $body = $this->faker->paragraphs(3, true)]
        );

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
     * @test
     */
    public function it_updates_the_conversation_timestamp_when_creating_a_new_inquiry_message()
    {
        $space = $this->inquiryInPast();
        $conversation = $this->modelInPast($space->conversations->first());
        $original_updated_at = $conversation->updated_at;

        $this->signInWithEvents($space->buyerUser());

        $this->post(route('business-inquiry.conversation.store', ['id' => $space->id, 'c_id' => $conversation->id]), [
            'body' => $this->faker->paragraphs(3, true),
        ]);

        $this->assertTimestampUpdated($conversation, $original_updated_at);
    }

    /**
    * @test
    */
    public function it_updates_the_conversation_timestamp_when_creating_a_new_diligence_message()
    {
        $space = $this->spaceInPast();
        $conversation = $this->modelInPast($space->conversations->first());
        $original_updated_at = $conversation->updated_at;

        $this->signInWithEvents($space->buyerUser());

        $this->patch(
            route('exchange-spaces.conversations.update', ['id' => $conversation->space->id, 'c_id' => $conversation->id]),
            ['body' => $body = $this->faker->paragraphs(3, true)]
        );

        $this->assertTimestampUpdated($conversation, $original_updated_at);
    }

    /**
    * @test
    */
    public function it_updates_the_timestamp_when_deleting_an_inquiry_message()
    {
        $this->signInDeveloperWithEvents();
        $space = $this->inquiryInPast();
        $message = factory('App\Message')->create(['conversation_id' => $space->conversations->first()->id]);
        $space = $this->modelInPast($this->modelInPast($space));
        $original_updated_at = $space->updated_at;

        $this->json('DELETE', route('conversation.message.destroy', ['id' => $message->id]));

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
     * @test
     */
    public function it_updates_the_timestamp_when_deleting_a_space_message()
    {
        $this->signInDeveloperWithEvents();
        $space = $this->spaceInPast();
        $message = factory('App\Message')->create(['conversation_id' => $space->conversations->first()->id]);
        $space = $this->modelInPast($this->modelInPast($space));
        $original_updated_at = $space->updated_at;

        $this->json('DELETE', route('conversation.message.destroy', ['id' => $message->id]));

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
     * @test
     */
    public function it_updates_the_conversation_timestamp_when_deleting_an_inquiry_message()
    {
        $this->signInDeveloperWithEvents();
        $space = $this->inquiryInPast();
        $message = factory('App\Message')->create(['conversation_id' => $space->conversations->first()->id]);

        $conversation = $this->modelInPast($space->conversations->first());
        $original_updated_at = $conversation->updated_at;

        $this->json('DELETE', route('conversation.message.destroy', ['id' => $message->id]));

        $this->assertTimestampUpdated($conversation, $original_updated_at);
    }

    /**
     * @test
     */
    public function it_updates_the_conversation_timestamp_when_deleting_a_space_message()
    {
        $this->signInDeveloperWithEvents();
        $space = $this->spaceInPast();
        $message = factory('App\Message')->create(['conversation_id' => $space->conversations->first()->id]);
        $conversation = $this->modelInPast($space->conversations->first());
        $original_updated_at = $conversation->updated_at;

        $this->json('DELETE', route('conversation.message.destroy', ['id' => $message->id]));

        $this->assertTimestampUpdated($conversation, $original_updated_at);
    }

    /**
    * @test
    */
    public function it_updates_the_timestamp_when_enabling_historical_financials_access()
    {
        $space = $this->spaceInPast();
        $space->historical_financials_public = false;
        $space->save();
        $space = $this->modelInPast($space);
        $original_updated_at = $space->updated_at;

        $this->signInWithEvents($space->sellerUser());

        $this->assertFalse($space->historical_financials_public);
        $this->post(
            route('exchange-spaces.historical-financials.update', ['id' => $space->id]),
            ['public' => true]
        );

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
    * @test
    */
    public function it_updates_the_timestamp_when_disabling_historical_financials_access()
    {
        $space = $this->spaceInPast();
        $space->historical_financials_public = true;
        $space->save();
        $space = $this->modelInPast($space);
        $original_updated_at = $space->updated_at;

        $this->signInWithEvents($space->sellerUser());

        $this->assertTrue($space->historical_financials_public);
        $this->post(
            route('exchange-spaces.historical-financials.update', ['id' => $space->id]),
            ['public' => false]
        );

        $this->assertTimestampUpdated($space, $original_updated_at);
    }

    /**
     * @test
     */
    public function it_updates_the_timestamp_for_all_when_updating_historical_financials()
    {
        $listing = $this->addListingSpacesInPast(factory('App\Listing')->create());
        $original_updated_ats = $listing->spaces->pluck('updated_at');
        factory('App\HistoricalFinancial')->create([
            'listing_id' => $listing->id,
            'year' => Carbon::now()->subYear()->format('Y'),
        ]);

        $this->assertTimestampsUpdated($listing->spaces, $original_updated_ats);
    }

    /**
     * @test
     */
    public function it_updates_the_timestamp_for_all_when_updating_revenues()
    {
        $listing = $this->addListingSpacesInPast(factory('App\Listing')->create());
        $original_updated_ats = $listing->spaces->pluck('updated_at');
        factory('App\Revenue')->create(['listing_id' => $listing->id]);

        $this->assertTimestampsUpdated($listing->spaces, $original_updated_ats);
    }

    /**
     * @test
     */
    public function it_updates_the_timestamp_for_all_when_updating_revenue_lines()
    {
        $listing = factory('App\Listing')->create();
        $revenue = factory('App\Revenue')->create(['listing_id' => $listing->id]);

        $listing = $this->addListingSpacesInPast($listing);
        $original_updated_ats = $listing->spaces->pluck('updated_at');
        factory('App\RevenueLine')->create(['revenue_id' => $revenue->id, 'year' => Carbon::now()->subYear()->format('Y')]);

        $this->assertTimestampsUpdated($listing->spaces, $original_updated_ats);
    }

    /**
     * @test
     */
    public function it_updates_the_timestamp_for_all_when_updating_expenses()
    {
        $listing = $this->addListingSpacesInPast(factory('App\Listing')->create());
        $original_updated_ats = $listing->spaces->pluck('updated_at');
        factory('App\Expense')->create(['listing_id' => $listing->id]);

        $this->assertTimestampsUpdated($listing->spaces, $original_updated_ats);
    }

    /**
     * @test
     */
    public function it_updates_the_timestamp_for_all_when_updating_expense_lines()
    {
        $listing = factory('App\Listing')->create();
        $expense = factory('App\Expense')->create(['listing_id' => $listing->id]);

        $listing = $this->addListingSpacesInPast($listing);
        $original_updated_ats = $listing->spaces->pluck('updated_at');
        factory('App\ExpenseLine')->create(['expense_id' => $expense->id, 'year' => Carbon::now()->subYear()->format('Y')]);

        $this->assertTimestampsUpdated($listing->spaces, $original_updated_ats);
    }

    /**
     * @test
     */
    public function it_updates_the_timestamp_for_all_when_updating_listing()
    {
        $listing = $this->addListingSpacesInPast(factory('App\Listing')->create());
        $original_updated_ats = $listing->spaces->pluck('updated_at');

        $listing->title = 'New Title';
        $listing->save();

        $this->assertTimestampsUpdated($listing->spaces, $original_updated_ats);
    }

    /**
     * Creates multiple spaces with the updated_at and created_at in the past for a given listing.
     *
     * @param Listing $listing
     * @param integer $count
     * @return Listing
     */
    protected function addListingSpacesInPast(Listing $listing, $count = 3)
    {
        for ($i = 0; $i < $count; $i++) {
            $space = $this->spaceInPast();
            $space->user_id = $listing->user_id;
            $space->listing_id = $listing->id;
            $space->save();
            $seller = $space->sellerMember();
            $seller->user_id = $listing->user_id;
            $seller->save();
            $this->modelInPast($space);
        }

        return $listing->fresh();
    }

    /**
     * Creates a space with the updated_at and created_at in the past.
     *
     * @param App\User $seller
     * @param App\User $buyer
     * @param boolean $inquiry
     * @return ExchangeSpace
     */
    protected function spaceInPast($seller = null, $buyer = null, $inquiry = false)
    {
        if ($inquiry) {
            $space = $this->createInquiryConversation()->space;
        } else {
            $space = $this->createSpaceConversation([], $seller, $buyer)->space;
        }

        return $this->modelInPast($space);
    }

    /**
     * Sets a model with the updated_at and created_at in the past.
     *
     * @param App\User $seller
     * @param App\User $buyer
     * @return Model
     */
    protected function modelInPast(Model $model)
    {
        $model->updated_at = $model->freshTimestamp()->subDay();
        $model->created_at = $model->freshTimestamp()->subDays(5);
        $model->save();

        return $model->fresh();
    }

    /**
     * Undocumented function
     *
     * @param App\User $seller
     * @param App\User $buyer
     * @return void
     */
    protected function inquiryInPast($seller = null, $buyer = null)
    {
        return $this->spaceInPast($seller, $buyer, $inquiry = true);
    }

    /**
     * Asserts that the model timestamp has been updated from the original
     *
     * @param Model $space
     * @param Carbon $original_updated_at
     * @return void
     */
    protected function assertTimestampUpdated(Model $model, Carbon $original_updated_at)
    {
        $model = $model->fresh();
        $this->assertNotEquals($original_updated_at, $model->updated_at);
        $this->assertTrue($model->updated_at->gt($original_updated_at));
    }

    /**
     * Asserts that all models timestamps have been updated from the original
     *
     * @param Collection $models
     * @param Collection $original_updated_ats
     * @return void
     */
    protected function assertTimestampsUpdated(Collection $models, Collection $original_updated_ats)
    {
        $models->map->fresh()->each(function ($model, $key) use ($original_updated_ats) {
            $this->assertTimestampUpdated($model, $original_updated_ats->get($key));
        });
    }
}
