<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

/**
 * Flujo de "olvide mi contraseña" en 3 pasos:
 *   1) El usuario escribe su correo -> se genera un codigo de 6 digitos.
 *   2) El usuario escribe el codigo -> si es valido, puede continuar.
 *   3) El usuario define su nueva contraseña.
 *
 * Nota importante para explicar en clase: en un sistema en produccion
 * el codigo se enviaria por correo (Mail::send / Notification). Como
 * este proyecto no tiene un servidor SMTP real configurado, el codigo
 * se muestra directamente en pantalla (y tambien queda en
 * storage/logs/laravel.log) para poder demostrar el flujo completo.
 */
class PasswordResetController extends Controller
{
    /**
     * Paso 1: formulario para pedir el correo.
     */
    public function mostrarSolicitud(Request $request)
    {
        return view('auth.olvide-password', [
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Paso 1: genera y guarda un codigo de 6 digitos valido por 15 minutos.
     */
    public function enviarCodigo(Request $request)
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $usuario = User::where('email', $datos['email'])->first();

        // Por seguridad no decimos si el correo existe o no: siempre
        // mostramos el mismo mensaje. Pero solo generamos el codigo
        // si el usuario realmente existe.
        if ($usuario) {
            $codigo = (string) random_int(100000, 999999);

            DB::table('password_resets')->insert([
                'email' => $usuario->email,
                'codigo' => $codigo,
                'expira_en' => now()->addMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // "Envio" del codigo: se deja en el log del sistema.
            Log::info("Codigo de recuperacion de contraseña para {$usuario->email}: {$codigo}");

            return redirect()
                ->route('password.codigo', ['email' => $usuario->email])
                ->with('exito', 'Generamos un codigo de verificacion.')
                ->with('codigo_demo', $codigo);
        }

        return redirect()
            ->route('password.olvide')
            ->with('exito', 'Si el correo existe en el sistema, se genero un codigo de verificacion.');
    }

    /**
     * Paso 2: formulario para escribir el codigo recibido.
     */
    public function mostrarCodigo(Request $request)
    {
        return view('auth.codigo-password', [
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Paso 2: valida el codigo y, si es correcto, deja pasar al paso 3.
     */
    public function verificarCodigo(Request $request)
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'codigo' => ['required', 'digits:6'],
        ]);

        $registro = DB::table('password_resets')
            ->where('email', $datos['email'])
            ->where('codigo', $datos['codigo'])
            ->where('expira_en', '>=', now())
            ->latest('id')
            ->first();

        if (! $registro) {
            return back()->withErrors([
                'codigo' => 'El codigo es invalido o ya expiro. Solicita uno nuevo.',
            ])->withInput();
        }

        return redirect()->route('password.restablecer', [
            'email' => $datos['email'],
            'codigo' => $datos['codigo'],
        ]);
    }

    /**
     * Paso 3: formulario para la nueva contraseña.
     */
    public function mostrarRestablecer(Request $request)
    {
        return view('auth.restablecer-password', [
            'email' => $request->query('email', ''),
            'codigo' => $request->query('codigo', ''),
        ]);
    }

    /**
     * Paso 3: vuelve a validar el codigo (no confiamos solo en la URL)
     * y actualiza la contraseña del usuario.
     */
    public function restablecer(Request $request)
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'codigo' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $registro = DB::table('password_resets')
            ->where('email', $datos['email'])
            ->where('codigo', $datos['codigo'])
            ->where('expira_en', '>=', now())
            ->latest('id')
            ->first();

        if (! $registro) {
            return back()->withErrors([
                'codigo' => 'El codigo es invalido o ya expiro. Solicita uno nuevo.',
            ]);
        }

        $usuario = User::where('email', $datos['email'])->firstOrFail();
        $usuario->update(['password' => Hash::make($datos['password'])]);

        // El codigo ya se uso, se elimina para que no se pueda reutilizar.
        DB::table('password_resets')->where('email', $datos['email'])->delete();

        return redirect()->route('login')->with('exito', 'Tu contraseña fue actualizada. Ya puedes iniciar sesion.');
    }
}