<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only students can request rooms
        return auth()->check() && auth()->user()->role === 'student';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'bed_id' => 'nullable|exists:beds,id',
            'student_message' => 'nullable|string|max:500',
        ];
    }
}
