<?php

namespace App\Support\TableFilters;

use App\User;
use Illuminate\Http\Request;
use App\Support\TableFilters\TableFilter;

class UserTableFilter extends TableFilter
{
    /**
     * {@inheritDoc}
     */
    public function paginated($perPage = 10)
    {
        $users = $this->query()->get();
        $users = $this->sortByActiveListings($users);
        $users = $this->sortByPrimaryRoles($users);
        $users = collect($users->values());
        $users = $users->map(function ($user) {
            $user->active_listings_count = $user->active_listings_count;

            return $user;
        });

        return $users->paginate($perPage);
    }

    /**
     * Sorts the users by their primary role(s)
     *
     * @param [type] $users
     * @return void
     */
    protected function sortByPrimaryRoles($users)
    {
        if ($this->getSortKey() !== 'primary_roles') {
            return $users;
        }

        $users = $users->sortBy(function ($user) {
            $roles = is_array($user->primary_roles) ? $user->primary_roles : [];
            $roles = collect($roles)->sort()->implode(',');

            return $roles;
        });

        if ($this->getSortOrder() !== 'asc') {
            $users = $users->reverse();
        }

        return $users;
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

        // Since we may have to order by items in different places in the database.
        // this will allow for placing the order by in the correct spot.
        if ($this->isUserTableSortKey($sortKey)) {
            $query = User::orderBy($sortKey, $sortOrder);
        } else {
            // This is the default query order if not ordering by.
            $query = User::orderBy('created_at', 'asc');
        }

        // If search is not empty lets try to find the value somewhere.
        if ($search = $this->request->get('search')) {
            $query->where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('last_name', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('primary_roles', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%");
        }

        return $query;
    }

    /**
     * Sort the users by active listings.
     */
    protected function sortByActiveListings($users)
    {
        if ('active_listings' !== $this->getSortKey()) {
            return $users;
        }

        if ($this->getSortOrder() === 'asc') {
            return $users->sortBy('active_listings_count');
        }

        return $users->sortByDesc('active_listings_count');
    }

    /**
     * Checks if the sort key is in the users table.
     *
     * @param  string  $key
     *
     * @return boolean
     */
    protected function isUserTableSortKey($key)
    {
        return in_array($key, [
            'last_name',
            'email',
            'last_login',
        ]);
    }

    /**
     * Gets the sort key.
     *
     * @return string
     */
    public function getSortKey()
    {
        $key = $this->request->get('sortKey', 'last_name');

        // The columns in the table do not always map to columns so
        // in those situations we will need to transform the key.
        switch ($key) {
            case 'name':
                return 'last_name';
                break;

            case 'roles_selected':
                return 'primary_roles';
                break;

            case 'email_address':
                return 'email';
                break;

            default:
                return $key;
                break;
        }
    }
}
