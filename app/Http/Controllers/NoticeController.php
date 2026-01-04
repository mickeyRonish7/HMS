<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::latest()->get();
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,students,wardens',
        ]);

        $data = $request->all();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('notices', 'public');
            $data['attachment'] = $path;
        }

        $notice = Notice::create($data);

        // Notify relevant audience
        app(\App\Services\NotificationService::class)->notifyNewNotice($notice->audience, $notice->title);

        return redirect()->route('admin.notices.index')->with('success', 'Notice posted successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted successfully.');
    }
}
