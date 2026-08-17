<!-- Modal -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-xl shadow-2xl border-0">
            <!-- Modal Header -->
            <div class="modal-header flex items-center justify-between p-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800 flex items-center gap-2" id="viewEmployeeModalLabel">
                    <i class="fas fa-user-circle text-blue-600 text-xl"></i>
                    <span>Employee Profile</span>
                </h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" 
                        data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-6" id="viewEmployeeContent">
                <!-- Loading State -->
                <div class="text-center py-8" id="loadingState">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <p class="text-gray-500 mt-3">Loading employee details...</p>
                </div>

                <!-- Employee Profile Content -->
                <div id="employeeProfileContent" class="hidden">
                    <!-- Profile Header -->
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex-shrink-0">
                            <div id="profilePicture" class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                <i class="fas fa-user text-gray-400 text-3xl"></i>
                            </div>
                        </div>
                        <div class="text-center md:text-left flex-1">
                            <h3 id="employeeFullName" class="text-xl font-bold text-gray-800">John Doe</h3>
                            <p id="employeeEmail" class="text-gray-500">john.doe@example.com</p>
                            <p id="employeeId" class="text-sm text-gray-400">ID: EMP-000001</p>
                            <div class="flex flex-wrap items-center gap-2 mt-2 justify-center md:justify-start">
                                <span id="employeeStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                                <span id="employeeType" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Permanent
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h6 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                <i class="fas fa-user mr-2 text-blue-600"></i>Personal Information
                            </h6>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-xs text-gray-500">Full Name</label>
                                    <p id="fullNameDisplay" class="text-gray-800 font-medium">John Doe</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Email</label>
                                    <p id="emailDisplay" class="text-gray-800">john.doe@example.com</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Contact</label>
                                    <p id="contactDisplay" class="text-gray-800">+63 912 345 6789</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Address</label>
                                    <p id="addressDisplay" class="text-gray-800">123 Main St, Manila</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h6 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                <i class="fas fa-briefcase mr-2 text-blue-600"></i>Employment Information
                            </h6>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-xs text-gray-500">Department</label>
                                    <p id="departmentDisplay" class="text-gray-800 font-medium">Information Technology</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Position</label>
                                    <p id="positionDisplay" class="text-gray-800">Senior Developer</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Status</label>
                                    <p id="statusDisplay" class="text-gray-800">Active</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Hire Date</label>
                                    <p id="hireDateDisplay" class="text-gray-800">January 15, 2020</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer flex justify-end gap-2 p-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors font-medium flex items-center gap-2" 
                        data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                    <span>Close</span>
                </button>
                <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium flex items-center gap-2 edit-from-view">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profile</span>
                </button>
            </div>
        </div>
    </div>
</div>