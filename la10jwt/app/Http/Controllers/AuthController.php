<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth:api', ['except' => ['dangky']]);
//    }

    public function dangky(UserStoreRequest $request)
    {
        $data['email'] = $request->get('email') ?: null;
        $data['password'] = $request->get('password') ?: null;
        $data['password_confirmation'] = $request->get('password_confirmation') ?: null;
        $data['name'] = $request->get('name') ?: null;
        $data_return = array();
        if ($data['password'] == $data['password_confirmation']) {
            $user = User::create($data);
            if ($user) {
                $data_return['status'] = 1;
                $data_return['data'] = $user;
            }
        } else {
            $data_return['status'] = 0;
        }
        return response()->json($data_return);
    }

    public function login()
    {
        $credentials = \request(['email', 'password']);
        $token = Auth::guard('api')->attempt($credentials);
        $data =[
            'user' => Auth::guard('api')->user()->id,
            'time_created' =>time(),
        ];
        $refresh_token = JWTAuth::getJWTProvider()->encode($data);
        return $this->responseWithToken($token,$refresh_token);
    }
    public function refresh(Request $request){

        $data = JWTAuth::getJWTProvider()->decode($request->refresh_token);
        $user = User::find($data['user']);
        if($user)
        {
            $token = Auth::guard('api')->login($user);
            $data =[
                'user' => Auth::guard('api')->user()->id,
                'time_created' =>time(),
            ];
            $refresh_token = JWTAuth::getJWTProvider()->encode($data);
            return $this->responseWithToken($token,$refresh_token);
        }
    }

    public function logout()
    {
        Auth::guard('api')->logout(true);
        return response()->json(['status'=>'Logout Success']);
    }
    public function getUser()
    {
        $user = Auth::guard('api')->user()?:null;
        return response()->json([
            'status'=>1,
            'user' => $user
        ]);
    }

    protected function responseWithToken($token, $refresh_token = null)
    {
        return response()->json([
            'token' => $token,
            'expires' => \auth('api')->factory()->getTTL() * 60,
            'token_type' => 'bearer',
            'refresh_token' => $refresh_token,
        ]);
    }
}
