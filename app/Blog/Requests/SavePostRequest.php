<?php

namespace App\Blog\Requests;

use App\Blog\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SavePostRequest
 *
 * @property string $body
 * @property string $title
 *
 * @package App\Blog\Requests
 */
class SavePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->status === StatusEnum::STATUS_ACTIVE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'body' => 'required',
            'themes.*.name' => 'required',
            'category.id' => 'exists:categories,id'

        ];
    }
}
