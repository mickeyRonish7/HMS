<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; }
        .id-card {
            width: 320px;
            height: 200px;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            position: relative;
            overflow: hidden;
            border-left: 8px solid #4f46e5;
        }
        .header { margin-bottom: 10px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; }
        .header h1 { font-size: 14px; margin: 0; color: #1e1b4b; text-transform: uppercase; }
        .header p { font-size: 8px; margin: 2px 0; color: #6b7280; font-weight: bold; }
        .photo {
            float: left;
            width: 80px;
            height: 80px;
            background: #f3f4f6;
            border-radius: 8px;
            margin-right: 15px;
        }
        .details { float: left; width: 180px; }
        .details h2 { font-size: 16px; margin: 0; color: #111827; }
        .details .id-num { font-size: 10px; color: #4f46e5; font-weight: bold; }
        .info-grid { margin-top: 10px; }
        .info-item { float: left; width: 50%; margin-bottom: 5px; }
        .info-label { font-size: 7px; color: #9ca3af; text-transform: uppercase; font-weight: bold; }
        .info-value { font-size: 9px; font-weight: bold; color: #374151; }
        .footer {
            clear: both;
            margin-top: 15px;
            border-top: 1px dashed #e5e7eb;
            padding-top: 10px;
        }
        .qr-code { float: right; width: 50px; height: 50px; }
        .contact { float: left; font-size: 8px; color: #6b7280; line-height: 1.4; }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>{{ __('STUDENT ID CARD') }}</p>
        </div>

        <div class="photo">
            @if(($hasGD ?? true) && $user->profile_photo_path)
                <img src="{{ public_path('storage/' . $user->profile_photo_path) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
            @elseif(!($hasGD ?? true))
                <div style="font-size: 8px; color: #9ca3af; text-align: center; padding-top: 20px;">Photo Hidden<br>(Enable GD)</div>
            @endif
        </div>

        <div class="details">
            <h2>{{ $user->name }}</h2>
            <div class="id-num">{{ $user->student_id_number ?? 'HMS-'.str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">{{ __('Dept') }}</div>
                    <div class="info-value">{{ strtoupper($user->department) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('Blood') }}</div>
                    <div class="info-value" style="color: #dc2626;">{{ $user->blood_group }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('Year') }}</div>
                    <div class="info-value">{{ $user->year }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('Expiry') }}</div>
                    <div class="info-value">{{ $user->id_card_expiry_date ? \Carbon\Carbon::parse($user->id_card_expiry_date)->format('M Y') : 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="contact">
                <strong>{{ __('Emergency Contact') }}:</strong><br>
                {{ $user->emergency_contact_name ?? __('Parent') }}: {{ $user->emergency_contact ?? $user->phone }}
                @if(!($hasGD ?? true))
                    <br><span style="color: #dc2626; font-size: 7px; font-weight: bold;">Note: Enable PHP GD extension for full ID visual</span>
                @endif
            </div>
            
            <div class="qr-code">
                @if($hasGD ?? true)
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('admin.students.profile', $user->id)) }}" style="width: 100%;">
                @else
                    <div style="font-size: 6px; color: #9ca3af; text-align: right;">QR Hidden</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
