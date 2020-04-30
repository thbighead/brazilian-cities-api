<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatePutPatchRequest extends FormRequest
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
            'acronym' => [
                'string',
                'size:2',
                Rule::unique('states', 'acronym')->ignore($this->route('state'))
            ],
            'name' => [
                'string',
                'min:3',
                Rule::unique('states', 'name')->ignore($this->route('state'))
            ],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('acronym')) $this->merge(['acronym' => strtoupper($this->get('acronym'))]);
    }
}
