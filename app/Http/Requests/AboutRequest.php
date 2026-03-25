<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
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
        $rules = [
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ru' => ['required', 'string', 'max:255'],
            'title.tm' => ['required', 'string', 'max:255'],
            'body' => ['required', 'array'],
            'body.en' => ['required'],
            'body.ru' => ['required'],
            'body.tm' => ['required'],
            'file'      => ['nullable', 'file', 'image', 'max:1024'],
        ];

        if ($this->isMethod('POST')) {
            $rules['file'] = ['required', 'file', 'image', 'max:1024'];
        }

        return $rules;
    }
}
