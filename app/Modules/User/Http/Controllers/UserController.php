<?php

namespace App\Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UserController
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

     public function register(Request $request)
     {
         // Define validation rules for user registration
         $rules = [
             'matricule' => 'required|unique:users',
         ];

         // Validate the request data
         $validator = Validator::make($request->all(), $rules);

         // If validation fails, return error response
         if ($validator->fails()) {
             return [
                 "error" => $validator->errors()->first(),
                 "status" => 422
             ];
         }

         try {
             // Create the new user
             $user = User::create([
                 'matricule' => $request->matricule,
                 'firstname' => $request->firstname,
                 'lastname' => $request->lastname,
                 'shift' => $request->shift,
                 'password' => Hash::make("123456")

             ]);

             return [
                 "payload" => $user,
                 "message" => "User created successfully",
                 "status" => 201
             ];
         } catch (\Exception $e) {
             return [
                 'error' => $e->getMessage(),
                 'status' => 500
             ];
         }
     }
}
