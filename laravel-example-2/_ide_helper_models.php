<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Domain{
/**
 * App\Domain\StoredEvent
 *
 * @property int $id
 * @property string|null $aggregate_uuid
 * @property string $event_class
 * @property array $event_properties
 * @property array $meta_data
 * @property string $created_at
 * @property-read mixed $event
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\EventProjector\Models\StoredEvent startingFrom($storedEventId)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\EventProjector\Models\StoredEvent uuid($uuid)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent whereAggregateUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent whereEventClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent whereEventProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\StoredEvent whereMetaData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\EventProjector\Models\StoredEvent withMetaDataAttributes()
 */
	class StoredEvent extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Media
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $extension
 * @property-read mixed $human_readable_size
 * @property-read mixed $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Media[] $model
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\MediaLibrary\Models\Media ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereUpdatedAt($value)
 */
	class Media extends \Eloquent {}
}

namespace App{
/**
 * App\MachinePod
 *
 * @property int $id
 * @property int|null $pod_id
 * @property int|null $machine_id
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Machine|null $machine
 * @property-read \App\Pod|null $pod
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod whereMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod wherePodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MachinePod whereUpdatedAt($value)
 */
	class MachinePod extends \Eloquent {}
}

namespace App{
/**
 * App\Board
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Pod[] $pods
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Board onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Board whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Board withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Board withoutTrashed()
 */
	class Board extends \Eloquent {}
}

namespace App{
/**
 * App\BoardPod
 *
 * @property int $id
 * @property int|null $pod_id
 * @property int|null $board_id
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Board|null $board
 * @property-read \App\Pod|null $pod
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod wherePodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoardPod whereUpdatedAt($value)
 */
	class BoardPod extends \Eloquent {}
}

namespace App{
/**
 * App\Pod
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Board[] $boards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Machine[] $machines
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pod newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Pod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pod query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pod withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Pod withoutTrashed()
 */
	class Pod extends \Eloquent {}
}

namespace App{
/**
 * App\Job
 *
 * @property int $id
 * @property int|null $machine_id
 * @property string $uuid
 * @property string|null $customer_name
 * @property string|null $work_order_number
 * @property string|null $control_number_1
 * @property int|null $screens_1
 * @property string|null $placement_1
 * @property string|null $imported_placement_1
 * @property string|null $control_number_2
 * @property int|null $screens_2
 * @property string|null $placement_2
 * @property string|null $imported_placement_2
 * @property string|null $control_number_3
 * @property int|null $screens_3
 * @property string|null $placement_3
 * @property string|null $imported_placement_3
 * @property string|null $control_number_4
 * @property int|null $screens_4
 * @property string|null $placement_4
 * @property string|null $imported_placement_4
 * @property string|null $product_location_wc
 * @property string|null $wip_status
 * @property string|null $sku_number
 * @property string|null $art_status
 * @property int|null $priority
 * @property string|null $pick_status
 * @property int|null $total_quantity
 * @property int|null $small_quantity
 * @property int|null $medium_quantity
 * @property int|null $large_quantity
 * @property int|null $xlarge_quantity
 * @property int|null $2xlarge_quantity
 * @property int|null $other_quantity
 * @property int|null $complete_count
 * @property int|null $issue_count
 * @property string|null $notes
 * @property int $type
 * @property int|null $duration
 * @property int $sort_order
 * @property int|null $flag
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property \Illuminate\Support\Carbon|null $due_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Machine|null $machine
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Media[] $media
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Job onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job where2xlargeQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereArtStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereCompleteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereControlNumber1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereControlNumber2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereControlNumber3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereControlNumber4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereDueAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereImportedPlacement1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereImportedPlacement2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereImportedPlacement3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereImportedPlacement4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereIssueCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereLargeQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereMediumQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereOtherQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job wherePickStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job wherePlacement1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job wherePlacement2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job wherePlacement3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job wherePlacement4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereProductLocationWc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereScreens1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereScreens2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereScreens3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereScreens4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereSkuNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereSmallQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereTotalQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereWipStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereWorkOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereXlargeQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Job withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Job withoutTrashed()
 */
	class Job extends \Eloquent {}
}

namespace App{
/**
 * App\Machine
 *
 * @property int $id
 * @property string $uuid
 * @property string|null $external_machine_id
 * @property string|null $name
 * @property string|null $description
 * @property int $status
 * @property bool $has_auto_unloader
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Job[] $jobs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Pod[] $pods
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Machine onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereExternalMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereHasAutoUnloader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Machine whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Machine withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Machine withoutTrashed()
 */
	class Machine extends \Eloquent {}
}

