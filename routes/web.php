<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;

Route::get('/debug/login-admin', function () {
    $user = \App\Models\User::where('role', 'admin')->first();
    if ($user) {
        auth()->login($user);
        return redirect()->route('admin.dashboard');
    }
    return "No admin found";
});

Route::get('/debug/login-student', function () {
    $user = \App\Models\User::where('role', 'student')->first();
    if ($user) {
        auth()->login($user);
        return redirect()->route('student.dashboard');
    }
    return "No student found";
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'visitor') {
             return redirect()->route('visitor.dashboard');
        }
        return redirect()->route('student.dashboard');
    })->name('dashboard');

    // Visitor Routes
    Route::middleware(['auth', 'verified', 'role:visitor'])->group(function () {
        Route::get('/visitor/dashboard', [\App\Http\Controllers\VisitorDashboardController::class, 'dashboard'])->name('visitor.dashboard');
        Route::post('/visitor/request', [\App\Http\Controllers\VisitorDashboardController::class, 'requestVisit'])->name('visitor.request');
    });

    // Chatbot Route
    Route::post('/chatbot/ask', [\App\Http\Controllers\ChatbotController::class, 'ask'])->name('chatbot.ask');

    // Theme and Locale Routes
    Route::post('/theme/toggle', [\App\Http\Controllers\ThemeController::class, 'toggleTheme'])->name('theme.toggle');
    Route::post('/theme/font-size', [\App\Http\Controllers\ThemeController::class, 'changeFontSize'])->name('theme.font-size');
    Route::get('/locale/{locale}', [\App\Http\Controllers\ThemeController::class, 'changeLocale'])->name('locale.change');

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::get('/recent', [\App\Http\Controllers\NotificationController::class, 'recent'])->name('recent');
    });

    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('visitors', \App\Http\Controllers\VisitorController::class)->only(['index', 'store', 'update']);
        Route::resource('fees', \App\Http\Controllers\FeeController::class)->only(['index', 'show']);
        Route::resource('rooms', \App\Http\Controllers\RoomController::class);
        Route::resource('notices', \App\Http\Controllers\NoticeController::class);
        Route::resource('complaints', \App\Http\Controllers\ComplaintController::class)->only(['index', 'update']);
        
        Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/export', [App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');

        // Feedback Admin
        Route::get('/feedback', [\App\Http\Controllers\FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('/feedback/analytics', [\App\Http\Controllers\FeedbackController::class, 'analytics'])->name('feedback.analytics');
        Route::post('/feedback/{id}/status', [\App\Http\Controllers\FeedbackController::class, 'updateStatus'])->name('feedback.status');

        // Room Requests Admin
        Route::get('/room-requests', [\App\Http\Controllers\RoomController::class, 'adminRequests'])->name('room-requests.index');
        Route::post('/room-requests/{id}/approve', [\App\Http\Controllers\RoomController::class, 'approveRequest'])->name('room-requests.approve');
        Route::post('/room-requests/{id}/reject', [\App\Http\Controllers\RoomController::class, 'rejectRequest'])->name('room-requests.reject');

        // Approvals
        Route::get('/users/pending', [AdminController::class, 'pendingUsers'])->name('users.pending');
        Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::delete('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');

        // Admin Profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

        // Student Management
        Route::get('/students', [AdminController::class, 'students'])->name('students.index');
        Route::get('/students/{id}/profile', [AdminController::class, 'showStudentProfile'])->name('students.profile');
        Route::get('/students/{id}/assign-room', [AdminController::class, 'assignRoomForm'])->name('students.assign-room');
        Route::post('/students/{id}/assign-room', [AdminController::class, 'assignRoom'])->name('students.assign-room.post');
        Route::post('/students/{id}/unassign', [AdminController::class, 'unassignRoom'])->name('students.unassign');

        // ID Card Admin
        Route::get('/students/{id}/id-card', [\App\Http\Controllers\IDCardController::class, 'show'])->name('students.id-card.show');
        Route::get('/students/{id}/id-card/edit', [\App\Http\Controllers\IDCardController::class, 'edit'])->name('students.id-card.edit');
        Route::put('/students/{id}/id-card', [\App\Http\Controllers\IDCardController::class, 'update'])->name('students.id-card.update');

        // Visitor Request Management (Specific Visits)
        Route::get('/visitor-requests', [AdminController::class, 'visitorRequests'])->name('visitors.requests');
        Route::get('/visitor-requests/{id}/edit', [AdminController::class, 'editVisitRequest'])->name('visitors.edit_request');
        Route::put('/visitor-requests/{id}', [AdminController::class, 'updateVisitRequest'])->name('visitors.update_request');
    });

    Route::prefix('student')->name('student.')->middleware(['role:student'])->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        Route::post('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');

        // Room Browsing and Requests
        Route::get('/rooms/browse', [\App\Http\Controllers\RoomController::class, 'browse'])->name('rooms.browse');
        Route::get('/rooms/{id}', [\App\Http\Controllers\RoomController::class, 'showRoom'])->name('rooms.show');
        Route::post('/rooms/request', [\App\Http\Controllers\RoomController::class, 'requestRoom'])->name('rooms.request');
        Route::get('/room-requests', [\App\Http\Controllers\RoomController::class, 'myRequests'])->name('room-requests');

        // Detailed Views
        Route::get('/room', [StudentController::class, 'room'])->name('room');
        Route::get('/fees', [StudentController::class, 'fees'])->name('fees');
        Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'studentIndex'])->name('attendance');

        // ID Card
        Route::get('/id-card', [\App\Http\Controllers\IDCardController::class, 'show'])->name('id-card');
        Route::get('/id-card/download', [\App\Http\Controllers\IDCardController::class, 'download'])->name('id-card.download');

        // Complaints
        Route::resource('complaints', \App\Http\Controllers\ComplaintController::class)->only(['create', 'store', 'index']);

        // Visitor Approval (Student)
        Route::get('/visitors/pending', [StudentController::class, 'pendingVisitors'])->name('visitors.pending');
        Route::post('/visitors/{id}/approve', [StudentController::class, 'approveVisitor'])->name('visitors.approve');
        Route::post('/visitors/{id}/reject', [StudentController::class, 'rejectVisitor'])->name('visitors.reject');

        // Specific Visit Requests (Purpose & Date)
        Route::post('/visits/{id}/approve', [StudentController::class, 'approveVisitRequest'])->name('visits.approve');
        Route::post('/visits/{id}/reject', [StudentController::class, 'rejectVisitRequest'])->name('visits.reject');

        // Feedback (Now strictly for students)
        Route::get('/feedback/create', [\App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create');
        Route::post('/feedback', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
    });

    // Password Update for Authenticated Users (Secure Unique Route)
    Route::post('/user/security/update-password', [\App\Http\Controllers\AuthController::class, 'updatePassword'])->name('user.password.secure_update');
});

require __DIR__.'/auth.php'; // Standard laravel auth include, though file might not exist yet
