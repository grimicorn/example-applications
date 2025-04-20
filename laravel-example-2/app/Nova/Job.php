<?php

namespace App\Nova;

use App\Enums\ArtStatus;
use App\Enums\JobType;
use App\Enums\WipStatus;
use App\Enums\PickStatus;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Job extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Job';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'work_order_number',
        'control_number_1',
        'screens_1',
        'placement_1',
        'control_number_2',
        'screens_2',
        'placement_2',
        'control_number_3',
        'screens_3',
        'placement_3',
        'control_number_4',
        'screens_4',
        'placement_4',
        'product_location_wc',
        'wip_status',
        'sku_number',
        'art_status',
        'priority',
        'pick_status',
        'total_quantity',
        'small_quantity',
        'medium_quantity',
        'large_quantity',
        'xlarge_quantity',
        '2xlarge_quantity',
        'other_quantity',
        'complete_count',
        'issue_count',
        'notes',
        'type',
        'sort_order',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Machine'),
            Text::make('Work Order Number', 'work_order_number')
                ->rules('required'),
            Text::make('Control Number 1', 'control_number_1')
                ->hideFromIndex()
                ->nullable(),
            Number::make('Screens 1', 'screens_1')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Text::make('Placement 1', 'placement_1')
                ->hideFromIndex()
                ->nullable(),
            Text::make('Control Number 2', 'control_number_2')
                ->hideFromIndex()
                ->nullable(),
            Number::make('Screens 2', 'screens_2')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Text::make('Placement 2', 'placement_2')
                ->hideFromIndex()
                ->nullable(),
            Text::make('Control Number 3', 'control_number_3')
                ->hideFromIndex()
                ->nullable(),
            Number::make('Screens 3', 'screens_3')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Text::make('Placement 3', 'placement_3')
                ->hideFromIndex()
                ->nullable(),
            Text::make('Control Number 4', 'control_number_4')
                ->hideFromIndex()
                ->nullable(),
            Number::make('Screens 4', 'screens_4')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Text::make('Placement 4', 'placement_4')
                ->hideFromIndex()
                ->nullable(),
            Text::make('Product Location Wc', 'product_location_wc')
                ->hideFromIndex()
                ->nullable(),
            Select::make('WIP Status', 'wip_status')
                ->options(WipStatus::novaFieldSelectOptions())
                ->rules('required', Rule::in(WipStatus::keys()))
                ->sortable(),
            Text::make('Sku Number', 'sku_number')
                ->hideFromIndex()
                ->nullable(),
            Select::make('Art Status', 'art_status')
                ->options(ArtStatus::novaFieldSelectOptions())
                ->rules('required', Rule::in(ArtStatus::keys()))
                ->sortable(),
            Number::make('Priority', 'priority')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Select::make('Pick Status', 'pick_status')
                ->options(PickStatus::novaFieldSelectOptions())
                ->rules('required', Rule::in(PickStatus::keys()))
                ->sortable(),
            Number::make('Total Quantity', 'total_quantity')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Number::make('Small Quantity', 'small_quantity')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Number::make('Medium Quantity', 'medium_quantity')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Number::make('Large Quantity', 'large_quantity')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Number::make('Xlarge Quantity', 'xlarge_quantity')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Number::make('2xlarge Quantity', '2xlarge_quantity')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Number::make('Other Quantity', 'other_quantity')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Number::make('Complete Count', 'complete_count')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Number::make('Issue Count', 'issue_count')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0'),
            Trix::make('Notes', 'notes')
                ->hideFromIndex()
                ->nullable(),
            Select::make('Type', 'type')
                ->options(JobType::novaFieldSelectOptions())
                ->rules('nullable', Rule::in(JobType::keys()))
                ->nullable(),
            DateTime::make('Start At', 'due_at')
                ->nullable(),
            DateTime::make('Due At', 'due_at')
                ->nullable(),
            DateTime::make('Started At', 'started_at')
                ->nullable(),
            DateTime::make('Completed At', 'completed_at')
                ->nullable(),
            Number::make('Duration', 'duration')
                ->nullable()
                ->step(15)
                ->min(0)
                ->rules('nullable', 'numeric', 'gte:0')
                ->help('Duration in minutes.')
                ->sortable(),
            Number::make('Sort Order', 'sort_order')
                ->nullable()
                ->hideFromIndex()
                ->step(1)
                ->min(0)
                ->rules('required', 'numeric', 'gte:0'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new DownloadExcel,
        ];
    }
}
