<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {   
        try {
            if ($id=='all') {
                $res = User::query()->whereNull('deleted_at')->orderBy('id', 'desc')->get();
            } else {
                $res = User::whereNull('deleted_at')->findOrFail($id);
            }
            return response($res, 200);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()], 400);        
        }
    }

    public function unsubscribe ($id) 
    {
        try {
            User::findOrFail($id)
                ->delete();
            return response(['id' => $id], 200);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()], 400);        
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        try {
            $req = request()->all();

            $fields = [
                'username' => 'required|string|unique:user|max:255|min:8',
                'email' => 'required|email|unique:user|max:255',
                'password' => 'required|min:8',
                'phone_number' => 'required|min:11'
            ];
            $errmsgs = [
                'username.required' => 'The username field is required.',
                'username.unique' => 'This username has already been taken.',
                'username.min' => 'This username must be at least 8 characters.',
                'email.required' => 'The email field is required.',
                'email.email' => 'The email must be a valid email address.',
                'email.unique' => 'The email has already been taken.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'phone_number.required' => 'The phone number is required.',
                'phone_number.min' => 'The phone number must be at least 11 characters.',
            ];

            if ($req['method'] !== 'create') {
                $fields['username'] = 'required|string|max:255|min:8';
                $fields['email'] = 'required|email|max:255|min:8';
            }

            $validator = Validator::make($req, $fields, $errmsgs);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $registerData = [
                'username' => $req['username'],
                'password' => $req['password'],
                'email' => $req['email'],
                'phone_number' => $req['phone_number'],
                'designation' => 0,
            ];

            $conditions = [
                'email' => $req['email'],
                'username' => $req['username']
            ];

            $userData = [];

            if ($req['method'] !== 'create') {
                $errmsg = [];
                $email = User::where ([ ["email", $req['email']] ])->first();
                $usern = User::where ([ ["username", $req['username']]  ])->first();

                if ($email && ($email->id != $req['method'])) {
                    $errmsg['email'][] = 'Email already exists!';
                }

                if ($usern && ($usern->id != $req['method'])) {
                    $errmsg['username'][] = 'Username already exists!';
                }

                if (count($errmsg) > 0) return response()->json(['errors' => $errmsg], 422);

                //$userData = User::where('id', $req['method'])->update($registerData);
                $conditions = [
                    'id' => $req['method'],
                ];

            }

            $userData = User::updateOrCreate ($conditions, $registerData);
            
            return response(['res' => $userData], 200);

        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()], 400);
        }
    }


    public function authUser (Request $request)
    {
        try {
            $req = request()->all();

            $res = User::
                    where ('password', $req['password']) 
                    -> where ('email', $req['username']) 
                    -> orWhere ('username', $req['username'])
                    -> first();
            
            //$request->session()->put('login_data', $res);

            return response($res, 200);

        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    public function getSession (Request $request) {
        //$session = $request->session()->get('key');
        return response(['session' => []], 200);
    }

}
