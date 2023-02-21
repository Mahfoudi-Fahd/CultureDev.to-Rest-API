<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{  
       
        public function update(Request $request)
        {
            $user = $request->user();
            if ($request->has('name'))
            {
                $user->name = $request->name;
            }
            if ($request->has('email'))
            {
                $user->email = $request->email;
            }
            if ($request->has('password'))
            {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'user' => $user,
            ]);
        }    
        
        public function resetPassword(Request $request)
        {
            $user = $request->user();

            $request->validate([
                'email' => 'required|string|email',
                'old_password' => 'required|string',
                'password' => 'required|string|min:8',
            ]);

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid old password',
                ], 401);
            }

            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully',
            ]);

        }

        public function destroy(Request $request)
        {
            $user=$request->user();
            if ($user) {
                $user->delete();
            
                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile deleted successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            }
        }
    
    
}
