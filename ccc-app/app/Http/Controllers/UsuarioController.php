<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use \DateTimeZone;

class UsuarioController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function entrar(Request $request)
    {
        $usuario = trim($request->input("Usuario"));
        $hashPassword = $request->input("Contraseña");


        $usuarioDB = DB::table("usuarios")
            ->select("nombre", "password", "id", "permisos", "intentos", "tiempo_bloqueado")
            ->where("nombre", $usuario)
            ->first();

        if ($usuarioDB) {
            if (Hash::check($hashPassword, $usuarioDB->password)) {
                DB::table('usuarios')
                    ->where('id', $usuarioDB->id)
                    ->update(['intentos' => 0, 'tiempo_bloqueado' => null]);

                $token = Hash::make(Str::random(60));
                session(["token" => $token]);
                session(["usuario" => $usuarioDB->id]);
                switch ($usuarioDB->permisos) {
                    case "40":
                    case "50":
                        session(["ver" => "todo"]);
                        break;
                    case "30":
                        session(["ver" => "moderador"]);
                        break;
                    case "20":
                        session(["ver" => "editor"]);
                        break;
                    case "10":
                        session(["ver" => "lector"]);
                        break;
                    default:
                        session(["ver" => "nada"]);
                        break;
                }
                return redirect()->route("get-crimen", ['winterhorn' => $token]);
            } else {
                $intentos = $usuarioDB->intentos ?? 0;
                $intentos++;
                $tiempo_bloqueado = $usuarioDB->tiempo_bloqueado ? Carbon::parse($usuarioDB->tiempo_bloqueado) : Carbon::now();

                if ($intentos > 10) {

                    $tiempo_transcurrido = Carbon::now()->diffInSeconds($tiempo_bloqueado);

                    if ($tiempo_transcurrido < 7200) {
                        $tiempo_restante = 7200 - $tiempo_transcurrido;
                        $horas = intdiv($tiempo_restante, 3600);
                        $minutos = intdiv($tiempo_restante % 3600, 60);

                        return back()->with([
                            'error' => "Has superado el límite de intentos de inicio de sesión.",
                            'tiempoEspera' => "$horas horas y $minutos minutos"
                        ]);
                    } else {
                        $intentos = 0;
                        $tiempo_bloqueado = Carbon::now()->addHours(2);
                    }
                }

                DB::table('usuarios')
                    ->where('id', $usuarioDB->id)
                    ->update([
                        'intentos' => $intentos,
                        'tiempo_bloqueado' => $tiempo_bloqueado
                    ]);

                return back()->with('error', "Usuario o contraseña inválidos. NºIntento: $intentos");
            }
        } else {
            return back()->with("error", "No existe el usuario indicado");
        }
    }

    public function crear(Request $request)
    {
        $nombre = trim($request->input("nombre"));
        $contrasena = $request->input("contrasena");
        $permisos = $request->input("permisos");
        $creado = Carbon::now(new DateTimeZone('Europe/Madrid'));
        $creado = $creado->format('Y-m-d H:i:s');
        $actualizado = Carbon::now(new DateTimeZone('Europe/Madrid'));
        $actualizado = $actualizado->format('Y-m-d H:i:s');

        //Validamos los datos
        $resultado = "";
        $usuarioExiste = DB::table("usuarios")->where("nombre", $nombre)->exists();
        if ($usuarioExiste) {
            $titulo = "ERROR: EL USUARIO YA EXISTE";
            $resultado = "Por favor, introduzca un usuario que no exista en la base de datos.";
            $codigo = 1;
        } else if (strlen($nombre) <= 0 || strlen($nombre) > 45) {
            $titulo = "ERROR: NOMBRE DE USUARIO NO VALIDO";
            $resultado = "Por favor, introduzca un nombre de usuario cuyos caracteres estén en el rango indicado: [1 - 45].";
            $codigo = 2;
        }  else if ($permisos != 10 && $permisos != 20 && $permisos != 30 && $permisos != 40 && $permisos != 50) {
            $titulo = "ERROR: PERMISOS INCORRECTOS";
            $resultado = "Por favor, introduzca los permisos adecuados.";
            $codigo = 4;
        } else {
            $resultado = DB::table("usuarios")->insert([
                "nombre" => $nombre,
                "password" => Hash::make($contrasena),
                "permisos" => $permisos,
                "creado" => $creado,
                "actualizado" => $actualizado,
            ]);
            $titulo = "USUARIO AGREGADO EXITOSAMENTE";
            $resultado = "El usuario '$nombre' con permisos '$permisos' ha sido creado correctamente en la base de datos.";
            $codigo = 0;
        }
        return redirect()->route("crear-usuario", [
            'winterhorn' => session("token"),
            "resultado" => $resultado,
            "codigo" => $codigo,
            "titulo" => $titulo
        ]);
    }

    public function editar(Request $request)
    {
        $id = $request->input("id_usuario");
        $nombre = trim($request->input("nombre"));
        $contrasena = null;
        if (strlen($request->input("password")) > 30) {
            $contrasena = $request->input("password");
        } else {
            $contrasena = Hash::make($request->input("password"));
        }
        $permisos = $request->input("permisos");
        $creado = $request->input("creado");

        $actualizado = Carbon::now(new DateTimeZone('Europe/Madrid'));
        $actualizado = $actualizado->format('Y-m-d H:i:s');

        $usuarioExiste = DB::table("usuarios")->where("id", $id)->exists();
        $resultado = "";
        if (!$usuarioExiste) {
            $titulo = "ERROR: EL USUARIO NO EXISTE";
            $resultado = "Por favor, introduzca un usuario que exista en la base de datos.";
            $codigo = 1;
        } else if (strlen($nombre) <= 0 || strlen($nombre) > 45) {
            $titulo = "ERROR: NOMBRE DE USUARIO NO VALIDO";
            $resultado = "Por favor, introduzca un nombre de usuario cuyos caracteres estén en el rango indicado: [1 - 45].";
            $codigo = 2;
        } else if ($permisos != 10 && $permisos != 20 && $permisos != 30 && $permisos != 40 && $permisos != 50) {
            $titulo = "ERROR: PERMISOS INCORRECTOS";
            $resultado = "Por favor, introduzca los permisos adecuados.";
            $codigo = 4;
        } else {
            $resultado = DB::update(
                'update usuarios
                  set nombre = :nombre, password = :password, permisos = :permisos, actualizado = :actualizado
                  where id = :id',
                ['nombre' => $nombre, 'password' => $contrasena, 'permisos' => $permisos, 'actualizado' => $actualizado, 'id' => $id]
            );

            $titulo = "ÉXITO";
            $resultado = "Usuario editado exitosamente.";
            $codigo = 0;
        }
        $datoseditar = DB::table("usuarios")->select("*")->where("id", $id)->first();

        return redirect()->route("editar-usuario", [
            'winterhorn' => session("token"),
            'id_usuario' => $datoseditar->id,
            'nombre' => $datoseditar->nombre,
            'password' => $datoseditar->password,
            'permisos' => $datoseditar->permisos,
            'creado' => $datoseditar->creado,
            'actualizado' => $datoseditar->actualizado,
            "codigo" => $codigo,
            "titulo" => $titulo,
            "resultado" => $resultado,
        ]);
    }

    public function datos_editar(Request $request)
    {
        $datos = DB::table("usuarios")->select("*")->where("id", $request->get("id_usuario"))->first();

        return redirect()->route("editar-usuario", [
            'winterhorn' => session("token"),
            'id_usuario' => $datos->id,
            'nombre' => $datos->nombre,
            'password' => $datos->password,
            'permisos' => $datos->permisos,
            'creado' => $datos->creado,
            'actualizado' => $datos->actualizado,
        ]);
    }

    public function get_usuario(Request $request)
    {
        $pagina = $request->input('pagina', 1);
        $ordenarPor = $request->input('ordenarPor', 'nombre');
        $direccion = $request->input('direccion', 'asc');
        $registrosPorPagina = 15; //Cambiar a 15 en PRODUCCION

        $salto = ($pagina - 1) * $registrosPorPagina;
        $usuarios = DB::table("usuarios")->orderBy($ordenarPor, $direccion)->skip($salto)->take($registrosPorPagina)->get();

        if (request()->has("buscar")) {
            $buscar = request()->input("buscar");

            $usuarios = DB::table("usuarios")->orderBy($ordenarPor, $direccion)->skip($salto)->take($registrosPorPagina)
                ->where("nombre", "like", "%" . $buscar . "%")
                ->orWhere("permisos", "like", "%" . $buscar . "%")
                ->get();
        }



        $totalUsuarios = DB::table("usuarios")->count();
        $totalPaginas = ceil($totalUsuarios / $registrosPorPagina);

        return view("listado-usuario", [
            'winterhorn' => session("token"),
            "usuarios" => $usuarios,
            "pagina" => $pagina,
            "registrosPorPagina" => $registrosPorPagina,
            "totalPaginas" => $totalPaginas,
            "ordenarPor" => $ordenarPor,
            "direccion" => $direccion
        ]);
    }



    public function borrar(Request $request)
    {
        $resultado = "";
        $idUsuario = $request->input("id_usuario");


        $usuarioExiste = DB::table("usuarios")->where("id", $idUsuario)->exists();

        if (!$usuarioExiste) {
            $titulo = "ERROR: EL USUARIO NO EXISTE";
            $resultado = "El usuario no existe en la base de datos o ya ha sido borrado.";
            $codigo = 1;
        } else {
            $titulo = "ÉXITO";
            $resultado = DB::table("usuarios")->where("id", $idUsuario)->delete();
            $codigo = 0;
            if ($resultado) {
                $resultado = "Usuario borrado existosamente.";
            }
        }

        return redirect()->route("get-usuario", [
            'winterhorn' => session("token"),
            "resultado" => $resultado,
            "codigo" => $codigo,
            "titulo" => $titulo,
            "borrado" => true
        ]);

    }


}
