<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
// Importamos para poder usar la clase DB y Hash (esta para hacerlo bien luego)
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use \DateTimeZone;

class BuscadorController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function buscador(Request $request)
    {
        $buscador = new BuscadorController();
        switch ($request->input("dondeBuscar")) {
            case "/get-incidencia":
            case "/buscar-incidencia":
                return redirect()->route("buscar-incidencia", [
                    'winterhorn' => session("token"),
                    'buscar' => $request->input('buscar'),
                    'totalPaginas' => $request->input('totalPaginas'),
                    'registrosPorPagina' => $request->input('registrosPorPagina'),
                    'pagina' => $request->input('pagina')
                ]);
            case "/get-usuario":
            case "/buscar-usuario":
                return redirect()->route("buscar-usuario", [
                    'winterhorn' => session("token"),
                    'buscar' => $request->input('buscar'),
                    'totalPaginas' => $request->input('totalPaginas'),
                    'registrosPorPagina' => $request->input('registrosPorPagina'),
                    'pagina' => $request->input('pagina')
                ]);
            case "/get-crimen":
            case "/buscar-crimen":
                return redirect()->route("buscar-crimen", [
                    'winterhorn' => session("token"),
                    'buscar' => $request->input('buscar'),
                    'totalPaginas' => $request->input('totalPaginas'),
                    'registrosPorPagina' => $request->input('registrosPorPagina'),
                    'pagina' => $request->input('pagina')
                ]);
            default:
                return redirect()->back();
        }

    }


    public function buscar_incidencia(Request $request)
    {

        $buscar = $request->input('buscar');
        $pagina = $request->input('pagina', 1);
        $registrosPorPagina = 3; //Cambiar a 15 en PRODUCCION

        $salto = ($pagina - 1) * $registrosPorPagina;
        $incidencias = DB::table("incidencias")->skip($salto)->take($registrosPorPagina)
        ->where("id", "like", "%" . $buscar . "%")
        ->orWhere("id_crimen", "like", "%" . $buscar . "%")
        ->orWhere("id_usuario", "like", "%" . $buscar . "%")
        ->get();

        $totalIncidencias = DB::table("incidencias")
        ->where("id", "like", "%" . $buscar . "%")
        ->orWhere("id_crimen", "like", "%" . $buscar . "%")
        ->orWhere("id_usuario", "like", "%" . $buscar . "%")
        ->count();

        $totalPaginas = ceil($totalIncidencias / $registrosPorPagina);

        return view("listado-incidencia", [
            'winterhorn' => session("token"),
            "incidencias" => $incidencias,
            "pagina" => $pagina,
            "registrosPorPagina" => $registrosPorPagina,
            "totalPaginas" => $totalPaginas,
            "buscar" => $buscar
        ]);
    }






    // APARTADO BUSCADOR CRIMENES
    public static function buscar_crimen(Request $request)
    {
        $buscar = $request->input('buscar');
        $pagina = $request->input('pagina', 1);
        $registrosPorPagina = 3; //Cambiar a 15 en PRODUCCION

        $salto = ($pagina - 1) * $registrosPorPagina;
        $crimenes = DB::table("crimenes")->skip($salto)->take($registrosPorPagina)
            ->where("titulo", "like", "%" . $buscar . "%")
            ->orWhere("descripcion", "like", "%" . $buscar . "%")
            ->orWhere("tipologia", "like", "%" . $buscar . "%")
            ->orWhere("modus_operandi", "like", "%" . $buscar . "%")
            ->orWhere("zona", "like", "%" . $buscar . "%")
            // ->groupBy("id")
            ->get();

        $totalCrimenes = DB::table("crimenes")
        ->where("titulo", "like", "%" . $buscar . "%")
        ->orWhere("descripcion", "like", "%" . $buscar . "%")
        ->orWhere("tipologia", "like", "%" . $buscar . "%")
        ->orWhere("modus_operandi", "like", "%" . $buscar . "%")
        ->orWhere("zona", "like", "%" . $buscar . "%")
        ->count();

        $totalPaginas = ceil($totalCrimenes / $registrosPorPagina);

        return view("listado-crimen", [
            'winterhorn' => session("token"),
            "crimenes" => $crimenes,
            "pagina" => $pagina,
            "registrosPorPagina" => $registrosPorPagina,
            "totalPaginas" => $totalPaginas,
            "buscar" => $buscar
        ]);
    }






    // APARTADO BUSCADOR USUARIOS

    public static function buscar_usuario(Request $request)
    {

        $buscar = $request->input('buscar');
        $pagina = $request->input('pagina', 1);
        $registrosPorPagina = 3; //Cambiar a 15 en PRODUCCION

        $salto = ($pagina - 1) * $registrosPorPagina;
        $usuarios = DB::table("usuarios")->skip($salto)->take($registrosPorPagina)
        ->where("nombre", "like", "%" . $buscar . "%")
        ->orWhere("permisos", "like", "%" . $buscar . "%")
            ->get();

        $totalUsuarios = DB::table("usuarios")
        ->where("nombre", "like", "%" . $buscar . "%")
        ->orWhere("permisos", "like", "%" . $buscar . "%")
        ->count();

        $totalPaginas = ceil($totalUsuarios / $registrosPorPagina);

        return view("listado-usuario", [
            'winterhorn' => session("token"),
            "usuarios" => $usuarios,
            "pagina" => $pagina,
            "registrosPorPagina" => $registrosPorPagina,
            "totalPaginas" => $totalPaginas,
            "buscar" => $buscar
        ]);
    }
}
