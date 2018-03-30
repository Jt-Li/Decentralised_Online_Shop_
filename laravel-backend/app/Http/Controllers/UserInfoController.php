<?php

namespace App\Http\Controllers;

use App\User;
use App\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use Exception;
use Validator;
>>>>>>> 18b13cdd9a86278ae0dcdec0583b3c219c54f507
class UserInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        try{

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

    /**
     * Display the specified resource.
     *
     * @param  \App\UserInfo  $userInfo
     * @return \Illuminate\Http\Response
     */
    public function show(UserInfo $userInfo)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserInfo  $userInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(UserInfo $userInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserInfo  $userInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserInfo $userInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserInfo  $userInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserInfo $userInfo)
    {
        //
    }

    public function checkIfUserExists($address){

        $user = User::where('address', '=', $address)->first();
        if ($user === null) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        return response()->json(['user' => $user->id]);
    }
}
