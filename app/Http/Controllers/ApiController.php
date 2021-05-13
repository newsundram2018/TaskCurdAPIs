<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Validator;
class ApiController extends Controller
{
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
         
         'email' => 'required',
        
         'password' => 'required',
        ]);

        
        if($validator->fails()) {
        return response()->json(
            [
                'response_code' => 401,
                'message' => $validator->errors()
            ],
            200
        );
        }

        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])){
           $user = Auth::user();
            
            $result['name']           =  $user->name;
            $result['email']          =  $user->email;
            $result['created']        =  $user->created_at;
            return response()->json(['message'=>'User logged in successfully','data'=> $result,'responsecode'=>200],200);
        }
        else{
            return response()->json(['message'=>'Please enter valid credentials','responsecode'=>400],400);
        }
    }

    public function register(Request $request ){
          $validator = Validator::make($request->all(),[
        
         'name' => 'required',
         
         'email' => 'required',
        
         'password' => 'required',
        ]);

        if($validator->fails()) {
        return response()->json(
            [
                'response_code' => 401,
                'message' => $validator->errors()
            ],
            200
        );
    }
    
            $name=$request->get('name');
            $email=$request->get('email');
            $password=$request->get('password');
            $user=new User;

            $user->name=$name;
            $user->email=$email;
            $user->password=bcrypt($password);
           if( $user->save()){
                return response()->json(['message'=>'User register in successfully','responsecode'=>200],200);
           }else{
                return response()->json(['message'=>'Something went wrong','responsecode'=>500],500);
           }

    
        }

    public function deleteUser($id){
            $user=User::where('id',$id)->delete();
            if($user){
                return response()->json(['message'=>'User Deleted in successfully','responsecode'=>200],200);
            }else{
                return response()->json(['message'=>'Something went wrong','responsecode'=>500],500);
            }
    }

    public function showData(){
        $user=User::all();
        return response()->json($user);
    }
}