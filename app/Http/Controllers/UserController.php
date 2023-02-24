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
     /**
                 * @OA\Put(
                 *     path="/api/updateProfile",
                 *     summary="Update user information",
                 *     description="Update the authenticated user's information",
                 *     tags={"Users"},
                 *     security={{"bearerAuth": {}}},
                 *     @OA\RequestBody(
                 *         description="The updated user information",
                 *         required=true,
                 *         @OA\JsonContent(
                 *             @OA\Property(
                 *                 property="name",
                 *                 type="string",
                 *                 description="The user's new name",
                 *             ),
                 *             @OA\Property(
                 *                 property="email",
                 *                 type="string",
                 *                 description="The user's new email address",
                 *             ),
                 *             @OA\Property(
                 *                 property="password",
                 *                 type="string",
                 *                 description="The user's new password",
                 *             ),
                 *         ),
                 *     ),
                 *     @OA\Response(
                 *         response=200,
                 *         description="User updated successfully",
                 *         @OA\JsonContent(
                 *             @OA\Property(
                 *                 property="status",
                 *                 type="string",
                 *                 description="The status of the response",
                 *                 example="success",
                 *             ),
                 *             @OA\Property(
                 *                 property="message",
                 *                 type="string",
                 *                 description="A message describing the response status",
                 *                 example="User updated successfully",
                 *             ),
                 *             @OA\Property(
                 *                 property="user",
                 *                 type="object",
                 *                 description="The updated user object",
                 *                 @OA\Property(
                 *                     property="id",
                 *                     type="integer",
                 *                     description="The user's ID",
                 *                     example=1,
                 *                 ),
                 *                 @OA\Property(
                 *                     property="name",
                 *                     type="string",
                 *                     description="The user's name",
                 *                     example="John Doe",
                 *                 ),
                 *                 @OA\Property(
                 *                     property="email",
                 *                     type="string",
                 *                     description="The user's email address",
                 *                     example="example@example.com",
                 *                 ),
                 *                 @OA\Property(
                 *                     property="role_id",
                 *                     type="integer",
                 *                     description="The user's role ID",
                 *                     example=3,
                 *                 ),
                 *             ),
                 *         ),
                 *     ),
                 *     @OA\Response(
                 *         response=401,
                 *         description="Unauthorized action",
                 *     ),
                 *     @OA\Response(
                 *         response=422,
                 *         description="Validation error",
                 *         @OA\JsonContent(
                 *             @OA\Property(
                 *                 property="message",
                 *                 type="string",
                 *                 description="A message describing the validation error",
                 *                 example="The given data was invalid.",
                 *             ),
                 *             @OA\Property(
                 *                 property="errors",
                 *                 type="object",
                 *                 description="An object containing validation error messages",
                 *             ),
                 *         ),
                 *     ),
                 * )
             */

       
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
         /**
             * @OA\Post(
             *    path="/api/forgetPassword",
             *    summary="Request password reset",
             *    description="Request a password reset by sending an email to the user's email address",
             *    tags={"Users"},
             *    security={{"bearerAuth": {}}},
             *    @OA\RequestBody(
             *        description="the email address",
             *        required=true,
             *        @OA\JsonContent(
             *            @OA\Property(
             *               property="email",
             *               type="string",
             *               description="the user's email address",
             *               example="example@example.com",
             *            ),
             *        ),
             *    ),
             *    @OA\Response(
             *         response=200,
             *         description="email has been send successfuly",
             *         @OA\JsonContent(
             *            @OA\Property(
             *                property="status",
             *                type="string",
             *                description="statut of response",
             *                example="success",
             *            ),
             *            @OA\Property(
             *               property="message",
             *               type="string",
             *               description="a message that describe your response",
             *               example="an verification token has been send to your email ",
             *            ),
             *         ),
             *    ),
             *    @OA\Response(
             *         response=401,
             *         description="action not authorized",
             *         @OA\JsonContent(
             *           @OA\Property(
             *           property="status",
             *           type="string",
             *           description="status of response",
             *           example="anauthoriz",
             *           ),
             *           @OA\Property(
             *           property="message",
             *           type="string",
             *           description="message describe your response",
             *           example="action not authorize",
             *           ),
             *         ),
             *    ),
             *    @OA\Response(
             *         response=404,
             *         description="email dosn't exist",
             *    ),
             * 
             * )
             *
         */
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
        /**
            * @OA\POST(
            *     path="/api/changePassword",
            *     summary="change password ",
            *     description="change the password of the user",
            *     tags={"Users"},
            *     security={{"bearerAuth": {}}},
            *     @OA\Response(
            *         response=200,
            *         description="password changed successfuly",
            *         @OA\JsonContent(
            *             @OA\Property(
            *                 property="status",
            *                 type="string",
            *                 description="The status of the response",
            *                 example="success",
            *             ),
            *             @OA\Property(
            *                 property="message",
            *                 type="string",
            *                 description="A message describing the response status",
            *                 example="Password has been changed successfully",
            *             ),
            *         ),
            *     ),
            *     @OA\Response(
            *         response=405,
            *         description="Method not allowd",
            *     ),
            *     @OA\Response(
            *         response=422,
            *         description="validation error",
            *         @OA\JsonContent(
            *             @OA\Property(
            *               property="status",  
            *               type="string",
            *               description="the status response",
            *               example="error"
            *             ),
            *             @OA\Property(
            *               property="message",  
            *               type="string",
            *               description="An object containing validation error messages",
            *               example="validation error",
            *             ),
            *         ),
            *
            *        ),
            *     ),
            * )
        */
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
        /**
            * @OA\DELETE(
            *     path="/api/deleteProfile",
            *     summary="Delete the conected user",
            *     description="Delete the authenticated user",
            *     tags={"Users"},
            *     security={{"bearerAuth": {}}},
            *     @OA\Response(
            *         response=200,
            *         description="User deleted successfully",
            *         @OA\JsonContent(
            *             @OA\Property(
            *                 property="status",
            *                 type="string",
            *                 description="The status of the response",
            *                 example="success",
            *             ),
            *             @OA\Property(
            *                 property="message",
            *                 type="string",
            *                 description="A message describing the response status",
            *                 example="User deleted successfully",
            *             ),
            *         ),
            *     ),
            *     @OA\Response(
            *         response=401,
            *         description="Unauthorized action",
            *     ),
            * )
        */
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
