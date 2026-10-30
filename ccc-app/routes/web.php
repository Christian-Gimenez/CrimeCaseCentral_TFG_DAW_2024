<?php

use App\Http\Middleware\ValidarPermisos;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CrimenController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\BuscadorController;
use App\Http\Middleware\EnsureTokenIsValid;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middlewaregit  group. Make something great!
|
*/

//Ruta principal (login)
Route::get('/', function () {
    return view('login');
})->name("login");

//Listado de crimenes, es lo 1º que verá el usuario
Route::get('/listado-crimen', function () {
    return view('listado-crimen');
})->name("listado-crimen")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Listado de usuarios
Route::get('/listado-usuario', function () {
    return view('listado-usuario');
})->name("listado-usuario")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Listado de incidencias
Route::get('/listado-incidencia', function () {
    return view('listado-incidencia');
})->name("listado-incidencia")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Lectura de un crimen
Route::get("/leer-crimen", function() {
    return view("leer-crimen");
})->name("leer-crimen")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Creación de un crimen
// Route::get("/crear-crimen", function() {
//     return view("crear-crimen");
// })->name("crear-crimen")->middleware(EnsureTokenIsValid::class);

Route::get("/crear-crimen", [CrimenController::class, "get_tipologias"])->name("crear-crimen")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Edición de un crimen
Route::get("/editar-crimen", function() {
    return view("editar-crimen");
})->name("editar-crimen")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Creación de un usuario
Route::get("/crear-usuario", function() {
    return view("crear-usuario");
})->name("crear-usuario")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Edición de un usuario
Route::get("/editar-usuario", function() {
    return view("editar-usuario");
})->name("editar-usuario")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Lleva al controlador que carga los datos del usuario seleccionado antes de editar
Route::get('/editar-usuario-cargadb', [UsuarioController::class, "datos_editar"])->name("editar-usuario-cargadb")->middleware(ValidarPermisos::class);

//Lleva al controlador que valida el login
Route::post('/validar-login', [UsuarioController::class, "entrar"])->name("validar-login");

//Lleva al controlador que crea el usuario
Route::post('/crear-usuario-db', [UsuarioController::class, "crear"])->name("crear-usuario-db")->middleware(ValidarPermisos::class);

//Lleva al controlador que edita el usuario
Route::post('/editar-usuario-db', [UsuarioController::class, "editar"])->name("editar-usuario-db")->middleware(ValidarPermisos::class);

//Lleva al controlador que borra el usuario
Route::get('/borrar-usuario', [UsuarioController::class, "borrar"])->name("borrar-usuario")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Trae la lista de usuarios
Route::get('/get-usuario', [UsuarioController::class, "get_usuario"])->name("get-usuario")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Trae la lista de usuarios
Route::get('/get-incidencia', [IncidenciaController::class, "get_incidencia"])->name("get-incidencia")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Trae la lista de crimenes
Route::get('/get-crimen', [CrimenController::class, "get_crimen"])->name("get-crimen")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Lleva al controlador que borra el usuario
Route::get('/borrar-incidencia', [IncidenciaController::class, "borrar"])->name("borrar-incidencia")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Lleva al controlador que crea la incidencia
Route::get('/crear-incidencia-db', [IncidenciaController::class, "crear"])->name("crear-incidencia-db")->middleware(ValidarPermisos::class);

//Lleva al controlador que crea el usuario
Route::post('/crear-crimen-db', [CrimenController::class, "crear"])->name("crear-crimen-db")->middleware(ValidarPermisos::class);

//Lleva al controlador que extraer los datos
Route::get('/editar-crimen-cargadb', [CrimenController::class, "datos_editar"])->name("editar-crimen-cargadb")->middleware(ValidarPermisos::class);

//Lleva al controlador que edita el crimen
Route::post('/editar-crimen-db', [CrimenController::class, "editar"])->name("editar-crimen-db")->middleware(ValidarPermisos::class);

//Lleva al controlador que extraer los datos
Route::get('/leer-crimen-db', [CrimenController::class, "datos_leer"])->name("leer-crimen-db")->middleware(ValidarPermisos::class);

//Lleva al controlador que borra el crimen
Route::get('/borrar-crimen', [CrimenController::class, "borrar"])->name("borrar-crimen")->middleware(EnsureTokenIsValid::class)->middleware(ValidarPermisos::class);

//Buscador por POST
Route::post('/buscador', [BuscadorController::class, "buscador"])->name("buscador")->middleware(ValidarPermisos::class);

//Buscador por GET
Route::get('/buscador', [BuscadorController::class, "buscador"])->name("buscador")->middleware(ValidarPermisos::class);

//Buscador de incidencias
Route::get('/buscar-incidencia', [BuscadorController::class, "buscar_incidencia"])->name("buscar-incidencia")->middleware(ValidarPermisos::class);

//Buscador de crimenes
Route::get('/buscar-crimen', [BuscadorController::class, "buscar_crimen"])->name("buscar-crimen")->middleware(ValidarPermisos::class);

//Buscador de usuarios
Route::get('/buscar-usuario', [BuscadorController::class, "buscar_usuario"])->name("buscar-usuario")->middleware(ValidarPermisos::class);
