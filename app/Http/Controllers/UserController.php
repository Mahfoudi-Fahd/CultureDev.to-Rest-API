<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Services\EmailService;

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
        
        public function forgetPassword(Request $request)
        {
            $user = $request->user();
            if($request->isMethod('post')){
                $request->validate([
                    'email' => 'required|string|email',
                ]);
                $email=$request->email;
                $user = User::where('email', $email)->first();
                if($user){
                    $full_name=$user->name;
                    $activation_token = md5(uniqid()).$email.sha1($email);
                    $emailresetpwd = new EmailService;
                    $subject ="reset your password";
                    $emailresetpwd->resetPassword($subject,$email,$full_name,true,$activation_token);
                    $user = User::where('email', $email)->update(['remembertoken' => $activation_token ]);
                    return response()->json([
                                'status' => 'success',
                                'message' => 'We have send an email vereification to your email please verify that',
                                'name' => $full_name,
                                'token' => $activation_token,

                    ], 200);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'email dosnt exist',
            ], 404);
                }
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid method',
                ], 401);
            }
           
        }

        public function changePassword(Request $request)
        {
            $user= $request->user();
            // $token =$user->rememberToken;
            if($request->isMethod('post')){
                $request->validate([
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|min:8|same:password',
                    'token' => 'required'
                ]);
                $user = User::where('rememberToken', $request->token)->first();
                if($user){
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return response()->json([
                       'statuts' => 'success',
                       'message' => 'your password has been updated successfuly',
                    ],200);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'you do not have permession to access into this page'
                    ],401);
                }
            }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'method not allowd'
                    ],405);
            }
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
    
        public function getUsers(Request $request)
        {
            if($request->isMethod('post')){
                $request->validate([
                    'id' => 'required',
                ]);
                $usersCount = User::where('role_id', $request->id)->count();
                $userId = User::where('role_id', $request->id)->get();
                if($usersCount>0){
                    return response()->json([
                       'status' => 'success',
                        'message' => $userId,
                        'number' => $usersCount
                    ]);
                }else{
                    return response()->json([
                        'status' => 'info',
                        'message' => 'there is no users with this id'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'info',
                    'message' => 'method not allowd'
                ],405);

            }
            
        }
}
