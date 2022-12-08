<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Helpers\JwtAuth;
use App\Book;

class BookController extends Controller
{
    public function index(Request $request){
       $books = Book::all()->load('user');
       return response()->json(array(
           'books' => $books,
           'status' => 'success'
       ), 200);
    }
    //muestra uno en especifico
    public function show($id){
        $book = Book::find($id);
        if(is_object($book)){
            $book = Book::find($id)->load('user');
            return response()->json(array('book' => $book, 'status' => 'success'),200);
        }else{
            return response()->json(array('message' => 'El libro no existe', 'status' => 'error'),200);

        }
         
    }

    public function store(Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            //recoger datos por post
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            //consigue usuario
            $user = $jwtAuth->checkToken($hash, true);

            //validacion
                $validate = \Validator::make($params_array, [
                    'title' => 'required|min:5',
                    'autor' => 'required|min:5',
                    'imagen' => 'required|min:5',
                    'description' => 'required',
                    'price'=> 'required',
                    'status' => 'required'
                ]);

                if($validate->fails()){
                    return response()->json($validate->errors(), 400);
                }

            //guardar el registro
            $book = new Book();
            $book->user_id = $user->sub;
            $book->title = $params->title;
            $book->autor = $params->autor;
            $book->imagen= $params->imagen;
            $book->description = $params->description;
            $book->price = $params->price;
            $book->status = $params->status;      
            $book->save();

            $data = array(
                'book' => $book,
                'status' => 'success',
                'code' => 200
            );
        
        }else{
           //devolver error
           $data = array(
            'message' => 'Login Incorrecto',
            'status' => 'error',
            'code' => 300
        );

        }
        return response()->json($data, 200);
    }

    public function update($id, Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){

            //recoger post
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            //validar datos
            $validate = \Validator::make($params_array,[
                'title' => 'required',
                'description' => 'required',
                'price'=> 'required',
                'status' => 'required'
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(), 400);
            }

            //actualizar el registro
            unset($params_array['id']);
            unset($params_array['user_id']);
            unset($params_array['created_at']);
            unset($params_array['user']);
            //var_dump($params_array);
            //die();

            $book = Book::where('id', $id)->update($params_array);

            $data = array(
                'book' => $params,
                'status' => 'success',
                'code' => 200
            );

        }else{
            //devuelve error
            $data = array(
                'message' => 'login incorrecto',
                'status' => 'error',
                'code' => 300
            );
        }

        return response()->json($data, 200);

    }

    //eliminar
    public function destroy($id, Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            //comprobar existe el registro
            $book = Book::find($id);

            //borrarlo
            $book->delete();

            //devolverlo
            $data = array(
                'car' => $book,
                'status' => 'success',
                'code' => 200
            );
        }else{
            $date = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'login incorrecto'
            );
        }

        return response()->json($data, 200);
    }


}// end class

