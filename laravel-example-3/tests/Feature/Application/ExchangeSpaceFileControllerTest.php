<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceFileControllerTest extends TestCase
{
    use HasExchangeSpaceCreators, RefreshDatabase;

    /**
    * @test
    */
    public function it_allows_a_file_owner_to_delete_a_file()
    {
        // Setup exchange space.
        $seller = $this->signInWithEvents();
        $conversation = $this->createSpaceConversation([], $seller, null);

        // Add 2 files.
        $conversation = $this->addUploadsToConversation($conversation, 2);
        $media = $conversation->space->getMedia();
        $delete_id = $media->first()->id;
        $this->assertCount(2, $media);

        // Delete the file
        $response = $this->post(
            route('exchange-spaces.file.destroy', ['id' => $conversation->space->id]),
            ['media_id' => $delete_id]
        );

        // Make sure everything went ok
        $response = $response->status(302);
        $media = $conversation->fresh()->space->getMedia();
        $this->assertCount(1, $media);
        $media->each(function ($file) use ($delete_id) {
            $this->assertNotEquals($delete_id, $file->id);
        });
    }

    /**
    * @test
    */
    public function it_does_not_allow_a_file_owner_to_delete_a_file()
    {
        // Setup exchange space.
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $conversation = $this->createSpaceConversation([], $seller, $buyer);

        // Have the buyer add 2 files.
        $this->signInWithEvents($buyer);
        $conversation = $this->addUploadsToConversation($conversation, 2);
        $media = $conversation->space->getMedia();
        $delete_id = $media->first()->id;
        $this->assertCount(2, $media);

        // Sign the seller back in and try to delete a file
        $this->signInWithEvents($seller);

        // Make sure we only have 2 files.
        $this->assertCount(2, $conversation->fresh()->space->getMedia());

        // Delete the file
        $response = $this->post(
            route('exchange-spaces.file.destroy', ['id' => $conversation->space->id]),
            ['media_id' => $delete_id]
        );

        // Make sure everything went ok
        $response = $response->status(403);
        $this->assertCount(2, $conversation->fresh()->space->getMedia());
    }
}
