<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Log;

class AuthController extends Controller
{

    // Show Login Page
    public function showLogin()
    {
        return view('login');
    }




    // Login Process
    public function login(Request $request)
    {

        $credentials = $request->validate([

            'username' => 'required',

            'password' => 'required',

            'role' => 'required',

        ]);



        Log::info('Login attempt', [

            'username' => $request->username,

            'role' => $request->role

        ]);




        // Check username + password + role

        if(Auth::attempt([

            'username' => $request->username,

            'password' => $request->password,

            'role' => $request->role

        ]))
        {


            // Regenerate session

            $request->session()->regenerate();



            $user = Auth::user();



            Log::info('Login successful', [

                'username'=>$user->username,

                'role'=>$user->role

            ]);






            // Redirect according to role

            switch($user->role)
            {


                case 'admin':


                case 'staff':


                    return redirect('/dashboard')

                    ->with(

                        'success',

                        'Login successful! Welcome back '.$user->username.'.'

                    );





                case 'patient':


                case 'user':


                    return redirect()

                    ->route('userDashboard')

                    ->with(

                        'success',

                        'Login successful! Welcome back '.$user->username.'.'

                    );





                default:


                    Auth::logout();


                    return redirect()

                    ->route('login')

                    ->withErrors([

                        'login_error'=>'Invalid user role.'

                    ]);

            }



        }





        // Login Failed

        return back()

        ->withErrors([

            'login_error'=>'Invalid username, password or role.'

        ])

        ->onlyInput('username');


    }









    // Register Process

    public function register(Request $request)
    {


        $validated = $request->validate([



            'role'=>

            'required|in:admin,patient,staff',





            'username'=>

            'required|string|max:255|unique:user,username',





            'gender'=>

            'required|in:male,female',





            'password'=>

            'required|string|min:8|confirmed',




        ]);








        User::create([



            'role'=>$validated['role'],



            'username'=>$validated['username'],



            'gender'=>$validated['gender'],



            'password'=>Hash::make($validated['password']),



        ]);







        return redirect()

        ->route('login')

        ->with(

            'success',

            'Account created successfully! Please login.'

        );


    }









    // Logout Process

    public function logout(Request $request)
    {


        Auth::logout();



        $request->session()->invalidate();



        $request->session()->regenerateToken();




        return redirect('/');


    }


}