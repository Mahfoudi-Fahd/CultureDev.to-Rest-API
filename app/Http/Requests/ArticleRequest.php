<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required','max:30'],    
            'description' => 'required',    
            'content' => 'required',    
            'user_id' => ['required', 'integer'],    
            'category_id' => ['required', 'integer'],    
            'tag_id' => ['required', 'array'],      
            'tag_id.*' => ['integer','exists:tags.id'],    
        ];
    }
}
