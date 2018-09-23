<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        // return root (index) + status
        return response()->json(['data' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $fields = $request->all();
        $fields['password'] = bcrypt($request->password);
        $fields['verified'] = User::NOT_VERIFIED;
        $fields['verification_token'] = User::generateVerificationToken();
        $fields['admin'] = User::REGULAR;

        $user = User::create($fields);
        return response()->json(['data' => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $user = User::findOrFail($id);

      $this->validate($request,[
        'email' => 'email|unique:users,email,'. $user->id,
        'password' => 'min:6|confirmed',
        'admin' => 'in: '. User::ADMIN. ',' . User::REGULAR,
      ]);

      if($request->has('name')){
        $user->name = $request->name;
      }

      if($request->has('email')){
        if($user->email != $request->email){
          $user->verified = User::NOT_VERIFIED;
          $user->verification_token = User::generateVerificationToken();
          $user->email = $request->email;
        }
      }

      if($request->has('password')){
        $user->password = bcrypt($request->password);
      }

      if($request->has('admin')){
        if(!$user->isVerified()){
          return response()->json(['error' => 'Verified users can only change their admin status', 'code' => 409], 409);
        }
        $user->admin = $request->admin;
      }

      // need to change something for update the record

      if(!$user->isDirty()){
        return response()->json(['error' => 'Must changed at least one text field to update', 'code' => 422], 422);
      }

      $user->save();
      return response()->json(['data' => $user], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['data', $user], 200);
    }
}
