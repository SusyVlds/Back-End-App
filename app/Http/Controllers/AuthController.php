<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// insertamos las librerias que usaremos
use Illuminate\Support\Facades\Hash;
use App\Models\User;
//laravel nos da una libreria con el metodo auth para facilitarnos la vida en la autenticacion
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{//para asignrar una variable en php se usa el $nombreVariable
    
    public function register(Request $request)
    {//obtener todos los datos del request en un json
        $data = $request->json()->all();
        // Comprobar que no este vacio
        $itExistsUserName=User::where('email',$data['email'])->first();

        if ($itExistsUserName==null) {
            $user = User::create(
                [
                    'name'=>$data['name'],
                    'email'=>$data['email'],
                    'password'=>Hash::make($data['password'])

                ]
            );
  //al usuario que creemos tenemos que asignarle un token
            $token = $user->createToken('web')->plainTextToken;


                return response()->json([
                    'data'=>$user,
                    'token'=> $token

                ],200);// tiempo de respuesta, si excede marca un error
        } else {
               return response()->json([
                'data'=>'User already exists!',
                'status'=> false
            ],200);
       }

   }

   //ingresar por correo y contraseña
   public function login (Request $request){            //objeto que contiene la info enviada por el usuario Request
        if(!Auth::attempt($request->only('email', 'password'))){  //si es falso
        return response()->json([    //regresa un mensaje en formato json en consola 
            'message'=>'Correo o contraseña incorrecto',
            'status'=> false
        ],400);
    } 
   

   //si  se encuentra
   $user = User::where('email', $request['email'])->firstOrFail();

   //generar un token de acceso, que cambie cada que entre 
   $token = $user->createToken('web')->plainTextToken;

   return response()->json([
    'data'=> $user,
    'token'=>$token
   ]);
   
}
public function logout(Request $request){
   $request->user()->currentAccessToken()->delete();

   return response()->json([   
           //se crea una respuesta 
'status'=>true
   ]);
}

public function newPassword($email){ //recbie de parametro el email 
        //se verifica que el email corrsponda a un usuario 
    $user = User::where('email', $email)->first();  // v o f    recupera todo del usario 
    // es una consulta de select donde regresa al usuario con el mismo email 
        
    if (!$user) //si es falso, no se encuentra a nadie 
    {
        return response()->json(['message' => 'El usuario no esta registrado en la base de datos'], 200);
    }
    else   //si es verdadero, se encuentra el correro 
    {
       
    $nuevaContraseña = Str::random(6);  //se crea una nueva variable para la contraseña, esta será aleatoria 
        
       
    $user->password = Hash::make($nuevaContraseña);   //en el campo password se guarda la nueva contraseña, hash encripta 
        
    $user->save();  //se guardan los cambios en la bd
        
    //se regresa la respuesta en formato json     
    return response()->json([
        'new_password' => $nuevaContraseña,   //se regresa sin encriptar 
        'message' => 'La contraseña a sido actualizada correctamente',
    ], 200);
    }
}

public function crearFoto(Request $request, $user_id){
    DB::table('fotos')->insert([
        'user_id' => $user_id,
        'foto' => $request -> selectedImage,
    ]);

    return response()->json([
        'message' => $request -> selectedImage,
    ]);
}

}
