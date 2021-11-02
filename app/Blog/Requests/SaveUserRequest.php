<?php

namespace App\Blog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserRequest extends FormRequest
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
        $rules = [
            'email' => 'unique:users,email,' . $this->user()->id,
            'login' => 'unique:users,login,' . $this->user()->id,
            'ready_for_work' => 'boolean',
            'links' => 'array',
            'position' => 'nullable',
            'avatar' => 'nullable'
        ];

        if (empty($this->user()->position) && $this->post('ready_for_work')) {
            $rules['position'] = ['required'];
        }

        return $rules;
    }

    public function validationData()
    {
        $data = $this->all();

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = htmlentities($value);
            }
        }

        return $data;
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'position.required' => 'Заполните информацию о своей позиции'
        ]);
    }

}
