<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::with('student')->latest()->paginate(20);
        return view('admin.fees.index', compact('fees'));
    }

    public function show(Fee $fee)
    {
        return view('admin.fees.receipt', compact('fee'));
    }
}
