<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
      /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate a user",
     *     description="Authenticate a user with their email and password",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         description="User credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="The user's email address",
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="The user's password",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User authenticated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="The status of the response",
     *                 example="success",
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 description="The authenticated user object",
     *             ),
     *             @OA\Property(
     *                 property="Authorization",
     *                 type="object",
     *                 description="The authorization token",
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="The authorization token value",
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     description="The authorization token type",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="the given data is invalid",
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
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'Authorization' => [
                'token' => $token,
                'type' => 'Bearer',
            ]
        ]);
    }
     /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="cretae your account",
     *     description="Create an account",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         description="create the user",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="The user's name",
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="The user's email address",
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="The user's password",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
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
     *                 example="User createded successfully",
     *             ),
     *         ),
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
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);        
        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
       /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log out user",
     *     description="Log out the currently authenticated user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
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
     *                 example="Successfully logged out",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Anauthoriz action"
     *     ),
     * )
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
  
}