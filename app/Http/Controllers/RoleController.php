<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function createRole(Request $request)
    {
        if(auth()->user()->role_id == 1){
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
        }else return response()->json(['message'=>'Method not allowed !']);
    }
}
