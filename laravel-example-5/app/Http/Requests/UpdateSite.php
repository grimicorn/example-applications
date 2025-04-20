<?php

namespace App\Http\Requests;

use App\Site;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSite extends FormRequest
{
    protected $site;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->site = Site::find($this->route('site'))->first();

        return $this->site && $this->user()->can('update', $this->site);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|string',
            'sitemap_url' => 'required|string',
            'difference_threshold' => 'nullable|numeric',
        ];
    }

    public function persist()
    {
        $site = $this->route('site');
        $site->fill($this->validated());
        $site->save();

        return $site->fresh();
    }
}
