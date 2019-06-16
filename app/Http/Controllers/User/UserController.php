<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Rol;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\Hash;

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

    public function updateUserInfo(Request $request, int $id) {
        $user = User::find($id);

        if($user == null) {
            return \response()->json(['error' => 'user not found'], 400);
        }


        if($id != $request->user()->id && $request->user()->rol->name != Rol::ADMIN) {
            return \response()->json(['error' => 'Unhautorized'], 401);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users'
        ]);

        if($validator->fails()) {
            return \response()->json(['error' => $validator->errors()], 400);
        }

        if (!$user->isDirty()) {
            return response()->json(['error' => 'You must specify at least one different value to update', 'code' => 422], 422);
        }

        $user->update($input);

        return \response()->json([
            'success' => 'updated succesfully',
            'user' => $user->toArray()
        ], 200);

    }

    public function softDeleteUser(Request $request, int $id) {
        $user = User::find($id);

        if($user == null) {
            return \response()->json(['error' => 'user not found'], 400);
        }

        if($request->user()->rol->name != Rol::ADMIN) {
            return \response()->json(['error' => 'Unhautorized'], 401);
        }

        //soft delete
        $user->update(['state' => false]);
        return \response()->json([
            'success' => 'updated succesfully',
            'user' => $user->toArray()
        ], 200);
    }

    public function getAllUsers(Request $request) {
        // implements security limitations
        if($request->user()->rol->name == Rol::ADMIN) {
            return \response()->json(['users' => User::all()], 200);
        }
        return \response(['error' => 'Unhautorized'], 401);
    }

    public function updateUserPassword(Request $request, int $id) {
        $user = User::find($id);

        if($user == null) {
            return \response()->json(['error' => 'user not found'], 400);
        }


        if($id != $request->user()->id) {
            return \response()->json(['error' => 'Unhautorized'], 401);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'password' => 'required',
            //6 caracteres de largo, 1 mayuscula, 1 minuscula, 1 caracter especia, sin espacio
            'n_password' => 'required|regex:/^(?=.*\d)(?=.*[\u002D-\u002E\u0040\u005F\u002A.])(?=.*[A-Z])(?=.*[a-z])\S{6,}$/',
            'confirmPassword' => 'required|same:n_password',
        ]);

        if($validator->fails()) {
            return \response()->json(['error' => $validator->errors()], 400);
        }


        if(!Hash::check($input['password'], $user->password)) {
            return \response()->json(['error' => 'Password doesn\'t match ' ], 400);
        }
        $input['n_password'] = bcrypt($input['n_password']);
        $user->update(['password' => $input['n_password']]);
        return \response()->json([
            'success' => 'Password Change successfully'
        ], 200);
    }
}