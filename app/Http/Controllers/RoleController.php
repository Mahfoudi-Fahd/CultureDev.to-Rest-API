<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:10|min:2',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
            'roles' => $role,
        ]);
    }
}
