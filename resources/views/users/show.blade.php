@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('users.index') }}" class="hover:text-indigo-600 transition-colors duration-200">Users</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-800 font-medium">{{ $user->getDisplayNameAttribute() }}</span>
        </nav>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8 border px-6 py-6 bg-gradient-to-r from-[#2dd4bf] to-[#1f2937] rounded-lg">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->getDisplayInitialsAttribute() }}" class="w-16 h-16 rounded-2xl object-cover">
                        @else
                            <span class="text-2xl font-bold text-indigo-600">{{ $user->getDisplayInitialsAttribute() ?? 'N/A' }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $user->getDisplayNameAttribute() }}</h1>
                    <p class="text-sm text-gray-100 mt-1">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $user->email }}
                        </span>
                        <span class="mx-2">•</span>
                        <span class="inline-flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full {{ $user->status == 'enabled' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                            {{ ucfirst($user->status) }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-200 border border-white/20">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Role</p>
                        <p class="text-lg font-bold text-gray-900 mt-1 capitalize">{{ $user->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg {{ $user->role == 'admin' ? 'bg-yellow-100' : 'bg-blue-100' }} flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $user->role == 'admin' ? 'text-yellow-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</p>
                        <p class="text-lg font-bold text-gray-900 mt-1 capitalize">{{ $user->status }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg {{ $user->status == 'enabled' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $user->status == 'enabled' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">{{ $user->updated_at->format('M d, Y') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details Card -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Personal Information -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Personal Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Full Name</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->getDisplayNameAttribute() ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email Address</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Role</label>
                            <p class="text-sm font-semibold text-gray-900 capitalize">{{ $user->role }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->status == 'enabled' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->status == 'enabled' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Created At</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('F d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Last Updated</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('F d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-400">{{ $user->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-6">
                <!-- Quick Actions Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Quick Actions
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Change Role - Modal Trigger -->
                        <button type="button" 
                                id="changeRoleBtn"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            Change Role
                        </button>
                        
                        @if($user->status == 'enabled')
                        <form action="{{ route('users.disable', $user) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200" onclick="return confirm('Are you sure you want to disable this user?');">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                Disable User
                            </button>
                        </form>
                        @else
                        <form action="{{ route('users.enable', $user) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200" onclick="return confirm('Are you sure you want to enable this user?');">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Enable User
                            </button>
                        </form>
                        @endif

                        @if(auth()->id() !== $user->id)
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete User
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Timeline (Optional) -->
        <div class="mt-6 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Activity Timeline
                </h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">No recent activity</p>
                        <p class="text-xs text-gray-400 mt-1">User activity will appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="changeRoleModal" class="modal-overlay">
    <div class="modal-container">
        <!-- Modal Content -->
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Change User Role
                </h5>
                <button type="button" class="modal-close" id="closeModalBtn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- User Info Summary -->
                <div class="user-info-summary">
                    <div class="user-avatar">
                        <span>{{ $user->getDisplayInitialsAttribute() ?? 'U' }}</span>
                    </div>
                    <div class="user-details">
                        <p class="user-name">{{ $user->getDisplayNameAttribute() }}</p>
                        <p class="user-email">{{ $user->email }}</p>
                    </div>
                    <div class="current-role">
                        <span class="role-badge">Current: {{ $user->role }}</span>
                    </div>
                </div>

                <!-- Change Role Form -->
                <form action="{{ route('users.changeRole', $user) }}" method="POST" id="changeRoleForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="role" class="form-label">Select New Role</label>
                        <select name="role" id="role" class="form-select">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->role == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        <p class="form-hint">
                            <svg class="w-3.5 h-3.5 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Changing a user's role will update their permissions immediately.
                        </p>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="confirm" id="confirm" required>
                        <label for="confirm">I confirm that I want to change this user's role.</label>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="cancelModalBtn">Cancel</button>
                <button type="submit" form="changeRoleForm" class="btn-submit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Update Role
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    /* Modal Overlay */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
    }

    /* Modal Container */
    .modal-container {
        width: 100%;
        max-width: 500px;
        margin: 1rem;
        animation: slideUp 0.3s ease;
    }

    /* Modal Content */
    .modal-content {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    /* Modal Header */
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 1.5rem 0 1.5rem;
        border-bottom: none;
    }

    .modal-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        padding: 0.25rem;
        cursor: pointer;
        color: #9ca3af;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }

    .modal-close:hover {
        color: #111827;
        background: #f3f4f6;
    }

    /* Modal Body */
    .modal-body {
        padding: 1.5rem;
    }

    /* User Info Summary */
    .user-info-summary {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .user-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        background: #e0e7ff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .user-avatar span {
        font-size: 0.875rem;
        font-weight: 700;
        color: #4f46e5;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .user-email {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
    }

    .current-role {
        flex-shrink: 0;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        background: #e5e7eb;
        color: #374151;
        white-space: nowrap;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.375rem;
    }

    .form-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: #111827;
        background: white;
        transition: all 0.2s;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-hint {
        margin-top: 0.375rem;
        font-size: 0.75rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Checkbox */
    .checkbox-group {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: 1rem;
        height: 1rem;
        margin-top: 0.125rem;
        border: 1px solid #d1d5db;
        border-radius: 0.25rem;
        color: #4f46e5;
        cursor: pointer;
        flex-shrink: 0;
    }

    .checkbox-group input[type="checkbox"]:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .checkbox-group label {
        font-size: 0.875rem;
        color: #4b5563;
        cursor: pointer;
    }

    /* Modal Footer */
    .modal-footer {
        display: flex;
        gap: 0.75rem;
        padding: 0 1.5rem 1.5rem 1.5rem;
        border-top: none;
    }

    .btn-cancel {
        flex: 1;
        padding: 0.625rem 1rem;
        background: #f3f4f6;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    .btn-submit {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background: #4f46e5;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #4338ca;
    }

    .btn-submit svg {
        width: 1rem;
        height: 1rem;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const modal = document.getElementById('changeRoleModal');
        const openBtn = document.getElementById('changeRoleBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelModalBtn');
        const modalOverlay = document.querySelector('.modal-overlay');

        // Function to open modal
        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }

        // Function to close modal
        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Open modal on button click
        if (openBtn) {
            openBtn.addEventListener('click', openModal);
        }

        // Close modal on X button
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        // Close modal on Cancel button
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }

        // Close modal on overlay click (click outside)
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });

        // Handle form submission (optional: show loading state)
        const form = document.getElementById('changeRoleForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('.btn-submit');
                submitBtn.innerHTML = `
                    <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Updating...
                `;
                submitBtn.disabled = true;
                // Form will submit normally
            });
        }
    });
</script>
@endsection