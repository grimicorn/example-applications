<?php

use App\BusinessCategory;
use Illuminate\Database\Seeder;
use App\Support\HasBusinessCategories;

class BusinessCategoriesSeeder extends Seeder
{
    use HasBusinessCategories;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the parents first.
        $parentIds = $this->createParents();

        // Then create the children.
        $this->createChildren($parentIds);
    }

    /**
     * Creates the children and associates them to their parents.
     *
     * @param  array $parentIds
     *
     * @return array
     */
    protected function createChildren($parentIds)
    {
        return  $this->getBusinessCategoryChildren()->map(function ($children, $parentSlug) use ($parentIds) {
            $parentId = $parentIds->get($parentSlug, 0);
            return $children->map(function ($child) use ($parentId) {
                return $this->firstOrCreate($child, $parentId);
            });
        });
    }

    /**
     * Creates the parents.
     *
     * @return array
     */
    protected function createParents()
    {
        return $this->getBusinessCategoryParents()->map(function ($parent) {
            return $this->firstOrCreate($parent);
        });
    }

    /**
     * Creates a row/
     *
     * @param  string  $label
     * @param  integer $parentId
     *
     * @return int
     */
    protected function firstOrCreate($label, $parentId = 0)
    {
        return BusinessCategory::firstOrCreate([
            'label' => $label,
            'slug' => str_slug($label, '-'),
            'parent_id' => $parentId,
        ])->id;
    }
}
