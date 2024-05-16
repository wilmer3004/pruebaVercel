<?php

use App\Http\Controllers\AmbienteController;
use App\Http\Controllers\BloqueController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\ComponenteController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\CondicionController;
use App\Http\Controllers\CondicionHoraController;
use App\Http\Controllers\ControllerTrimestreAnio;
use App\Http\Controllers\CoordinacionController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\FestivosController;
use App\Http\Controllers\FichaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HoraLaboralController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\JornadaController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TipoComponenteController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\TipoProgramaController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\HorarioInformationController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\HorarioInformationTeacherController;
use App\Models\HoraLaboral;
use App\Models\Rol;
use App\Models\TipoComponente;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {

    // RUTAS PARA LA PARTE DEL LANDPAGE
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // RUTAS PARA LA GESTIÓN DE PERSONAS
    Route::controller(PersonaController::class)->group(function () {
        Route::get('personas', 'index')->name('personas.index');
        Route::get('personas/listar', 'listar')->name('personas.listar');
        Route::get('personas/crear', 'create')->name('personas.create');
        Route::post('personas/store', 'store')->name('personas.store');
        Route::get('personas/{id}/show', 'show')->name('personas.show');
        Route::get('personas/card', 'card')->name('personas.card');
        Route::get('personas/{id}/consulta', 'consulta')->name('personas.consulta');
        Route::get('personas/{id}/edit', 'edit')->name('personas.edit');
        Route::post('personas/update', 'update')->name('personas.update');
        Route::get('personas/{id}/disable', 'disable')->name('personas.disable');
        Route::get('personas/{id}/enable', 'enable')->name('personas.enable');
    });


    // RUTAS PARA LA GESTIÓN DE INSTRUCTORES
    Route::controller(InstructorController::class)->group(function () {
        Route::middleware(['role:superadmin,administrador,programador'])->group(function () {
            Route::get('instructores', 'index')->name('instructores.index');
            Route::get('instructores/listar', 'listar')->name('instructores.listar');
        });
        Route::middleware(['role:superadmin, administrador'])->group(function () {
            Route::get('instructores/crear', 'create')->name('instructores.create');
            Route::post('instructores/store', 'store')->name('instructores.store');
            Route::post('instructores/details', 'storeDetails')->name('instructores.details');
            Route::get('instructores/card', 'card')->name('instructores.card');
            Route::get('instructores/{id}/show', 'show')->name('instructores.show');
            Route::get('instructores/{id}/consulta', 'consulta')->name('instructores.consulta');
            Route::get('instructores/{id}/edit', 'edit')->name('instructores.edit');
            Route::post('instructores/update', 'update')->name('instructores.update');
            Route::post('instructores/update/details', 'updateDetails')->name('instructores.updateDetails');
            Route::get('instructores/{id}/disable', 'disable')->name('instructores.disable');
            Route::get('instructores/{id}/enable', 'enable')->name('instructores.enable');
            Route::get('instructores/{id}/delete', 'delete')->name('instructores.delete');
        });
    });

    // RUTAS PARA LA GESTIÓN DE PROGRAMAS
    Route::controller(ProgramaController::class)->group(function () {
        Route::middleware(['role:superadmin,administrador,programador'])->group(function () {
            Route::get('programas', 'index')->name('programas.index');
            Route::get('programas/listar', 'listar')->name('programas.listar');
        });
        Route::middleware(['role:superadmin,administrador'])->group(function () {
            Route::post('programas/create', 'store')->name('programas.store');
            Route::get('programas/{id}/edit','edit')->name('programas.edit');
            Route::post('programas/update', 'update')->name('programas.update');
            Route::get('programas/{id}/changeState','destroy')->name('programas.destroy');
            Route::get('programas/{id}/delete','delete')->name('programas.delete');
        });
    });

    // RUTAS PARA LA GESTIÓN DE COMPONENTES
    Route::controller(ComponenteController::class)->group(function () {
        Route::middleware(['role:superadmin,administrador,programador'])->group(function () {
            Route::get('componentes', 'index')->name('componentes.index');
            Route::get('componentes/listar', 'listar')->name('componentes.listar');
        });
        Route::middleware(['role:superadmin,administrador'])->group(function () {
            Route::post(
                'componentes/create',
                'store'
            )->name('componentes.store');
            Route::get('componentes/{id}/edit', 'edit')->name('componentes.edit');
            Route::post(
                'componentes/update',
                'update'
            )->name('componentes.update');
            Route::get('componentes/{id}/changeState','destroy')->name('componentes.destroy');
            Route::get('componentes/{id}/delete','delete')->name('componentes.delete');

        });
    });

    //RUTAS PARA LA GESTIÓN SEDES
    Route::controller(SedeController::class)->group(function () {
        Route::middleware(['role:superadmin,administrador,programador'])->group(function () {
            Route::get('sedes', 'index')->name('sedes.index');
            Route::get('sedes/listar', 'listar')->name('sedes.listar');
        });
        Route::middleware(['role:superadmin,administrador'])->group(function () {
            Route::post('sedes/create', 'store')->name('sedes.store');
            Route::get('sedes/{id}/edit', 'edit')->name('sedes.edit');
            Route::post('sedes/update', 'update')->name('sedes.update');
            Route::get('sedes/{id}/delete', 'destroy')->name('sedes.destroy');
            Route::get('sedes/{id}/state', 'state')->name('sedes.state');
        });
    });

    //RUTAS PARA LA GESTIÓN AMBIENTES
    Route::controller(AmbienteController::class)->group(function () {
        Route::middleware(['role:superadmin,administrador,programador'])->group(function () {
            Route::get('ambientes', 'index')->name('ambientes.index');
            Route::get('ambientes/listar', 'listar')->name('ambientes.listar');
        });
        Route::middleware(['role:superadmin,administrador'])->group(function () {
            Route::post('ambientes/create', 'store')->name('ambientes.store');
            Route::get(
                'ambientes/{id}/edit',
                'edit'
            )->name('ambientes.edit');
            Route::post(
                'ambientes/update',
                'update'
            )->name('ambientes.update');
            Route::get('ambientes/{id}/delete', 'destroy')->name('ambientes.destroy');
        });
    });

    // RUTAS PARA LA GESTIÓN DE FICHAS
    Route::controller(FichaController::class)->group(function () {
        Route::middleware(['role:superadmin,administrador,programador'])
            ->group(function () {
                Route::get('fichas', 'index')->name('fichas.index');
                Route::get('fichas/listar', 'listar')->name('fichas.listar');
            });
        Route::middleware(['role:superadmin, administrador'])->group(function () {
            Route::post('fichas/create', 'store')->name('fichas.store');
            Route::get('fichas/{id}/edit', 'edit')->name('fichas.edit');
            Route::post('fichas/update', 'update')->name('fichas.update');
            Route::post('fichas/listarEvents', 'listarEvents')->name('fichas.listarEvents');
            Route::post('fichas/joinTiles', 'joinTiles')->name('fichas.joinTiles');
            Route::get('fichas/{id}/changeState','destroy')->name('fichas.destroy');
            Route::get('fichas/{id}/delete','delete')->name('fichas.delete');
        });
    });

    // RUTAS PARA LA GESTIÓN DEL HORARIO
    Route::controller(EventoController::class)->group(function () {
        Route::middleware(['role:superadmin,administrador,programador'])->group(function () {
            Route::get('calendario', 'index')->name('horarios.index');
            Route::get('horario/create', 'create')->name('horarios.create');
            Route::post('horario/fichas', 'findStudySheets')->name('horarios.fichas');
            Route::post('horario/baseOptions', 'baseOptions')->name('horarios.baseOptions');
            Route::post('horario/ambiente', 'ambiente')->name('horarios.ambiente');
            Route::post('horario/instructor', 'instructor')->name('horarios.instructor');
            Route::post('horario/opciones', 'findOptions')->name('horarios.opciones');
            Route::post('horario/store', 'store')->name('horarios.store');
            Route::post('horario/teacher', 'storeTeacher')->name('horarios.teacher');
            Route::get('horario/show', 'show')->name('horarios.show');
            Route::post('horario/filter', 'filterCronograma')->name('horarios.filter');

        });
    });

    // RUTAS PARA EL CONTROL DE EVENTOS
    Route::controller(HorarioInformationController::class)->group(function(){
        Route::middleware(['role:superadmin,administrador,programador'])->group(function () {
            Route::get('horarioInformation/datatable','datatable')->name('horarioInformation.datatable');
            Route::get('horarioInformation/listar', 'listar')->name('horarioInformation.listar');
            Route::delete('horarioInformation/destroy', 'destroy' )->name('horarioInformation.destroy');
            Route::post('horarioInformation/showEventoFicha', 'showEventoFicha' )->name('horarioInformation.showEventoFicha');
            Route::post('horarioInformation/listarEventsTrimester', 'listarEventsTrimester')->name('horarioInformation.listarEventsTrimester');
            Route::post('horarioInformation/listarEventsTrimestre', 'listarEvent')->name('horarioInformation.listarEventsTrimestre');
            Route::get('horarioInformation/listarTrimestre', 'listarTrimestre')->name('horarioInformation.listarTrimestre');
        });
    });

    //RUTAS PARA EL CONTROL DE EVENTOS DE ISNTRUCTORES
    Route::controller(HorarioInformationTeacherController::class)->group(function(){
        Route::middleware(['role:superadmin,administrador,programador'])->group(function () {
            Route::get('horarioInformationTeacher/index','datatableTeacherHR')->name('horarioInformationTeacher.index');
            Route::get('horarioInformationTeacher/listar','listar')->name('horarioInformationTeacher.listar');
            Route::post('horarioInformationTeacher/showEventsTeacher','showEventToTeacher')->name('horarioInformationTeacher.showEventsTeacher');
            Route::post('horarioInformationTeacher/totalHoursTeacherQuarter','totalHoursTeacherQuarter')->name('horarioInformationTeacher.totalHoursTeacherQuarter');
            Route::post('horarioInformationTeacher/busyAndAvailableHoursTeacher','busyAndAvailableHoursTeacher')->name('horarioInformationTeacher.busyAndAvailableHoursTeacher');
        });
    });

    // RUTAS PARA HERRAMIENTAS
    Route::middleware(['role:superadmin'])->group(function () {

        // RUTAS PARA LA GESTIÓN DE BLOQUES
        Route::controller(BloqueController::class)->group(function () {
            Route::get('bloques', 'index')->name('bloques.index');
            Route::get('bloques/listar', 'listar')->name('bloques.listar');
            Route::post('bloques/create', 'store')->name('bloques.store');
            Route::get('bloques/{id}/edit', 'edit')->name('bloques.edit');
            Route::post('bloques/update', 'update')->name('bloques.update');
            Route::get('bloques/{id}/state', 'change')->name('bloques.change');
            Route::get('bloques/{id}/delete', 'destroy')->name('bloques.destroy');
        });

        // RUTAS PARA LA GESTIÓN DE ROLES
        Route::controller(RolController::class)->group(function () {
            Route::get('roles', 'index')->name('roles.index');
            Route::get('roles/listar', 'listar')->name('roles.listar');
            Route::post('roles/create', 'store')->name('roles.store');
            Route::get('roles/{id}/edit', 'edit')->name('roles.edit');
            Route::post('roles/update', 'update')->name('roles.update');
            Route::get('roles/{id}/delete', 'destroy')->name('roles.destroy');
        });

        // RUTAS PARA LA GESTIÓN DE CONTRATOS
        Route::controller(ContratoController::class)->group(function () {
            Route::get('contratos', 'index')->name('contratos.index');
            Route::get('contratos/listar', 'listar')->name('contratos.listar');
            Route::post('contratos/create', 'store')->name('contratos.store');
            Route::get('contratos/{id}/edit', 'edit')->name('contratos.edit');
            Route::post('contratos/update', 'update')->name('contratos.update');
            Route::get('contratos/{id}/delete', 'destroy')->name('contratos.destroy');
            Route::get('contratos/{id}/changeState', 'changeState')->name('contratos.changeState');
        });

        // RUTAS PARA LA GESTION DE JORNADAS
        Route::controller(JornadaController::class)->group(function () {
            Route::get('jornadas', 'index')->name('jornadas.index');
            Route::get('jornadas/listar', 'listar')->name('jornadas.listar');
            Route::post('jornadas/create', 'store')->name('jornadas.store');
            Route::get('jornadas/{id}/edit', 'edit')->name('jornadas.edit');
            Route::post('jornadas/update', 'update')->name('jornadas.update');
            Route::get('jornadas/{id}/delete', 'destroy')->name('jornadas.destroy');
            Route::get('jornadas/{id}/changeState', 'changeState')->name('jornadas.changeState');
        });

        // RUTAS PARA LA GESTIÓN DE CONDICIONES
        Route::controller(CondicionController::class)->group(function () {
            Route::get('condiciones', 'index')->name('condiciones.index');
            Route::get('condiciones/listar', 'listar')->name('condiciones.listar');
            Route::post('condiciones/create', 'store')->name('condiciones.store');
            Route::get('condiciones/{id}/edit', 'edit')->name('condiciones.edit');
            Route::post('condiciones/update', 'update')->name('condiciones.update');
            Route::get('condiciones/{id}/delete', 'destroy')->name('condiciones.destroy');
            Route::get('condiciones/{id}/changeState', 'changeState')->name('condiciones.changeState');
        });

        // RUTAS PARA OFERTAS
        Route::controller(OfertaController::class)->group(function () {
            Route::get('ofertas', 'index')->name('ofertas.index');
            Route::get('ofertas/listar', 'listar')->name('ofertas.listar');
            Route::post('ofertas/create', 'store')->name('ofertas.store');
            Route::get('ofertas/{id}/edit', 'edit')->name('ofertas.edit');
            Route::post('ofertas/update', 'update')->name('ofertas.update');
            Route::get('ofertas/{id}/delete', 'destroy')->name('ofertas.destroy');
        });

        // RUTAS PARA LA GESTIÓN DE COORDINACIONES
        Route::controller(CoordinacionController::class)->group(function () {
            Route::get('coordinaciones', 'index')->name('coordinaciones.index');
            Route::get('coordinaciones/listar', 'listar')->name('coordinaciones.listar');
            Route::post('coordinaciones/create', 'store')->name('coordinaciones.store');
            Route::get('coordinaciones/{id}/edit', 'edit')->name('coordinaciones.edit');
            Route::post('coordinaciones/update', 'update')->name('coordinaciones.update');
            Route::get('coordinaciones/{id}/delete', 'destroy')->name('coordinaciones.destroy');
            Route::get('coordinaciones/{id}/changeState', 'changeState')->name('coordinaciones.changeState');
        });

        //RUTAS PARA TIPO DE COMPONENTES
        Route::controller(TipoComponenteController::class)->group(function () {
            Route::get('tiposcomponente', 'index')->name('tipos.index');
            Route::get('tiposcomponente/listar', 'listar')->name('tipos.listar');
            Route::post('tiposcomponente/create', 'store')->name('tipos.store');
            Route::get(
                'tiposcomponente/{id}/edit',
                'edit'
            )->name('tipos.edit');
            Route::post('tiposcomponente/update', 'update')->name('tipos.update');
            Route::get('tiposcomponente/{id}/changeState', 'changeState')->name('tipos.changeState');
        });

        // RUTAS PARA LA GESTIÓN DE TIPO DE PROGRAMA
        Route::controller(TipoProgramaController::class)->group(function () {
            Route::get('tipo-programa', 'index')->name('tiposprograma.index');
            Route::get('tipo-programa/listar', 'listar')->name('tiposprograma.listar');
            Route::post('tipo-programa/create', 'store')->name('tiposprograma.store');
            Route::get('tipo-programa/{id}/edit', 'edit')->name('tiposprograma.edit');
            Route::post('tipo-programa/update', 'update')->name('tiposprograma.update');
            Route::get('tipo-programa/{id}/changeState', 'state')->name('fichas.state');
            Route::get(
                'tipo-programa/{id}/delete',
                'destroy'
            )->name('tiposprograma.destroy');
        });

        // RUTAS PARA TRIMESTRES
        Route::controller(TrimestreController::class)->group(function () {
            Route::get('trimestres', 'index')->name('trimestres.index');
            Route::get('trimestres/listar', 'listar')->name('trimestres.listar');
            Route::post('trimestres/create', 'store')->name('trimestres.store');
            Route::get('trimestres/{id}/edit', 'edit')->name('trimestres.edit');
            Route::post('trimestres/update', 'update')->name('trimestres.update');
        });

        //RUTAS PARA LA GESTIÓN DE FECHAS TRIMESTRES
        Route::controller(ControllerTrimestreAnio::class)->group(function () {
            Route::get('fechasanio', 'index')->name('fechasanio.index');
            Route::get('fechasanio/listar', 'listar')->name('fechasanio.listar');
            Route::post('fechasanio/create', 'store')->name('fechasanio.store');
            Route::get('fechasanio/{id}/edit', 'edit')->name('fechasanio.edit');
            Route::post('fechasanio/update', 'update')->name('fechasanio.update');
            Route::get('fechasanio/{id}/delete', 'destroy')->name('fechasanio.destroy');
            Route::get('fechasanio/{id}/changeState', 'changeState')->name('fechasanio.changeState');
        });

        //RUTAS PARA LA GESTIÓN DE CONDICIONES HORAS
        Route::controller(CondicionHoraController::class)->group(function () {
            Route::get('condicioneshoras', 'index')->name('condicioneshoras.index');
            Route::get('condicioneshoras/listar', 'listar')->name('condicioneshoras.listar');
            Route::post('condicioneshoras/create', 'store')->name('condicioneshoras.store');
            Route::get('condicioneshoras/{id}/edit', 'edit')->name('condicioneshoras.edit');
            Route::post('condicioneshoras/update', 'update')->name('condicioneshoras.update');
            Route::get('condicioneshoras/{id}/delete', 'destroy')->name('condicioneshoras.destroy');
        });

        // RUTAS PARA LA GESTIÓN DE CHARTSS
        Route::get("/graficas", [ChartsController::class, 'graficas']) -> name("graficas.index");

        // RUTAS PARA LA GESTIÓN DE DIAS IGNORADOS
        Route::controller(FestivosController::class)->group(function () {
           Route::get('festivos', 'index')->name('festivos.index');
           Route::get('festivos/listar', 'listar')->name('festivos.listar');
           Route::post('festivos/create', 'store')->name('festivos.store');
           Route::get('festivos/{id}/delete', 'destroy')->name('festivos.destroy');
        });
    });
});

//LANDING PAGE
// Route::get('/', 'LandingPageController@index')->name('landingpage.index');
// Route::get('/landingpage', 'index')->name('landingpage.index');

Route::get('landingpage', function(){
    return view('landingpage.index');
})->name('landing');

//RUTAS PARA LA GESTIÓN DEL LOGIN

Route::get('inicio-sesion', [LoginController::class, 'index'])->name('inicio');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('login', function () {
    return view('autenticacion.login');
});

Route::get('users/export', [PersonaController::class, 'export']);
