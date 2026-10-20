<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /** Create a permission (AJAX). Optionally assign it to a role straight away. */
    public function store(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403, 'Only administrators can add permissions.');

        // Normalise before validating: "Leaves Approve" -> "leaves_approve"
        $request->merge(['name' => str($request->input('name'))->trim()->lower()->replaceMatches('/[\s\-]+/', '_')->toString()]);

        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                'regex:/^[a-z][a-z0-9]*(_[a-z0-9]+)*$/',        // snake_case, e.g. leaves_approve
                Rule::unique((new Permission)->getTable(), 'name'),
            ],
            'assign_to_role_id' => ['nullable', 'integer', Rule::exists((new Role)->getTable(), 'id')],
        ], [
            'name.regex'  => 'Use lowercase letters, numbers and underscores only, e.g. leaves_approve.',
            'name.unique' => 'That permission already exists.',
        ]);

        // If your permissions table has other required columns (e.g. label), add them here.
        $permission = Permission::create([
                                            'name' => $data['name'],
                                            'guard_name' => 'web',
                                            ]);

        $assigned = false;
        if (! empty($data['assign_to_role_id'])) {
            Role::findOrFail($data['assign_to_role_id'])->permissions()->syncWithoutDetaching([$permission->id]);
            $assigned = true;
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Permission "' . $permission->name . '" added.',
            'permission' => $permission,
            'assigned'   => $assigned,
        ], 201);
    }
}