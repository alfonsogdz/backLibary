<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Iluminate\Support\Facades\DB;
use App\User;

class UserController extends Controller
{
    public function register(Request $request){
        // recoger post
        $json = $request->input('json', null);
        $params = json_decode($json);
 
        $email =(!is_null($json) && isset($params->email)) ? $params->email : null;
        $name =(!is_null($json) && isset($params->name)) ? $params->name : null;
        $surname =(!is_null($json) && isset($params->surname)) ? $params->surname : null;
        $role = 'ROLE_USER';
        $password =(!is_null($json) && isset($params->password)) ? $params->password : null;
 
     if(!is_null($email) && !is_null($password) && !is_null($name)){
        
         //crea el usuario
         $user = new User();
         $user->email = $email;
         $user->name = $name;
         $user->surname = $surname;
         $user->role = $role;
 
         $pwd = hash('sha256', $password);
         $user->password = $pwd;
 
         //comprobar usuario existe
         $isset_user = User::where('email', '=', $email)->first();
 
         if(count($isset_user) == 0){
             //guarda el usuario
             $user->save();
             $data = array(
                 'status' => 'success',
                 'code' => 200,
                 'message' => 'Usuario Registrado correctamente' 
             );
 
         }else{
             //no guarda usuario
             $data = array(
                 'status' => 'error',
                 'code' => 400,
                 'message' => 'Usuario duplicado, no puede crearse' 
             );
 
         }
 
 
     }else{
         $data = array(
             'status' => 'error',
             'code' => 400,
             'message' => 'Usuario no creado' 
         );
     }
     return response()->json($data, 200);
 
 
    }
 

    public function login(Request $request){
        $jwtAuth = new JwtAuth();


        //recibir post
        $json = $request->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email :  null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password :  null;
        $getToken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken :  null;

        //cifrar contrase??a
        $pwd = hash('sha256', $password);

        if(!is_null($email) && !is_null($password) && ($getToken == null || $getToken == 'false')){
            $signup = $jwtAuth->signup($email, $pwd);

           // return response()->json($signup, 200);
        
        }elseif($getToken != null){
            //var_dump($getToken); die();
            
            $signup = $jwtAuth->signup($email, $pwd, $getToken);


        }else{
            $signup = array(
               'status' => 'error',
               'message' => 'Envia tus datos por post'
            );

        }

        return response()->json($signup, 200);


        }
   
    }


