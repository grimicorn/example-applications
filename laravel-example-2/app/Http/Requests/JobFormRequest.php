<?php

namespace App\Http\Requests;

use App\Job;
use App\Enums\ArtStatus;
use App\Enums\JobType;
use App\Enums\WipStatus;
use App\Enums\PickStatus;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Database\Eloquent\Model;
use App\Enums\JobFlag;

class JobFormRequest extends BaseFormRequest
{
    /**
     * @inheritDoc
     */
    protected function routeModelKey(): string
    {
        return 'job';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isPolicyAction('delete')) {
            return [];
        }

        $shared = [
            'type' => ['nullable', Rule::in(JobType::keys())],
            'machine_id' => 'nullable|numeric|exists:machines,id',
            'sort_order' => 'nullable|numeric',
            'duration' => [
                'nullable',
                'numeric',
                Rule::requiredIf(function () {
                    return $this->isSpecialtyJob();
                }),
            ],
            'flag' => ['nullable', Rule::in(JobFlag::keys()),],
        ];
        if ($this->isPolicyAction('create')) {
            return $shared;
        }

        return array_merge($shared, [
            'print_detail' => 'nullable|file',
            'wip_status' => ['nullable', Rule::in(WipStatus::keys()),],
            'art_status' => ['nullable', Rule::in(ArtStatus::keys()),],
            'priority' => 'nullable|numeric',
            'pick_status' => ['nullable', Rule::in(PickStatus::keys()),],
            'notes' => 'nullable',
            'start_at' => 'nullable|date',
            'due_at' => 'nullable|date',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'garment_ready' => 'nullable|boolean',
            'screens_ready' => 'nullable|boolean',
            'ink_ready' => 'nullable|boolean',
        ]);
    }

    /**
     * Creates a new App\Job.
     *
     * @return Model
     */
    protected function createModel(): Model
    {
        $params = collect($this->validated());

        $model = Job::create(
            $params->except(['print_detail'])->toArray()
        );

        $model->updatePrintDetailFromAttributes($this->validated());

        return $model->freshFromUuid();
    }

    /**
     * Updates an existing App\Job.
     *
     * @param Model $model
     *
     * @return Model
     */
    protected function updateModel(?Model $model = null): Model
    {
        $params = collect($this->validated());

        $model->update(
            $params->except(['print_detail'])->toArray()
        );

        $model->updatePrintDetailFromAttributes($this->validated());

        return $model->fresh();
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        if (!parent::authorize()) {
            return false;
        }

        if ($this->isPolicyAction('create')) {
            return $this->isSpecialtyJob();
        }

        return true;
    }

    protected function jsonRespondWith(): array
    {
        return array_filter([
            'media' => $this->getModel()->fresh()->print_detail,
        ]);
    }

    protected function isSpecialtyJob()
    {
        return $this->jobTypeValid() and !$this->jobTypeIsDefault();
    }

    protected function jobTypeValid()
    {
        return JobType::isValidKey(intval($this->get('type')));
    }

    protected function jobTypeIsDefault()
    {
        return  intval($this->get('type')) === JobType::DEFAULT;
    }
}
