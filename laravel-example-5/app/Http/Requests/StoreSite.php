<?php

namespace App\Http\Requests;

use App\Site;
use Illuminate\Foundation\Http\FormRequest;

class StoreSite extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Site::class);
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
        $site = new Site;
        $site->fill($this->validated());
        $site->user_id = $this->user()->id;
        $site->save();

        return $site;
    }
}
