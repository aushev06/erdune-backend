<?php

namespace App\Blog\Requests;

use App\Blog\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class SaveCommentRequest extends FormRequest
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
            'text'    => 'required',
            'post_id' => 'required',
            'user_reply_id' => 'exists:users,id',
            'parent_id' => 'exists:comments,id'
        ];
    }
}
