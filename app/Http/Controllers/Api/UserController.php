<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */

     public function login(Request $request)
    {
        // return response()->json([
        //     'code' => true,
        //     'msg' => 'User Loggedin Successfully',
        //     'data' => 'jkdgfjsfdkndk'
        // ], 200);

        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'avatar' => 'required',
                'type' => 'required',
                'open_id' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                // 'password' => 'required'
            ]);
            
            if($validateUser->fails()){
                return response()->json([
                    'code' => 401,
                    'msg' => 'validation error',
                    'data' => $validateUser->errors()
                ], 401);
            }
            
            $validated = $validateUser->validated();
            $map = [];
            //--Type : Google, Facebook, ...
            $map['type'] = $validated['type'];
            $map['open_id'] = $validated['open_id'];
            
            $user = User::where($map)->first();
            
            if(empty($user->id)){
                // $validated['password'] = Hash::make($validated['password']);
                $validated['token'] = md5(uniqid().round(10000,99999));
                $validated['created_at'] = Carbon::now();
                // return response()->json($validated);
                
                $userId = User::insertGetId($validated);
                $userInfo = User::where('id', '=', $userId)->first();
                $accessToken = $userInfo->createToken(uniqid())->plainTextToken;
                
                $userInfo->accessToken = $accessToken;
                User::where('id', '=', $userId)->update(['access_token' => $accessToken]);
                
                return response()->json([
                    'code' => 200,
                    'msg' => 'User Created Successfully',
                    'data' => $userInfo,
                ], 200);
            }

            $accessToken = $user->createToken(uniqid())->plainTextToken;
            $user->access_token = $accessToken;
            User::where('open_id', '=', $validated['open_id'])->update(['access_token' => $accessToken]);

            // $user = User::create([
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'password' => Hash::make($request->password)
            // ]);

            return response()->json([
                'code' => 200,
                'msg' => 'User Loggedin Successfully',
                'data' => $user
                // 'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'mmsg' => $th->getMessage()
            ], 500);
        }
    }

    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'avatar' => 'required',
                'type' => 'required',
                'open_id' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]);
            
            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            
            $validated = $validateUser->validated();
            $map = [];
            //--Type : Google, Facebook, ...
            $map['type'] = $validated['type'];
            $map['open_id'] = $validated['open_id'];
            
            $user = User::where($map)->first();
            
            if(empty($user->id)){
                $validated['password'] = Hash::make($validated['password']);
                $validated['token'] = md5(uniqid().round(10000,99999));
                $validated['created_at'] = Carbon::now();
                // return response()->json($validated);
                
                $userId = User::insertGetId($validated);
                $userInfo = User::where('id', '=', $userId)->first();
                $accessToken = $userInfo->createToken(uniqid())->plainTextToken;
                
                $userInfo->accessToken = $accessToken;
                User::where('id', '=', $userId)->update(['access_token' => $accessToken]);
                
                return response()->json([
                    'status' => true,
                    'message' => 'User Created Successfully',
                    'data' => $userInfo,
                ], 200);
            }

            $accessToken = $user->createToken(uniqid())->plainTextToken;
            $user->access_token = $accessToken;
            User::where('open_id', '=', $validated['open_id'])->update(['access_token' => $accessToken]);

            // $user = User::create([
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'password' => Hash::make($request->password)
            // ]);

            return response()->json([
                'status' => true,
                'message' => 'User Loggedin Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
