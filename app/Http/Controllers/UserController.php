<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //metodo para ver el usuario que ya inicio sesion 
    /*public function showById($id){     //va a mostrar el usuario dependiendo del id que tenga 
        $user = User::find($id);

        return response()->json(["data"=>$user]);   //va a traer todo lo que contenga user 
    }  */
    
    public function showById($id){
        $user = User::find($id);
        return response()->json(["data"=>$user]);
      }
}
