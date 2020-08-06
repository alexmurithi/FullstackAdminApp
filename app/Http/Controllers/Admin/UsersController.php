<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =User::orderBy('created_at','DESC')->get();
        return response()->json($users);
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
        $this->validate($request,[
          'fullName'=>'required',
          'email'=>'bail|required|unique:users|email',
          'password'=>'bail|required|min:8',
          'userType'=>'required'
        ]);

        $pass =bcrypt($request->password);

        return User::create([
          'fullname'=>$request->fullName,
          'email'=>$request->email,
          'password'=>$pass,
          'userType'=>$request->userType
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $this->validate($request,[
        'id'=>'required',
        'fullname'=>'required',
        //if no change of email then lets this email remain as it is, otherwise update//
        'email'=>"bail|email|required|unique:users,email,$request->id",
        'password'=>'min:8',
        'confirmPassword'=>'same:password',
        'userType'=>'required'
      ]);

      $data =[
        'fullname'=>$request->fullname,
        'email'=>$request->email,
        'userType'=>$request->userType
      ];

      if(!empty($request->password) && !empty($request->confirmPassword)){
        $data['password'] =bcrypt($request->password);
      }

       User::where('id',$request->id)->update($data);
      

      $users =User::orderBy('created_at','DESC')->get();
      return response()->json($users);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
