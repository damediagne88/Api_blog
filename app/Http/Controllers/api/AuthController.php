<?php

namespace App\Http\Controllers\api;

use auth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){

        /*ICI ON FAIT UN VERIFICATION POUR 
        *VOIR SI REELLEMENT ON RECUPERE EMAIL ET PASSWORD DE USER CONNECTER
        */

        $creds = $request->only(['email','password']);

        if(!$token=auth()->attempt($creds)){

            return response()->json([

                'success' =>false,
                'message' =>'invalid credintials'
            ]);

        }

        return response()->json([
            'success' =>true,
            'token' =>$token,
            'user' => auth()->user()
        ]);

    }

    public function register(Request $request){

        //ICI JE CRYPTE LE PASSWORD AVANT DE LE SAUVEGARDER 
        $encryptedPass = Hash::make($request->password);

        //ICI JE CREER UNE NOUVELLE INSTANCE DE MON OBJET USER
        $user = new User;

        try{
        
        // ICI JE SAUVEGARDE LES DONNEES ENTRER PAR UTILISATEUR DANS LA BASE DE DONNEES

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $encryptedPass;
        $user->save();
        
        //ICI JE DEMANDE DE RETOURNER LE FUNCTION LOGIN() OU J AI EMAIL ET LE PASSWORD
        return $this->login($request);

        }catch(Exception $e){
        
            return response()->json([
                'success' =>false,
                'message'=>''.$e
            ]);

        }
    }

    // VERIFICATION DU LOGOUT 

    public function logout(Request $request){
        try{
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' =>true,
                'message'=>'logout succes',
            ]);

        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message'=>' '.$e,
            ]);
        }
        
    }
}
