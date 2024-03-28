<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Mail\Code;
use App\Mail\Correo;
use App\Mail\Succes;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Codigo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api_jwt', ['except' => ['hola', 'register', 'activate', 'logCode', 'verifyCode', 'checkActive', 'verifyToken']]);
    }

    public function hola(Request $request)
    {
        $name = $request->name;
        return response()->json(['message' => 'Hola ' . $name]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api_jwt')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logCode(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $email = $request->email;
        $password = $request->password;
        $credentials = ['email' => $email, 'password' => $password];
        if (auth('api_jwt')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
        $code = rand(100000, 999999);
        $codigo = Codigo::where('id_usuario', $user->id)->first();
        if ($codigo) {
            $codigo->codigo = $code;
            $codigo->save();
        } else {
            $codigo = new Codigo();
            $codigo->id_usuario = $user->id;
            $codigo->codigo = Hash::make($code);
            $codigo->save();
        }
        Mail::to($email)->send(new Code($code));
        return response()->json(['message' => 'Codigo enviado'], 200);
    }

    public function activate(User $user)
    {
        $user->is_active = 1;
        $user->activated_at = now();
        $user->save();
        Mail::to($user->email)->send(new Succes($user));
        return view('Succesfull.succes')->with('user', $user);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'code' => 'required|string|min:6|max:6|regex:/^[0-9]*$/',
        ]);

        $correo = $request->email;
        $contraseña = $request->password;
        $code = $request->codigo;
        $user = User::where('email', $correo)->first();
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $codigo = Codigo::where('id_usuario', $user->id)->first();
        if (!$codigo || !Hash::check($code, $codigo->codigo)) {
            return response()->json(['message' => 'Código incorrecto'], 400);
        }
        $credentials = ['email' => $correo, 'password' => $contraseña];
        if (!$token = Auth::guard('api_jwt')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function checkActive(User $user)
    {

        if ($user->is_active == true) {
            return response()->json(['active' => true]);
        }
        return response()->json(['active' => false]);
    }

    public function verifyToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['valid' => true, 'active' => $user->is_active, 'role' => $user->id_rol], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(["Errors" => $validator->errors()], 400);
        }
        $user = new User();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->id_hospital = 1;
        $user->password = Hash::make($request->password);
        $user->save();
        $signed_route = URL::temporarySignedRoute(
            'activate',
            now()->addMinutes(30),
            ['user' => $user->id]
        );
        Mail::to($request->email)->send(new Correo($signed_route));
        return response()->json([
            'message' => 'Usuario creado con éxito, revise su correo para activar la cuenta'
        ], 201);
    }
}
