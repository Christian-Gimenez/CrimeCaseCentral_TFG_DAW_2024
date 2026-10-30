<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use \DateTimeZone;

class CrimenController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function crear(Request $request)
    {
        $tituloCrimen = trim($request->input("tituloCrimen"));
        $descripcion = trim($request->input("descripcion"));
        $fechaDenuncia = $request->input("fechaDenuncia");
        $tipologia = trim($request->input("tipologia"));
        $modusOperandi = trim($request->input("modusOperandi"));
        $zona = trim($request->input("zona"));

        $idUsuario = session("usuario");

        $rutaImagenPortada = "";
        $rutaArchivadorImagenes = "";

        $resultado = "";
        $crimenExiste = DB::table("crimenes")->where("titulo", $tituloCrimen)->exists();
        $type = DB::selectOne("SHOW COLUMNS FROM crimenes WHERE Field = 'tipologia'")->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $tipologiasPermitidas = explode("','", $matches[1]);

        if ($crimenExiste) {
            $titulo = "ERROR: EL CRIMEN YA EXISTE";
            $resultado = "Por favor, introduzca un crimen que no exista en la base de datos.";
            $codigo = 1;
        } else if (strlen($tituloCrimen) <= 0 || strlen($tituloCrimen) > 100) {
            $titulo = "ERROR: TITULO DE CRIMEN NO VALIDO";
            $resultado = "Por favor, introduzca un titulo de crimen cuyos caracteres estén en el rango indicado: [1 - 100].";
            $codigo = 2;
        } else if (strlen($descripcion) <= 0 || strlen($descripcion) > 4000) {
            $titulo = "ERROR: DESCRIPCIÓN NO VALIDA";
            $resultado = "Por favor, introduzca una descripción del crimen cuyos caracteres estén en el rango indicado: [1 - 4000].";
            $codigo = 3;
        } else if (!$fechaDenuncia) {
            $titulo = "ERROR: FECHA DENUNCIA NO VALIDA";
            $resultado = "Por favor, introduzca una fecha del crímen válida con el formato indicado [DD-MM-AAAA].";
            $codigo = 4;
        } else if (!in_array($tipologia, $tipologiasPermitidas)) {
            $titulo = "ERROR: TIPOLOGÍA DE CRIMEN NO VALIDA";
            $resultado = "Por favor, introduzca una tipología del crimen válida.";
            $codigo = 7;
        } else if (strlen($modusOperandi) <= 0 || strlen($modusOperandi) > 4000) {
            $titulo = "ERROR: TIPOLOGÍA DE CRIMEN NO VALIDA";
            $resultado = "Por favor, introduzca un modus operandio cuyos caracteres estén en el rango indicado: [1 - 100].";
            $codigo = 8;
        } else if (strlen($zona) <= 0 || strlen($zona) > 1000) {
            $titulo = "ERROR: ZONA DE CRIMEN NO VALIDA";
            $resultado = "Por favor, introduzca una zona de crimen válida.";
            $codigo = 9;
        } else {

            $resultado = DB::table("crimenes")->insert([
                "titulo" => $tituloCrimen,
                "descripcion" => $descripcion,
                "fecha_denuncia" => $fechaDenuncia,
                "imagen_portada" => $rutaImagenPortada,
                "archivador_imagenes" => $rutaArchivadorImagenes,
                "tipologia" => $tipologia,
                "modus_operandi" => $modusOperandi,
                "zona" => $zona,
                "id_usuario" => $idUsuario
            ]);
            $titulo = "CRIMEN AGREGADO EXITOSAMENTE";
            $resultado = "El crimen con título '$tituloCrimen' ha sido creado correctamente en la base de datos.";
            $codigo = 0;
        }
        $idCrimen = DB::table("crimenes")->max("id");

        if ($request->hasFile('imagenPortada')) {

            $rutaImagenPortada = $request->file('imagenPortada')->store('imagenesPortada/' . $idCrimen, 'public');
            DB::update(
                'update crimenes
                  set imagen_portada = :imagen_portada where id = :id'
                ,
                ["imagen_portada" => $rutaImagenPortada, "id" => $idCrimen]
            );
        }

        if ($request->hasFile('archivadorImagenes')) {

            $imagenes = $request->file('archivadorImagenes');
            $rutaArchivadorImagenes = "archivadorImagenes/" . Storage::disk('public')->makeDirectory('archivadorImagenes/' . $idCrimen);
            DB::update(
                'update crimenes
                  set archivador_imagenes = :archivador_imagenes where id = :id'
                ,
                ["archivador_imagenes" => $rutaArchivadorImagenes, "id" => $idCrimen]
            );

            foreach ($imagenes as $imagen) {
                $imagen->store('archivadorImagenes/' . $idCrimen, 'public');
            }
        }

        return response()->json([
            'redirect_url' => route("crear-crimen", [
                'winterhorn' => session("token"),
                "resultado" => $resultado,
                "codigo" => $codigo,
                "titulo" => $titulo
            ])
        ]);
    }

    public function editar(Request $request)
    {
        $id = trim($request->input("id_crimen"));
        $tituloCrimen = trim($request->input("tituloCrimen"));
        $descripcion = trim($request->input("descripcion"));
        $fechaDenuncia = $request->input("fechaDenuncia");
        $tipologia = trim($request->input("tipologia"));
        $modusOperandi = trim($request->input("modusOperandi"));
        $zona = trim($request->input("zona"));
        $idUsuario = session("usuario");
        $rutaImagenPortada = DB::table("crimenes")->where('id', $id)->value('imagen_portada');

        $crimenExiste = DB::table("crimenes")->where("id", $id)->exists();
        $crimenExistente = DB::table("crimenes")->where("titulo", $tituloCrimen)->first();
        $resultado = "";
        $type = DB::selectOne("SHOW COLUMNS FROM crimenes WHERE Field = 'tipologia'")->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $tipologiasPermitidas = explode("','", $matches[1]);

        if (!$crimenExiste) {
            $titulo = "ERROR: EL CRIMEN NO EXISTE";
            $resultado = "Por favor, introduzca un crimen que exista en la base de datos.";
            $codigo = 1;
        } else if (strlen($tituloCrimen) <= 0 || strlen($tituloCrimen) > 100) {
            $titulo = "ERROR: TITULO DE CRIMEN NO VALIDO";
            $resultado = "Por favor, introduzca un titulo de crimen cuyos caracteres estén en el rango indicado: [1 - 100].";
            $codigo = 2;
        } else if (strlen($descripcion) <= 0 || strlen($descripcion) > 4000) {
            $titulo = "ERROR: DESCRIPCIÓN NO VALIDA";
            $resultado = "Por favor, introduzca una descripción del crimen cuyos caracteres estén en el rango indicado: [1 - 4000].";
            $codigo = 3;
        } else if (($crimenExistente && $crimenExistente->id != $id)) {
            $titulo = "ERROR: TITULO DE CRIMEN NO VALIDO";
            $resultado = "Por favor, introduzca un titulo de crimen que no exista";
            $codigo = 2;
        } else if (!$fechaDenuncia) {
            $titulo = "ERROR: FECHA DENUNCIA NO VALIDA";
            $resultado = "Por favor, introduzca una fecha del crímen válida con el formato indicado [DD-MM-AAAA].";
            $codigo = 4;
        } else if (!in_array($tipologia, $tipologiasPermitidas)) {
            $titulo = "ERROR: TIPOLOGÍA DE CRIMEN NO VALIDA";
            $resultado = "Por favor, introduzca una tipología del crimen válida.";
            $codigo = 7;
        } else if (strlen($modusOperandi) <= 0 || strlen($modusOperandi) > 4000) {
            $titulo = "ERROR: TIPOLOGÍA DE CRIMEN NO VALIDA";
            $resultado = "Por favor, introduzca un modus operandio cuyos caracteres estén en el rango indicado: [1 - 100].";
            $codigo = 8;
        } else if (strlen($zona) <= 0 || strlen($zona) > 1000) {
            $titulo = "ERROR: ZONA DE CRIMEN NO VALIDA";
            $resultado = "Por favor, introduzca una zona de crimen válida.";
            $codigo = 9;
        } else {

            if ($request->has('imagenesEliminadas')) {
                $imagenesEliminadas = $request->input('imagenesEliminadas');

                foreach ($imagenesEliminadas as $nombreImagen) {
                    $filePath = 'archivadorImagenes/' . $id . '/' . $nombreImagen;
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }

            if (count(Storage::disk('public')->files('archivadorImagenes/' . $id)) < 1) {
                Storage::disk('public')->deleteDirectory('archivadorImagenes/' . $id);
                Storage::disk('public')->makeDirectory('archivadorImagenes/' . $id);

            }

            if ($request->hasFile('imagenPortada')) {
                $oldPortada = DB::table("crimenes")->where('id', $id)->value('imagen_portada');
                if ($oldPortada) {
                    Storage::disk('public')->delete($oldPortada);
                }

                $rutaImagenPortada = $request->file('imagenPortada')->store('imagenesPortada/' . $id, 'public');
            }

            if ($request->hasFile('archivadorImagenes')) {

                $imagenes = $request->file('archivadorImagenes');

                foreach ($imagenes as $imagen) {
                    if ($imagen->isValid() && strpos($imagen->getMimeType(), 'image/') === 0) {

                        $extension = $imagen->getClientOriginalExtension();

                        if (in_array($extension, ['jpeg', 'png', 'jpg', 'gif'])) {
                            try {
                                $imagen->store('archivadorImagenes/' . $id, 'public');
                            } catch (\Exception $e) {
                                \Log::error('Error al almacenar la imagen: ' . $e->getMessage());
                            }

                        }
                    }
                }

                $archivos = Storage::disk('public')->files('archivadorImagenes/' . $id);
                foreach ($archivos as $archivo) {
                    if (pathinfo($archivo, PATHINFO_EXTENSION) == 'txt') {
                        Storage::disk('public')->delete($archivo);
                    }
                }

                if (count(Storage::disk('public')->files('archivadorImagenes/' . $id)) < 1) {
                    Storage::disk('public')->deleteDirectory('archivadorImagenes/' . $id);

                }

            }


            $resultado = DB::update(
                'update crimenes
                 set titulo = :titulo, descripcion = :descripcion, fecha_denuncia = :fecha_denuncia, imagen_portada = :imagen_portada, tipologia = :tipologia, modus_operandi = :modus_operandi, zona = :zona where id = :id',
                [
                    'titulo' => $tituloCrimen,
                    'descripcion' => $descripcion,
                    'fecha_denuncia' => $fechaDenuncia,
                    'imagen_portada' => $rutaImagenPortada,
                    'tipologia' => $tipologia,
                    'modus_operandi' => $modusOperandi,
                    'zona' => $zona,
                    'id' => $id
                ]
            );

            $titulo = "ÉXITO";
            $resultado = "Crimen editado exitosamente.";
            $codigo = 0;
        }
        $datoseditar = DB::table("crimenes")->select("*")->where("id", $id)->first();

        return response()->json([
            'redirect_url' => route("editar-crimen", [
                'winterhorn' => session("token"),
                'id_crimen' => $datoseditar->id,
                'tituloCrimen' => $datoseditar->titulo,
                'descripcion' => $datoseditar->descripcion,
                'fecha_denuncia' => $datoseditar->fecha_denuncia,
                'imagen_portada' => $datoseditar->imagen_portada,
                'archivador_imagenes' => $datoseditar->archivador_imagenes,
                'tipologia' => $datoseditar->tipologia,
                'tipologias' => $tipologiasPermitidas,
                'modus_operandi' => $datoseditar->modus_operandi,
                'zona' => $datoseditar->zona,
                'redactor' => $datoseditar->id_usuario,
                "codigo" => $codigo,
                "titulo" => $titulo,
                "resultado" => $resultado,
            ])
        ]);
    }

    public function get_tipologias()
    {
        $type = DB::selectOne("SHOW COLUMNS FROM crimenes WHERE Field = 'tipologia'")->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $tipologias = explode("','", $matches[1]);

        return view('crear-crimen', [
            'winterhorn' => session("token"),
            'tipologias' => $tipologias
        ]);
    }

    public function datos_editar(Request $request)
    {
        $datos = DB::table("crimenes")->select("*")->where("id", $request->get("id_crimen"))->first();
        $type = DB::selectOne("SHOW COLUMNS FROM crimenes WHERE Field = 'tipologia'")->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $tipologiasPermitidas = explode("','", $matches[1]);

        return redirect()->route("editar-crimen", [
            'winterhorn' => session("token"),
            'id_crimen' => $datos->id,
            'tituloCrimen' => $datos->titulo,
            'descripcion' => $datos->descripcion,
            'fecha_denuncia' => $datos->fecha_denuncia,
            'imagen_portada' => $datos->imagen_portada,
            'archivador_imagenes' => $datos->archivador_imagenes,
            'tipologia' => $datos->tipologia,
            'tipologias' => $tipologiasPermitidas,
            'modus_operandi' => $datos->modus_operandi,
            'zona' => $datos->zona,
            'redactor' => $datos->id_usuario,
        ]);
    }


    public function datos_leer(Request $request)
    {
        $datos = DB::table("crimenes")->select("*")->where("id", $request->get("id_crimen"))->first();
        return redirect()->route("leer-crimen", [
            'winterhorn' => session("token"),
            'id_crimen' => $datos->id,
            'titulo' => $datos->titulo,
            'descripcion' => $datos->descripcion,
            'fecha_denuncia' => $datos->fecha_denuncia,
            'imagen_portada' => $datos->imagen_portada,
            'archivador_imagenes' => $datos->archivador_imagenes,
            'tipologia' => $datos->tipologia,
            'modus_operandi' => $datos->modus_operandi,
            'zona' => $datos->zona,
            'redactor' => $datos->id_usuario,
        ]);
    }

    public function get_crimen(Request $request)
    {

        $pagina = $request->input('pagina', 1);
        $registrosPorPagina = 3; //Cambiar a 15 en PRODUCCION

        $salto = ($pagina - 1) * $registrosPorPagina;
        $crimenes = DB::table("crimenes")->skip($salto)->take($registrosPorPagina)->get();
        $totalCrimenes = DB::table("crimenes")->count();
        $totalPaginas = ceil($totalCrimenes / $registrosPorPagina);

        return view("listado-crimen", [
            'winterhorn' => session("token"),
            "crimenes" => $crimenes,
            "pagina" => $pagina,
            "registrosPorPagina" => $registrosPorPagina,
            "totalPaginas" => $totalPaginas,
        ]);


    }

    public function borrar(Request $request)
    {
        $resultado = "";
        $id_crimen = $request->input("id_crimen");


        $crimenExiste = DB::table("crimenes")->where("id", $id_crimen)->exists();

        if (!$crimenExiste) {
            $titulo = "ERROR: EL CRIMEN NO EXISTE";
            $resultado = "El crimen no existe en la base de datos o ya ha sido borrado.";
            $codigo = 1;
        } else {
            $titulo = "ÉXITO";
            Storage::deleteDirectory('public/archivadorImagenes/' . $id_crimen);
            Storage::deleteDirectory('public/imagenesPortada/' . $id_crimen);
            DB::table("incidencias")->where("id_crimen", $id_crimen)->delete();
            $resultado = DB::table("crimenes")->where("id", $id_crimen)->delete();
            $codigo = 0;
            if ($resultado) {
                $resultado = "Crimen borrado existosamente.";
            }
        }

        return redirect()->route("get-crimen", [
            'winterhorn' => session("token"),
            "resultado" => $resultado,
            "codigo" => $codigo,
            "titulo" => $titulo,
            "ventana" => true
        ]);

    }


}
