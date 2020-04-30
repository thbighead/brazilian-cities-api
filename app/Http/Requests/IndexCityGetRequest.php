<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexCityGetRequest extends FormRequest
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
            'city_name' => 'string|min:3',
            'order_by_city_created_at' => 'in:asc,desc',
            'order_by_city_id' => 'in:asc,desc',
            'order_by_city_name' => 'in:asc,desc',
            'order_by_city_updated_at' => 'in:asc,desc',
            'order_by_state_acronym' => 'in:asc,desc',
            'order_by_state_created_at' => 'in:asc,desc',
            'order_by_state_id' => 'in:asc,desc',
            'order_by_state_name' => 'in:asc,desc',
            'order_by_state_updated_at' => 'in:asc,desc',
            'page' => 'integer|min:1',
            'paginate' => 'integer|min:5',
            'state_acronym' => 'string|size:2',
            'state_name' => 'string|min:3',
            'with_relationship' => 'boolean',
        ];
    }
}
