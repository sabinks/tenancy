<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct() {}
    public function getUser()
    {
        $user_id = Auth::user()->id;
        $user = User::whereId($user_id)->first();
        $response['name'] = $user->name;
        $response['email'] = $user->email;
        return $response;
        // $response['user_id'] = $user->id;
        // $response['is_active'] = $user->is_active;
        // $response['data'] = $user->data;
        // $response['count'] = Cart::whereClientId($user_id)->count();
        // $response['profile_image'] = $user->profile_image;
        $response = [];
        // $roles = $user->getRoleNames();
        // $response['roles'] = $roles;
        // if (in_array('Partner', $roles->toArray())) {
        //     $response['membership_type'] = $user->membership_type ? $user->membership_type->type->first(['id', 'name']) : null;
        //     $response['premium_services'] = $user->membership_type ? $user->membership_type->type->premium_services : null;
        // }
        return $response;
    }
}
