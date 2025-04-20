<?php

namespace App\Support\TableFilters;

use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use App\Support\TableFilters\ExchangeSpaceFilter;

class BuyerInquiryConversationsFilter extends TableFilter
{
    protected function all()
    {
        $results = $this->query()->get()
        ->map(function ($space) {
            return $this->pluckInquiryConversations(
                $space->conversations
            );
        })
        ->reject->isEmpty()
        ->map->load('space.listing')
        ->flatten();

        // Search
        $results = $this->search($results);

        // Sort
        $results = $this->sort($results);

        return $results->values();
    }

    public function allPaginated()
    {
        $all = $this->all();

        return $all->paginate($all->count() > 0 ? $all->count() : 1);
    }

    /**
     * Gets the filter paginated.
     *
     * @param  integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function paginated($perPage = null)
    {
        $perPage = is_null($perPage) ? $results->count() : $perPage;
        $perPage = ($perPage <= 0) ? 1 : $perPage;
        return $results->all()->paginate($perPage);
    }

    protected function pluckInquiryConversations($conversations)
    {
        $inquiries = $conversations->sortBy('created_at')->where('is_inquiry', true);

        return $inquiries->isEmpty() ? collect([]) : $inquiries->splice(0, 1);
    }

    /**
     * Sort the conversations.
     *
     * @param \Illuminate\Support\Collection $conversations
     * @return \Illuminate\Support\Collection
     */
    protected function sort($conversations)
    {
        switch ($this->getSortKey()) {
            case 'updated_at':
                $conversations = $conversations
                ->sortBy(function ($conversation) {
                    return optional($conversation->messages()
                           ->latest()->first())->updated_at;
                });
                break;

            case 'business_name':
                $conversations = $conversations
                ->sortBy(function ($conversation) {
                    return $conversation->space->listing->business_name;
                });
                break;

            case 'buyer_name':
                $conversations = $conversations
                ->sortBy(function ($conversation) {
                    return $conversation->space->buyerUser()->name;
                });
                break;
        }

        // Reverse the conversations if needed.
        if ($this->getSortOrder() !== 'asc') {
            return $conversations->reverse();
        }

        return $conversations;
    }


    /**
     * Search the conversations.
     *
     * @param \Illuminate\Support\Collection $conversations
     * @return \Illuminate\Support\Collection
     */
    protected function search($conversations)
    {
        $search = request()->get('search');
        if (!$search) {
            return $conversations;
        }

        // Lower case the search since we do not want to check case.
        $search = strtolower($search);

        // Filter the conversations to see if we get a search match.
        return $conversations->filter(function ($conversation) use ($search) {
            // Check business name.
            $businessName = $conversation->space->listing->business_name;
            if (str_contains(strtolower($businessName), $search)) {
                return true;
            }

            // Check messages
            $messages = $conversation->messages()
                        ->pluck('body')->implode(' ');
            if (str_contains(strtolower($messages), $search)) {
                return true;
            }

            // Check buyer name
            $buyerName = $conversation->space->buyerUser()->name;
            if (str_contains(strtolower($buyerName), $search)) {
                return true;
            }

            // Check buyer email
            $buyerEmail = $conversation->space->buyerUser()->email;
            if (str_contains(strtolower($buyerEmail), $search)) {
                return true;
            }

            return false;
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function query()
    {
        // First get the member exchange space ids.
        $exchangeSpaceIds = ExchangeSpaceMember::ofCurrentUser()
        ->approved()
        ->get()
        ->pluck('exchange_space_id')
        ->toArray();

        return ExchangeSpace::whereIn('id', $exchangeSpaceIds)
        ->with('conversations', 'conversations.messages', 'listing', 'members.user');
    }

    /**
     * {@inheritdoc}
     */
    public function getSortKey()
    {
        return collect([
            'newest' => 'updated_at',
            'oldest' => 'updated_at',
            'listing_title_az' => 'business_name',
            'listing_title_za' => 'business_name',
            'inquirer_az' => 'buyer_name',
            'inquirer_za' => 'buyer_name',
        ])
        ->get($this->request->get('sortKey', 'newest'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getSortOrder()
    {
        $isDescending = collect([
            'newest',
            'listing_title_za',
            'inquirer_za',
        ])->contains($this->request->get('sortKey', 'newest'));

        return $isDescending ? 'desc' : 'asc';
    }
}
