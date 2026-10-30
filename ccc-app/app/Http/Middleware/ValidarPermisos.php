<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class ValidarPermisos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idUsuario = session("usuario");
        $ruta = parse_url(url()->current(), PHP_URL_PATH);
        //PERMISOS:
        //10: Lectores
        //20: Editores
        //30: Moderadores
        //40: Administradores
        //50: Super Administradores

        $usuario = DB::table('usuarios')->select("permisos")->where("id", $idUsuario)->first();

        switch ($ruta) {
            case '/listado-incidencia':
            case '/listado-usuario':
            case '/get-incidencia':
            case '/get-usuario':
            case '/editar-usuario-cargadb':
            case '/crear-usuario-db':
            case '/crear-usuario':
            case '/editar-usuario-db':
            case '/editar-usuario':
            case '/borrar-usuario':
            case '/borrar-incidencia':
            case '/buscar-incidencia':
            case '/buscar-usuario':
                switch ($usuario->permisos) {
                    case '40':
                    case '50':
                        return $next($request);
                    default:
                        return back();
                }
            case '/crear-incidencia-db':
            case '/crear-incidencia':
                switch ($usuario->permisos) {
                    case '30':
                    case '40':
                    case '50':
                        return $next($request);
                    default:
                        return back();
                }
            case "/crear-crimen":
            case '/crear-crimen-db':
            case '/borrar-crimen':
            case "/editar-crimen":
            case "/editar-crimen-cargadb":
            case '/editar-crimen-db':
                switch ($usuario->permisos) {
                    case "30":
                        if ($ruta == '/borrar-crimen' || $ruta == "/editar-crimen" || $ruta == "/editar-crimen-cargadb" || $ruta == '/editar-crimen-db') {
                            return $next($request);
                        } else {
                            return back();
                        }
                    case '20':
                        if($ruta == '/borrar-crimen' || $ruta == "/editar-crimen" || $ruta == "/editar-crimen-cargadb" || $ruta == '/editar-crimen-db') {
                            $check_id = DB::table('crimenes')->select("id_usuario")->where("id", $request->input("id_crimen"))->first();
                            if ($idUsuario == $check_id->id_usuario) {
                                return $next($request);
                            } else {
                                return back();
                            }
                        }

                        return $next($request);

                    case '40':
                    case '50':
                        return $next($request);
                    default:
                        return back();
                }
            case '/listado-crimen':
            case '/get-crimen':
            case "/leer-crimen":
            case '/leer-crimen-db':
            case '/buscar-crimen':
            case '/buscador':
            case '/validar-login':
                switch ($usuario->permisos) {
                    case '10':
                    case '20':
                    case '30':
                    case '40':
                    case '50':
                        return $next($request);
                    default:
                        return back();
                }
            default:
                return redirect()->route("login");
        }

    }
}
