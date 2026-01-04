<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only visitors can request visits
        return auth()->check() && auth()->user()->role === 'visitor';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:users,id',
            'purpose' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
        ];
    }
}
