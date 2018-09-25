<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use App\User;

class UserController extends ApiController
{
    public function __construct(){
      parent::__construct(); // parent (Controller) construct method
      $this->middleware('transform.input:' . UserTransformer::class)->only(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        // return root (index) + status
        // return response()->json(['data' => $users], 200);
        return $this->showAll($users);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required',
          'email' => 'required|email|unique:users',
          'password' => 'required|min:6|confirmed'
        ]);

        $fields = $request->all();
        $fields['password'] = bcrypt($request->password);
        $fields['verified'] = User::NOT_VERIFIED;
        $fields['verification_token'] = User::generateVerificationToken();
        $fields['admin'] = User::REGULAR;

        $user = User::create($fields);
        // return response()->json(['data' => $user], 201);
        return $this->showOne($user, 201);
    }

    // model injection as parameters
    public function show(User $user)
    {
        // $user = User::findOrFail($id);

        // return response()->json(['data' => $user], 200);
        return $this->showOne($user, 200);
    }

    // model injection
    public function update(Request $request, User $user)
    {
      // $user = User::findOrFail($id);

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
          // return response()->json(['error' => 'Verified users can only change their admin status', 'code' => 409], 409);
          return $this->errorResponse('Verified users can only change their admin status', 409);
        }
        $user->admin = $request->admin;
      }

      // need to change something for update the record

      if(!$user->isDirty()){
        // return response()->json(['error' => 'Must changed at least one text field to update', 'code' => 422], 422);
        return $this->errorResponse('Must changed at least one text field to update', 422);
      }

      $user->save();
      // return response()->json(['data' => $user], 200);
      return $this->showOne($user, 200);
    }

    public function destroy(User $user)
    {
        // $user = User::findOrFail($id);
        $user->delete();
        // return response()->json(['data', $user], 200);
        return $this->showOne($user, 200);
    }

    public function verify($token){
      $user = User::where('verification_token', $token)->firstOrFail();
      $user->verified = User::VERIFIED;
      // after validated, it set it to null
      $user->verification_token = null;
      $user->save();
      return $this->showMessage('Account Verified', 200);
    }

    public function resend(User $user){
      if($user->isVerified()){
        return $this->errorResponse('User already have been verified', 409);
      }

      retry(5, function() use ($user) {
        Mail::to($user->email)->send(new UserCreated($user));
      }, 100);

      return $this->showMessage('Verifying Email have been resend');
    }
}
