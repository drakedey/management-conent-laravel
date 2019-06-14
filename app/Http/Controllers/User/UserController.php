<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('register');
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            //6 caracteres de largo, 1 mayuscula, 1 minuscula, 1 caracter especia, sin espacio
            'password' => 'required|regex:/^(?=.*\d)(?=.*[\u002D-\u002E\u0040\u005F\u002A.])(?=.*[A-Z])(?=.*[a-z])\S{6,}$/',
            'confirmPassword' => 'required|same:password',
            'rol_id' => 'required'
        ]);

        if($validator->fails()) {
            return \response()->json(['error' => $validator->errors()], 400);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], 200);
    }

    public function getUserData(Request $request) {
        return \response()->json(['user' => $request->user()->toArray()], 200);
    }

    public function logout(Request $request) {
        if($request->user()) {
            $request->user()->tokens()->delete();
            return \response()->json(['success' =>'user logged out'], 200);
        }
        return \response()->json(['error' => 'user not valid'], 401);
    }
}