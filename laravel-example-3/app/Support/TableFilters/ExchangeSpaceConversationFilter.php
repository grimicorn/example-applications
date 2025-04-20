<?php

namespace App\Support\TableFilters;

use App\Conversation;
use App\ExchangeSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\TableFilters\TableFilter;

class ExchangeSpaceConversationFilter extends TableFilter
{
    protected $space;

    public function __construct(Request $request, $space)
    {
        $this->space = $space;
        parent::__construct($request);
    }

    /**
     * Gets the filter paginated.
     *
     * @param  integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function paginated($perPage = 10)
    {
        return $this->search($this->query()->get())
        ->filter(function ($conversation) {
            return $conversation->latest_message;
        })
        ->sortByDesc(function ($conversation) {
            return strtotime(optional($conversation->messages()->latest()->first())->updated_at);
        })->values()->paginate($perPage);
    }

    /**
     * Builds up the filter query.
     *
     * @return Builder
     */
    protected function query()
    {
        $sortOrder = $this->getSortOrder();
        $sortKey = $this->getSortKey();
        $query = $this->space->conversations();

        // Filter
        $query = $this->addResolveFilter($query);
        $query = $this->addCategoryFilter($query);

        return $query;
    }

    /**
     * Searchs conversations.
     *
     * @param Collection $conversations
     * @return void
     */
    protected function search($conversations)
    {
        if (!$this->request->has('search')) {
            return $conversations;
        }

        $search = $this->request->get('search');
        return $conversations->filter(function ($conversation) use ($search) {
            if (str_contains($conversation->title, $search)) {
                return true;
            }

            $messages = $conversation->messages->filter(function ($message) use ($search) {
                $creator = optional($message->creator_member);
                if (str_contains(optional($creator->user)->name, $search)) {
                    return true;
                }

                if (str_contains($message->body, $search)) {
                    return true;
                }
            });

            return !$messages->isEmpty();
        });
    }

    /**
     * Adds filter for category of conversations.
     *
     * @param Builder $query
     * @return void
     */
    protected function addCategoryFilter($query)
    {
        if (!$this->request->has('category')) {
            return $query;
        }

        $query->where('category', intval($this->request->get('category')));

        return $query;
    }

    /**
     * Adds filter for resolved conversations.
     *
     * @param Builder $query
     * @return void
     */
    protected function addResolveFilter($query)
    {
        if (!$this->request->has('resolved')) {
            return $query;
        }

        $query->where('resolved', (bool) $this->request->get('resolved'));

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function getSortKey()
    {
        return $this->request->get('sortKey', 'created_at');
    }
}
