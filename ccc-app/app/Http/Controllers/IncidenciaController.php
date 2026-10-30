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

class IncidenciaController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function crear(Request $request)
    {
        $id_redactor = $request->input('redactor');
        $id_crimen = $request->input('id_crimen');
        $resultado = "";
        $incidenciaExiste = DB::table("incidencias")->where("id_usuario", $id_redactor)->where("id_crimen", $id_crimen)->exists();

        if (!$incidenciaExiste) {
            DB::table("incidencias")->insert([
                "id_usuario" => $id_redactor,
                "id_crimen" => $id_crimen,
            ]);
            $titulo = "INCIDENCIA CREADA EXITOSAMENTE";
            $resultado = "INCIDENCIA CREADA EXITOSAMENTE";
            $codigo = 0;
        } else {
            $titulo = "ERROR";
            $resultado = "ERROR: INCIDENCIA YA EXISTENTE";
            $codigo = 1;
        }

        return redirect()->route("get-crimen", [
            'winterhorn' => session("token"),
            "resultado" => $resultado,
            "codigo" => $codigo,
            "titulo" => $titulo,
            'ventana' => true
        ]);
    }

    public function get_incidencia(Request $request)
    {
        $pagina = $request->input('pagina', 1);
        $ordenarPor = $request->input('ordenarPor', 'id');
        $direccion = $request->input('direccion', 'asc');
        $registrosPorPagina = 15; //Cambiar a 15 en PRODUCCION

        $salto = ($pagina - 1) * $registrosPorPagina;
        $incidencias = DB::table("incidencias")->orderBy($ordenarPor, $direccion)->skip($salto)->take($registrosPorPagina)->get();


        if (request()->has("buscar")) {
            $buscar = request()->input("buscar");
            $incidencias = DB::table("incidencias")->orderBy($ordenarPor, $direccion)->skip($salto)->take($registrosPorPagina)
            ->where("id", "like", "%" . $buscar . "%")
            ->orWhere("id_crimen", "like", "%" . $buscar . "%")
            ->orWhere("id_usuario", "like", "%" . $buscar . "%")
            ->get();
        }

        $totalIncidencias = DB::table("incidencias")->count();
        $totalPaginas = ceil($totalIncidencias / $registrosPorPagina);

        return view("listado-incidencia", [
            'winterhorn' => session("token"),
            "incidencias" => $incidencias,
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
        $idIncidencia = $request->input("id_incidencia");


        $incidenciaExiste = DB::table("incidencias")->where("id", $idIncidencia)->exists();

        if (!$incidenciaExiste) {
            $titulo = "ERROR: LA INCIDENCIA NO EXISTE";
            $resultado = "La incidencia no existe en la base de datos o ya ha sido borrado.";
            $codigo = 1;
        } else {
            $titulo = "ÉXITO";
            $resultado = DB::table("incidencias")->where("id", $idIncidencia)->delete();
            $codigo = 0;
            if ($resultado) {
                $resultado = "Incidencia borrada existosamente.";
            }
        }

        return redirect()->route("get-incidencia", [
            'winterhorn' => session("token"),
            "resultado" => $resultado,
            "codigo" => $codigo,
            "titulo" => $titulo,
            "borrado" => true
        ]);

    }


}
