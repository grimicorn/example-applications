<?php

namespace App\Support\HistoricalFinancial;

use App\Revenue;
use App\Expense;
use App\RevenueLine;
use App\ExpenseLine;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

abstract class YearlyFinancials
{
    use HasYearlyDataHelpers;

    protected $type;
    protected $yearStart;
    protected $listing;
    protected $request;
    protected $models;
    protected $save;
    protected $lineModelClass;
    protected $modelClass;
    protected $previewAttributeKey;

    public function __construct($listing, $type, $save = false)
    {
        $this->save = $save;
        $this->yearStart = $listing->hfYearStart();
        $this->listing = $listing;
        $this->request = request();
        $this->type = $type;

        switch ($type) {
            case 'revenue':
                $this->models = $this->listing->revenues;
                $this->lineModelClass = RevenueLine::class;
                $this->modelClass = Revenue::class;
                $this->previewAttributeKey = 'previewRevenues';
                break;

            case 'expense':
                $this->models = $this->listing->expenses;
                $this->lineModelClass = ExpenseLine::class;
                $this->modelClass = Expense::class;
                $this->previewAttributeKey = 'previewExpenses';
                break;
        }
    }

    /**
     * Get model names.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getNames()
    {
        $names = collect($this->request->get('custom_name', []));
        return collect($names->get($this->type, []));
    }

    /**
     * Get model orders.
     *
     * @return array
     */
    protected function getOrders()
    {
        return $this->getNames()->keys();
    }

    /**
     * Removes models that no longer exist.
     *
     * @return void
     */
    protected function removeModels()
    {
        $orders = $this->getOrders()->toArray();

        $this->models->whereNotIn('order', $orders)->each(function ($model) {
            if ($this->save) {
                $model->lines->each->delete();
                $model->delete();
            }
        });

        return $this->models = $this->models->whereIn('order', $this->getOrders());
    }

    /**
     * Gets a model item by it's order.
     *
     * @param int $order
     * @return void
     */
    protected function modelByOrder($order)
    {
        return $this->models->where('order', $order)->first();
    }

    /**
     * Set the models.
     *
     * @return void
     */
    protected function setModels()
    {
        return $this->models = $this->getNames()->map(function ($name, $order) {
            $model = $this->modelByOrder($order);

            if (is_null($model)) {
                $model = new $this->modelClass;
            }

            $model->listing_id = $this->listing->id;
            $model->order = $order;
            $model->name = $name;

            if ($this->save) {
                $model->save();
            } else {
                $model->preview_id = is_null($model->id) ? "new_{$order}" : $model->id;
            }

            return $model;
        });
    }

    /**
     * Gets the request years.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRequestLinesByYear()
    {
        return r_collect($this->request->only(['year1', 'year2', 'year3', 'year4']))
        ->map(function ($request, $lineKey) {
            return $request->get($this->type);
        })->filter();
    }

    /**
     * Gets the request lines
     *
     * @return void
     */
    protected function getRequestLines()
    {
        $lines = collect([]);
        $range = $this->yearRange();
        foreach ($this->getRequestLinesByYear() as $yearKey => $year) {
            foreach ($year as $orderKey => $item) {
                $items = $lines->get($orderKey, collect([]));
                $items->put($range->get($yearKey), $item);
                $lines->put($orderKey, $items);
            }
        }

        return $lines->map->sortBy(function ($item, $key) {
            return intval($key);
        });
    }

    protected function setModelLineAmountByYear($groupModel, $amount, $year, $lineModelClass)
    {
        // Try to fine a line that matches the year if not create it.
        $yearDate = $this->createYear($year);
        $modelLine = $groupModel->lines->filter(function ($line) use ($year) {
            return intval($line->year->format('Y')) === intval($year);
        })->first();

        // Generate the new model line
        if (is_null($modelLine)) {
            $modelLine = new $lineModelClass;
            $modelIdColumn = "{$this->type}_id";
            $modelLine->$modelIdColumn = $groupModel->id;
            $modelLine->year = $yearDate;
        }

        // Add the preview model id so we can group them later
        if (!$this->save) {
            $modelLine->preview_model_id = $groupModel->preview_id;
        }

        // Always update the amount
        $amount = strtolower($amount) === 'nan' ? null : $amount;
        $modelLine->amount = is_null($amount) ? $amount : intval($amount);

        return $modelLine;
    }

    /**
     * Set the model lines.
     *
     * @param string $lineModelClass
     *
     * @return Collection
     */
    protected function setLines($lineModelClass)
    {
        return $this->getRequestLines()->map(function ($lines, $order) use ($lineModelClass) {
            $groupModel = $this->modelByOrder($order);

            return $lines->map(function ($amount, $year) use ($groupModel, $lineModelClass) {
                $line = $this->setModelLineAmountByYear($groupModel, $amount, $year, $lineModelClass);

                if ($this->save) {
                    $line->save();
                }

                return $line;
            })->values();
        });
    }

    /**
     * Fills the mdoel lines for the preview if needed.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function fillModelPreviewLines($lines)
    {
        if ($this->save) {
            return $this->models->map->fresh();
        }

        return $this->models->map(function ($model, $key) use ($lines) {
            $model->lines = $lines->filter(function ($line) use ($model) {
                $preview_model_id = $line->first()->preview_model_id;
                return $preview_model_id === $model->preview_id;
            })->first();

            return $model;
        });
    }

    /**
     * Save the model and model lines.
     *
     * @return void
     */
    public function save()
    {
        // First remove any model items that do not have an order.
        $this->removeModels();

        // Now update the models.
        $this->setModels();

        // Now set the model lines
        $lines = $this->setLines($this->lineModelClass);

        // Fill the preview lines
        $this->models = $this->fillModelPreviewLines($lines);

        return $this->models;
    }
}
