<?php

namespace App\Http\Controllers\Auth;

use App\Events\testWebsocket;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Mail\Code;
use App\Mail\Correo;
use App\Mail\Succes;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Codigo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api_jwt', ['except' => [
            'register',
            'activate', 'logCode', 'verifyCode', 'checkActive', 'verifyToken', 'restablecer',
            'recoveryPassword', 'resetPassword', 'test'
        ]]);
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

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api_jwt')->factory()->getTTL() * (60 * 24)
        ]);
    }

    public function logCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $email = $request->email;
        $password = $request->password;
        $user = User::where('email', $email)->first();
        $credentials = ['email' => $email, 'password' => $password];
        if (!Auth::guard('api_jwt')->attempt($credentials)) {
            return response()->json(['credenciales' => 'Credenciales incorrectas'], 401);
        }
        $code = rand(100000, 999999);
        $codigo = Codigo::where('user_id', $user->id)->first();
        if ($codigo) {
            $codigo->codigo = Hash::make($code);
            $codigo->save();
        } else {
            $codigo = new Codigo();
            $codigo->user_id = $user->id;
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
        return view('Successfull.success')->with('user', $user);
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'codigo' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $correo = $request->email;
        $contraseña = $request->password;
        $code = $request->codigo;
        $user = User::where('email', $correo)->first();
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $codigo = Codigo::where('user_id', $user->id)->first();
        if (!$codigo || !Hash::check($code, $codigo->codigo)) {
            return response()->json(['message' => 'Código incorrecto', $code], 400);
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
            'name' => 'required|string|max:100|min:3|regex:/^[a-zA-Z ]*$/',
            'last_name' => 'required|string|max:100|min:3|regex:/^[a-zA-Z ]*$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:password',
            'id_hospital' => 'integer|required'
        ]);
        if ($validator->fails()) {
            return response()->json(["Errors" => $validator->errors()], 400);
        }
        $user = new User();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->id_hospital = $request->id_hospital;
        $user->password = Hash::make($request->password);
        $user->save();
        $signed_route = URL::temporarySignedRoute(
            'activate',
            now()->addMinutes(30),
            ['user' => $user->id]
        );
        Mail::to($request->email)->send(new Correo($signed_route));
        return response()->json([
            'message' => 'Usuario creado con éxito, revise su correo para activar la cuenta',
            'id' => $user->id
        ], 201);
    }

    public function restablecer(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado'], 404);
        }

        $resetUrl = URL::temporarySignedRoute(
            'recoveryPassword',
            now()->addMinutes(30),
            ['user' => $user->id]
        );
        Mail::to($request->email)->send(new ResetPassword($resetUrl));

        return response()->json(['msg' => 'Correo enviado', $user], 200);
    }
    public function recoveryPassword(User $user)
    {
        $user = User::where('id', $user->id)->first();
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado'], 404);
        }
        $email = $user->email;

        return view('restPassword')->with('email', $email);
    }

    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado'])->withInput();
        }

        $request->validate([
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8'
        ]);

        if ($request->password != $request->confirm_password) {
            return back()->withErrors(['password' => 'Las contraseñas no coinciden'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('restPassword')->with('success', 'Contraseña restablecida correctamente');
    }

    /*public function test()
    {
        event(new testWebsocket);
    }*/
}
