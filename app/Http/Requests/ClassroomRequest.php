<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string|max:255',
            'section'=>'nullable|string|max:255',
            'subject'=>'nullable|string|max:255',
            'room'=>'nullable|string|max:255',
            'cover_image'=> [
                'nullable',
                'image',
                'max:1024',
                Rule::dimensions([
                    'min_width'=>600,
                    'min_height'=>300, 
                ]),
            ],
    ];
     
    }

    public function messages():array{
        return[
            'name.required'=>'The name is required',
            'cover_image.max'=>'Image size is greate than 1M', 
        ];
    }
}
