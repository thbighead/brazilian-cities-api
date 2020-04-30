<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'acronym' => 'required|string|size:2|unique:states,acronym',
            'name' => 'required|string|min:3',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('acronym')) $this->merge(['acronym' => strtoupper($this->get('acronym'))]);
    }
}
