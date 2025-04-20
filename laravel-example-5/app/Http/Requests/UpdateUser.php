<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use App\Events\BroadcastingModelEvent;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('user') && $this->user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'avatar' => 'nullable|image',
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }

    public function validated()
    {
        $validated = parent::validated();

        return collect($validated)->except(['password', 'avatar'])->toArray();
    }

    public function persist()
    {
        $user = $this->route('user');
        $user->fill($this->validated());

        if ($this->get('password')) {
            $user->password = Hash::make($this->get('password'));
        }

        if ($this->has('avatar')) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            event(new BroadcastingModelEvent($user, 'updated'));
        }

        $user->save();

        return $user->fresh();
    }
}
