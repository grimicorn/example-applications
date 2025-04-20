<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * The model for the request.
     *
     * @param Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The route model key.
     *
     * ```php
     * route('routePrefix.routeSuffix', ['routeModelKey' => $model])
     * ```
     */
    abstract protected function routeModelKey(): string;

    /**
     * Creates a new Illuminate\Database\Eloquent\Model.
     *
     * @return Model
     */
    abstract protected function createModel(): Model;

    /**
     * Updates an existing Illuminate\Database\Eloquent\Model.
     *
     * @param Model $model
     *
     * @return Model
     */
    abstract protected function updateModel(?Model $model = null): Model;

    /**
     * Deletes an existing Illuminate\Database\Eloquent\Model.
     *
     * @param Model $model
     *
     * @return Model
     */
    protected function deleteModel(?Model $model = null): Model
    {
        $model = $model ?? $this->getModel();
        $model->delete();

        return $model;
    }

    /**
     * The route name prefix
     *
     * ```php
     * route('routePrefix.routeSuffix', ['routeModelKey' => $model])
     * ```
     */
    protected function routeNamePrefix()
    {
        return Str::plural($this->routeModelKey());
    }

    /**
     * The fully namespaced class for the model.
     */
    protected function getModelClass()
    {
        $namespace = explode('\\', __NAMESPACE__)[0];
        $className = Str::studly($this->routeModelKey());

        return "{$namespace}\\{$className}";
    }

    /**
     * Model routes for redirecting upon success.
     */
    protected function modelRoutes()
    {
        return collect([
            'create' => redirect(route("{$this->routeNamePrefix()}.show", [
                $this->routeModelKey() => $this->getModel(),
            ])),
            'delete' => redirect(route("{$this->routeNamePrefix()}.index")),
        ]);
    }

    /**
     * Handles redirecting the user on success.
     */
    protected function responseRedirect()
    {
        return $this->modelRoutes()
            ->get($this->getPolicyAction(), back());
    }

    /**
     * The models user friendly name.
     */
    protected function modelUserFriendlyName()
    {
        $modelName = class_basename($this->getModelClass());
        $modelName = Str::kebab($modelName);
        $modelName = str_replace('-', ' ', $modelName);
        $modelName = Str::title($modelName);

        return $modelName;
    }

    /**
     * All status messages
     */
    protected function statusMessages()
    {
        $name = $this->modelUserFriendlyName();

        return collect([
            'create' => "{$name} created successfully!",
            'update' => "{$name} updated successfully!",
            'delete' => "{$name} deleted successfully!",
        ]);
    }

    /**
     * The status message for the current action.
     */
    protected function statusMessage()
    {
        return $this->statusMessages()->get($this->getPolicyAction());
    }

    /**
     * Get the request model.
     */
    protected function getModel()
    {
        $modelClass = $this->getModelClass();
        $modelKey = $this->routeModelKey();

        return $this->$modelKey ?? new $modelClass;
    }

    /**
     * Set the request model.
     */
    protected function setModel($model)
    {
        $modelKey = $this->routeModelKey();
        $this->$modelKey = $model;

        return $this;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can(
            $this->getPolicyAction(),
            $this->getModel()
        );
    }

    /**
     * Persist the model.
     */
    public function persist(?Model $model = null)
    {
        if ($this->isPolicyAction('create')) {
            $this->setModel($this->createModel());
            return $this;
        }

        if ($this->isPolicyAction('update')) {
            $this->setModel($this->updateModel($model));
            return $this;
        }

        if ($this->isPolicyAction('delete')) {
            $this->deleteModel($model);
            return $this;
        }

        return $this;
    }

    /**
     * Respond once complete.
     */
    public function respond()
    {
        if ($this->expectsJson()) {
            return [
                'data' => array_merge([
                    'model' => $this->getModel(),
                    'status' => $this->statusMessage(),
                ], $this->jsonRespondWith()),
            ];
        }

        return $this->responseRedirect()
            ->with('status', $this->statusMessage());
    }

    protected function jsonRespondWith(): array
    {
        return [];
    }

    /**
     * Checks for a given policy action.
     */
    protected function isPolicyAction($action)
    {
        return $this->getPolicyAction() === $action;
    }

    /**
     * Gets the current policy action.
     */
    protected function getPolicyAction()
    {
        $action = explode('@', $this->route()->getActionName())[1] ?? '';

        return collect([
            'index' => 'viewAny',
            'store' => 'create',
            'destroy' => 'delete',
        ])->get($action, $action);
    }
}
