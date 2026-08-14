<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Maintenance Mode | {{ $companyName ?? 'HRMIS' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
            padding: 1.5rem;
        }

        .dark body {
            background: linear-gradient(135deg, #111827 0%, #1a1a2e 100%);
        }

        .maintenance-container {
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .dark .icon-wrapper {
            background: linear-gradient(135deg, #92400e, #b45309);
        }

        .icon-wrapper i {
            font-size: 3rem;
            color: #d97706;
        }

        .dark .icon-wrapper i {
            color: #fbbf24;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .dark h1 {
            color: #f9fafb;
        }

        .message {
            font-size: 1rem;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .dark .message {
            color: #9ca3af;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dark .info-item {
            background: rgba(31, 41, 55, 0.7);
            border-color: rgba(255, 255, 255, 0.05);
        }

        .info-item .label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 0.25rem;
        }

        .info-item .value {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
        }

        .dark .info-item .value {
            color: #f9fafb;
        }

        .loader {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .loader .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #6366f1;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .loader .dot:nth-child(1) { animation-delay: -0.32s; }
        .loader .dot:nth-child(2) { animation-delay: -0.16s; }
        .loader .dot:nth-child(3) { animation-delay: 0; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        .refresh-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -8px rgba(99, 102, 241, 0.5);
        }

        .refresh-btn i {
            font-size: 1rem;
        }

        .footer {
            margin-top: 1.5rem;
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .dark .footer {
            color: #6b7280;
        }

        .footer a {
            color: #6366f1;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Auto-refresh meta tag */
        @if(isset($retryAfter) && $retryAfter > 0)
        <meta http-equiv="refresh" content="{{ $retryAfter }}">
        @endif
    </style>
</head>
<body>
    <div class="maintenance-container">
        <!-- Icon -->
        <div class="icon-wrapper">
            <i class="bi bi-tools"></i>
        </div>

        <!-- Title -->
        <h1>Maintenance Mode</h1>

        <!-- Message -->
        <p class="message">{{ $message }}</p>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Status</span>
                <span class="value">Under Maintenance</span>
            </div>
            @if(isset($estimatedReturn))
            <div class="info-item">
                <span class="label">Expected Return</span>
                <span class="value">{{ $estimatedReturn }}</span>
            </div>
            @endif
            @if(!empty($contactEmail))
            <div class="info-item">
                <span class="label">Email</span>
                <span class="value" style="font-size:0.75rem;">{{ $contactEmail }}</span>
            </div>
            @endif
            @if(!empty($contactPhone))
            <div class="info-item">
                <span class="label">Phone</span>
                <span class="value">{{ $contactPhone }}</span>
            </div>
            @endif
        </div>

        <!-- Loading Animation -->
        <div class="loader">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>

        <!-- Refresh Button -->
        <button onclick="window.location.reload()" class="refresh-btn">
            <i class="bi bi-arrow-clockwise"></i>
            Check Again
        </button>

        <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
            <p>
                <i class="bi bi-shield-lock mr-1"></i>
                Are you an administrator? 
                <a href="{{ route('admin.login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                    Click here to login
                </a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                &copy; {{ date('Y') }} {{ $companyName ?? 'HRMIS' }}
                @if(!empty($contactEmail))
                · <a href="mailto:{{ $contactEmail }}">Contact Support</a>
                @endif
            </p>
            @if(isset($retryAfter) && $retryAfter > 0)
            <p class="mt-1 text-xs opacity-60">
                Auto-refresh in {{ floor($retryAfter / 60) }} minutes
            </p>
            @endif
        </div>
    </div>

    <script>
        // Auto-refresh with countdown
        @if(isset($retryAfter) && $retryAfter > 0)
        let seconds = {{ $retryAfter }};
        const refreshBtn = document.querySelector('.refresh-btn');
        
        setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                window.location.reload();
            }
        }, 1000);
        @endif

        // Check for maintenance status every 30 seconds via AJAX
        setInterval(() => {
            fetch('/maintenance-status')
                .then(response => response.json())
                .then(data => {
                    if (!data.maintenance) {
                        window.location.reload();
                    }
                })
                .catch(() => {});
        }, 30000);
    </script>
</body>
</html>