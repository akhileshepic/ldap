<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class ApitestController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'uid' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('uid', 'password');

        if (Auth::guard('ldap')->attempt($credentials)) {
            $session['user'] = Auth::guard('ldap')->user();
            $session['token'] = $session['user']->createToken('API Token')->plainTextToken;
 
            return response()->json(['data' => $session]);
        }

        throw ValidationException::withMessages([
            'username' => ['Invalid credentials'],
        ]);
    }



    public function ldapUsers(Request $request)
    {
        // Authenticate the user using the access token
        if (!Auth::guard('ldap')->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

       
        // Ensure the user is from the LDAP guard
        if (!Auth::guard('ldap')->check()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Fetch the list of LDAP users
        $ldapUsers = Adldap::search()->get();

        return response()->json(['ldap_users' => $ldapUsers]);
    }
}
