<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            $rules = [
                "email" => "required|exists:admins,email",
                "password" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->codeAccordingToInput($validator);
                return $this->validationError($code, $validator);
            }

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('admin-api')->attempt($credentials);

            if (!$token) {
                return $this->responseErrorMessage(404, 'There is a wrong in your informations!');
            }

            $admin = Auth::guard('admin-api')->user();
            $admin->api_token = $token;

            return $this->responseSuccessData('admin', $admin, "success");
        } catch (\Exception $ex) {
            return $this->responseErrorMessage($ex->getCode(), $ex->getMessage());
        }
    }


    public function logout(Request $request)
    {
        $token = $request->header('auth-token');
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return  $this->responseErrorMessage(404, 'some thing went wrongs');
            }
            return $this->successMessage(200, 'Logged out successfully');
        } else {
            return $this->responseErrorMessage(404, 'some thing went wrongs');
        }
    }


    public function userLogin(Request $request)
    {
        try {
            $rules = [
                "email" => "required|exists:users,email",
                "password" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->codeAccordingToInput($validator);
                return $this->validationError($code, $validator);
            }

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('user-api')->attempt($credentials);

            if (!$token) {
                return $this->responseErrorMessage(404, 'There is a wrong in your informations!');
            }

            $user = Auth::guard('user-api')->user();
            $user->api_token = $token;

            return $this->responseSuccessData('user', $user, "success");
        } catch (\Exception $ex) {
            return $this->responseErrorMessage($ex->getCode(), $ex->getMessage());
        }
    }
}
