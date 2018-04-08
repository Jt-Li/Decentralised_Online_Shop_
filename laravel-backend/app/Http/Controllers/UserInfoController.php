<?php

namespace App\Http\Controllers;

use App\User;
use App\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Validator;

class UserInfoController extends Controller
{
    
   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'name' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()->all()], 404);
        }

        $newUserData = $request->only(['address', 'email', 'name']);
        DB::beginTransaction();
        try {
            $user = new User();
            $user->fill($newUserData);
            $user->save();
            DB::commit();
            return response()->json($user, 201);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function checkIfUserExists($address){

        $user = User::where('address', '=', $address)->first();
        if ($user === null) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        return response()->json($user);
    }
}
