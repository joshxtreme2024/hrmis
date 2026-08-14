@extends('layouts.app')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Settings</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Roles & Permissions</span>
    </li>
@endsection
@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8 bg-gradient-to-b from-[#db2777] via-[#ef4444] to-[#f97316] px-4 py-4 rounded-lg">
            <div>
                <h1 class="text-3xl font-bold text-white">Roles & Permissions</h1>
                <p class="text-sm text-gray-100 mt-1">Manage user roles and their access permissions</p>
            </div>
            <button onclick="openCreateRoleModal()" class="group inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New Role
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-[#0e7490] via-[#3b82f6] to-[#4f46e5] rounded-xl p-4 border border-indigo-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Total Roles</p>
                        <p class="text-2xl font-bold text-white">{{ $roles->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-bl from-[#84cc16] via-[#16a34a] to-[#0f766e] rounded-xl p-4 border border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Total Permissions</p>
                        <p class="text-2xl font-bold text-white">{{ $permissions->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-tl from-[#1e293b] via-[#6366f1] to-[#71717a] rounded-xl p-4 border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Assigned Permissions</p>
                        <p class="text-2xl font-bold text-white">{{ $roles->sum(function($role) { return $role->permissions->count(); }) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-[#ea580c] via-[#fb923c] to-[#fed7aa] rounded-xl p-4 border border-amber-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Users with Roles</p>
                        <p class="text-2xl font-bold text-white">{{ $usersWithRoles }}</p>
                    </div>
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles and Permissions Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Roles List -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-lg font-semibold text-gray-900">Roles</h2>
                        <p class="text-xs text-gray-500 mt-1">Click on a role to manage its permissions</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($roles as $role)
                        <div class="role-item p-4 hover:bg-gray-50 transition-all duration-200 cursor-pointer {{ $loop->first ? 'bg-indigo-50 border-l-4 border-indigo-500' : '' }}" 
                             onclick="selectRole({{ $role->id }}, '{{ $role->name }}')"
                             data-role-id="{{ $role->id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-semibold text-gray-900">{{ $role->name }}</h3>
                                        @if($role->name === 'Admin' || $role->name === 'admin')
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-purple-100 to-purple-50 text-purple-700">System</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $role->permissions->count() }} permission(s)</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($role->name !== 'Admin' && $role->name !== 'admin')
                                    <button onclick="event.stopPropagation(); editRole({{ $role->id }}, '{{ $role->name }}')" 
                                            class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="event.stopPropagation(); deleteRole({{ $role->id }}, '{{ $role->name }}')" 
                                            class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">No roles created yet</p>
                            <button onclick="openCreateRoleModal()" class="mt-2 text-sm text-indigo-600 hover:text-indigo-500">Create your first role</button>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Permissions Panel -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900" id="selected-role-title">Permissions</h2>
                                <p class="text-xs text-gray-500 mt-1" id="selected-role-description">Select a role from the left to manage its permissions</p>
                            </div>
                            <div id="save-permissions-btn" class="hidden">
                                <button onclick="savePermissions()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="permissions-container" class="p-6">
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Click on a role to view and manage permissions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Role Modal -->
<div id="roleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Create New Role</h3>
            <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="roleForm" action="{{ route('roles.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="role_id" id="roleId">
            <div class="p-6 space-y-4">
                <div>
                    <label for="roleName" class="block text-sm font-semibold text-gray-700 mb-2">Role Name</label>
                    <input type="text" name="name" id="roleName" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        placeholder="e.g., Editor, Viewer, Manager">
                    <p class="mt-1 text-xs text-gray-500">Use a unique, descriptive name for the role</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="closeRoleModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    Save Role
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let currentRoleId = null;
let currentPermissions = [];

// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;

// Select role and load permissions
function selectRole(roleId, roleName) {
    currentRoleId = roleId;
    
    // Update UI
    document.querySelectorAll('.role-item').forEach(item => {
        item.classList.remove('bg-indigo-50', 'border-l-4', 'border-indigo-500');
    });
    const selectedItem = document.querySelector(`[data-role-id="${roleId}"]`);
    if (selectedItem) {
        selectedItem.classList.add('bg-indigo-50', 'border-l-4', 'border-indigo-500');
    }
    
    document.getElementById('selected-role-title').innerHTML = `Permissions for <span class="text-indigo-600">${escapeHtml(roleName)}</span>`;
    document.getElementById('selected-role-description').innerHTML = 'Select the permissions you want to assign to this role';
    document.getElementById('save-permissions-btn').classList.remove('hidden');
    
    // Load permissions via AJAX
    const url = `/roles/${roleId}/permissions`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        currentPermissions = data.permissions.map(p => p.id);
        renderPermissions(data.allPermissions, data.permissions);
    })
    .catch(error => {
        console.error('Error loading permissions:', error);
        document.getElementById('permissions-container').innerHTML = `
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-gray-500">Failed to load permissions. Please try again.</p>
                <button onclick="selectRole(${roleId}, '${escapeHtml(roleName)}')" class="mt-4 text-sm text-indigo-600 hover:text-indigo-500">Retry</button>
            </div>
        `;
    });
}

// Render permissions grid
function renderPermissions(allPermissions, assignedPermissions) {
    const container = document.getElementById('permissions-container');
    const assignedIds = assignedPermissions.map(p => p.id);
    
    // Group permissions by module (based on permission name)
    const grouped = {};
    allPermissions.forEach(perm => {
        let module = perm.name.split('_')[0] || 'general';
        module = module.charAt(0).toUpperCase() + module.slice(1);
        if (!grouped[module]) grouped[module] = [];
        grouped[module].push(perm);
    });
    
    if (Object.keys(grouped).length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <p class="text-gray-500">No permissions available</p>
            </div>
        `;
        return;
    }
    
    let html = '<div class="space-y-6">';
    for (const [module, perms] of Object.entries(grouped)) {
        html += `
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">${escapeHtml(module)} Permissions</h3>
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
        `;
        
        perms.forEach(perm => {
            const isChecked = assignedIds.includes(perm.id);
            html += `
                <label class="flex items-start gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer group">
                    <input type="checkbox" 
                           value="${perm.id}" 
                           class="permission-checkbox mt-0.5 w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer"
                           ${isChecked ? 'checked' : ''}
                           onchange="togglePermission(${perm.id}, this.checked)">
                    <div class="flex-1">
                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">${escapeHtml(perm.name)}</span>
                    </div>
                </label>
            `;
        });
        
        html += `</div></div>`;
    }
    html += '</div>';
    container.innerHTML = html;
}

// Toggle permission
function togglePermission(permissionId, isChecked) {
    if (isChecked) {
        if (!currentPermissions.includes(permissionId)) {
            currentPermissions.push(permissionId);
        }
    } else {
        currentPermissions = currentPermissions.filter(id => id !== permissionId);
    }
}

// Save permissions
function savePermissions() {
    if (!currentRoleId) {
        showNotification('No role selected', 'error');
        return;
    }
    
    const url = `/roles/${currentRoleId}/permissions`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ permissions: currentPermissions })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Permissions updated successfully!', 'success');
            // Update the role item permission count
            const roleItem = document.querySelector(`[data-role-id="${currentRoleId}"]`);
            if (roleItem) {
                const permCountSpan = roleItem.querySelector('p');
                if (permCountSpan) {
                    permCountSpan.textContent = `${currentPermissions.length} permission(s)`;
                }
            }
        } else {
            showNotification(data.message || 'Failed to update permissions', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while saving permissions', 'error');
    });
}

// Create role modal
function openCreateRoleModal() {
    document.getElementById('modalTitle').textContent = 'Create New Role';
    document.getElementById('roleForm').action = "{{ route('roles.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('roleId').value = '';
    document.getElementById('roleName').value = '';
    document.getElementById('roleModal').classList.remove('hidden');
}

// Edit role
function editRole(roleId, roleName) {
    document.getElementById('modalTitle').textContent = 'Edit Role';
    document.getElementById('roleForm').action = `/roles/${roleId}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('roleId').value = roleId;
    document.getElementById('roleName').value = roleName;
    document.getElementById('roleModal').classList.remove('hidden');
}

// Delete role
function deleteRole(roleId, roleName) {
    if (confirm(`Are you sure you want to delete the role "${roleName}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/roles/${roleId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modal
function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.style.animation = 'fadeInUp 0.3s ease-out';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Auto-select first role on page load
document.addEventListener('DOMContentLoaded', function() {
    const firstRole = document.querySelector('.role-item');
    if (firstRole && firstRole.getAttribute('data-role-id')) {
        const roleId = firstRole.getAttribute('data-role-id');
        const roleName = firstRole.querySelector('h3').textContent;
        selectRole(roleId, roleName);
    }
});
</script>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@endsection