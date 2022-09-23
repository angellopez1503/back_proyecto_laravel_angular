<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Http\Request;
// use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function ingresar(Request $request)
    {
        // validar
        $credenciales = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        // verificar
        if (!Auth::attempt($credenciales)) {
            return response()->json(
                [
                    'mensaje' => 'No Autorizado',
                ],
                401
            );
        }

        // generar token
        $user = $request->user();
        $resultadoToken = $user->createToken('Token Acceso');
        $token = $resultadoToken->plainTextToken;

        // responder
        return response()->json(
            [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'usuario' => $user,
            ],
            201
        );
    }
    public function registrar(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users|email',
            'password' => 'required',
        ]);
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);

            if ($user->save()) {
                return response()->json(
                    ['mensaje' => 'Usuario registrado'],
                    201
                );
            } else {
                return response()->json(
                    [
                        'mensaje' => 'Ocurrio un error al registrar el usuario',
                    ],
                    422
                );
            }
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'mensaje' => 'Ocurrio un error en el servidor',
                    'error' => $e,
                ],
                500
            );
        }
    }

    public function perfil()
    {
        $user =Auth::user();
        return response()->json($user);
    }

    public function salir()
    {
        $user = Auth::user()->tokens()->delete();

         return response()->json(
                [
                    'mensaje' => 'Logout'
                ],
                200
            );

    }
}
