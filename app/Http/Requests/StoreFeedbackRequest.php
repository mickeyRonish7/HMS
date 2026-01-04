<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only students can submit feedback
        return auth()->check() && auth()->user()->role === 'student';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'room_rating' => 'nullable|integer|min:1|max:5',
            'mess_rating' => 'nullable|integer|min:1|max:5',
            'security_rating' => 'nullable|integer|min:1|max:5',
            'staff_rating' => 'nullable|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean',
        ];
    }
}
