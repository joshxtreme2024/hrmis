{{-- resources/views/management/employees/modals/delete.blade.php --}}
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-xl shadow-2xl border-0">
            <!-- Modal Header -->
            <div class="modal-header flex items-center justify-between p-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-red-600 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Confirm Delete</span>
                </h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" 
                        data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-6">
                <!-- Warning Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
                    </div>
                </div>

                <div class="text-center mb-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Delete Employee</h4>
                    <p class="text-gray-600">
                        Are you sure you want to delete <strong id="deleteEmployeeName" class="text-red-600"></strong>?
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        This action cannot be undone. All associated data will be permanently removed.
                    </p>
                </div>

                <!-- Warning Alert -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Warning</h3>
                            <p class="text-sm text-red-700 mt-1">
                                This will permanently delete this employee record including all related data.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Checkbox -->
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <input type="checkbox" 
                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500" 
                           id="deleteConfirmCheckbox">
                    <label for="deleteConfirmCheckbox" class="text-sm text-gray-700 font-medium">
                        I understand this action is irreversible and confirm deletion
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer flex justify-end gap-2 p-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors font-medium" 
                        data-bs-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <form id="deleteEmployeeForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteEmployeeId" name="employee_id">
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-600" 
                            id="confirmDeleteBtn" 
                            disabled>
                        <i class="fas fa-trash"></i>
                        <span>Delete Employee</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Checkbox Version -->
@push('scripts')
<script>
$(document).ready(function() {
    // Delete confirmation checkbox validation
    $('#deleteConfirmCheckbox').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('#confirmDeleteBtn').prop('disabled', !isChecked);
    });

    // Handle delete form submission
    $('#deleteEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#deleteEmployeeId').val();
        
        const submitBtn = $('#confirmDeleteBtn');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: `/management/employees/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteEmployeeModal').modal('hide');
                $('#deleteConfirmCheckbox').prop('checked', false);
                $('#confirmDeleteBtn').prop('disabled', true);
                submitBtn.html(originalText);
                
                if (typeof toastr !== 'undefined') {
                    toastr.success('Employee deleted successfully');
                }
                
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to delete employee. Please try again.');
                }
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Reset form when modal is hidden
    $('#deleteEmployeeModal').on('hidden.bs.modal', function() {
        $('#deleteConfirmCheckbox').prop('checked', false);
        $('#confirmDeleteBtn').prop('disabled', true);
    });
});
</script>
@endpush