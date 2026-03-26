<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterRequest extends FormRequest
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
            'title'         => ['required', 'array'],
            'title.*'       => ['required', 'string', 'max:255'],
            'description'   => ['required', 'array'],
            'description.*' => ['required', 'string', 'max:255'],
            'number'        => ['required', 'integer', 'min:0'],
            'symbol'        => ['nullable', 'string', 'max:255'],
        ];
    }
}
