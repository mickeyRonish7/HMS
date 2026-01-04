<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IDCardController extends Controller
{
    /**
     * Show student ID card
     */
    public function show($userId = null)
    {
        // If no userId provided, show current user's ID card
        if (!$userId) {
            $userId = auth()->id();
        }

        $user = User::findOrFail($userId);

        // Check authorization
        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('student.id-card', compact('user'));
    }

    /**
     * Download ID card as PDF
     */
    public function download($userId = null)
    {
        // If no userId provided, download current user's ID card
        if (!$userId) {
            $userId = auth()->id();
        }

        $user = User::findOrFail($userId);

        // Check authorization
        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Check if GD extension is installed
        $hasGD = extension_loaded('gd');

        // The View 'pdf.id-card' will use this flag to decide whether to show images
        $pdf = Pdf::loadView('pdf.id-card', [
            'user' => $user,
            'hasGD' => $hasGD,
        ]);
        
        // Enabling remote images for the QR code API (only if GD is available)
        if ($hasGD) {
            $pdf->setOption('isRemoteEnabled', true);
        }
        
        return $pdf->download($user->name . '_ID_Card.pdf');
    }

    /**
     * Admin: Show edit ID card form
     */
    public function edit($userId)
    {
        $this->authorize('admin');
        
        $user = User::findOrFail($userId);

        return view('admin.students.edit-id-card', compact('user'));
    }

    /**
     * Admin: Update ID card details
     */
    public function update(Request $request, $userId)
    {
        $this->authorize('admin');

        $request->validate([
            'student_id_number' => 'nullable|string|max:50|unique:users,student_id_number,' . $userId,
            'id_card_issued_date' => 'nullable|date',
            'id_card_expiry_date' => 'nullable|date|after:id_card_issued_date',
            'blood_group' => 'nullable|string|max:10',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:100',
        ]);

        $user = User::findOrFail($userId);

        $user->update($request->only([
            'student_id_number',
            'id_card_issued_date',
            'id_card_expiry_date',
            'blood_group',
            'emergency_contact',
            'emergency_contact_name',
        ]));

        return redirect()
            ->route('admin.students.profile', $userId)
            ->with('success', 'ID card details updated successfully');
    }

    /**
     * Helper: Check if user is admin
     */
    protected function authorize($role)
    {
        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized');
        }
    }
}
