<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (bool) (auth()->user()->is_admin ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        $ignoreId = $category instanceof \App\Models\Category ? $category->getKey() : $category;

        return [
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'nama')->ignore($ignoreId),
            ],
        ];
    }

    /**
     * Validation messages in English.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Category name is required.',
            'nama.unique' => 'This category name is already taken.',
        ];
    }
}
