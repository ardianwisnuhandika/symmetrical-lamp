<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')->id;
        
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $categoryId,
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
            'icon.max' => 'Icon maksimal 100 karakter.',
        ];
    }
}
