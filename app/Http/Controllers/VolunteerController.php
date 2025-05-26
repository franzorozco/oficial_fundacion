<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use App\Models\Profile; // ⬅️ Esta línea es clave
use FPDF;

class VolunteerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        // Filtra solo usuarios con rol "voluntario"
        $query->whereHas('roles', function ($q) {
            $q->where('name', 'voluntario');
        });

        // Filtros generales
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
            });
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }

        if ($request->filled('city')) {
            $query->where('address', $request->input('city'));
        }

        // Filtro por actividad
        if ($request->filled('activity_filters')) {
            $filters = $request->input('activity_filters');

            if (in_array('only_deleted', $filters)) {
                $query->onlyTrashed();
            } elseif (in_array('with_deleted', $filters)) {
                $query->withTrashed();
            }

            if (in_array('most_tasks', $filters)) {
                $query->orderByDesc('tareas_realizadas');
            }

            if (in_array('most_distributions', $filters)) {
                $query->orderByDesc('distribuciones_realizadas');
            }
        }

        // Filtros por perfil
        if ($request->filled('preferred_tasks')) {
            $task = $request->input('preferred_tasks');
            $query->whereHas('profile', function ($q) use ($task) {
                $q->where('preferred_tasks', 'like', "%$task%");
            });
        }

        if ($request->filled('availability_days')) {
            $days = $request->input('availability_days');
            $query->whereHas('profile', function ($q) use ($days) {
                $q->where(function ($subQ) use ($days) {
                    foreach ($days as $day) {
                        $subQ->orWhere('availability_days', 'like', "%$day%");
                    }
                });
            });
        }


        if ($request->filled('experience_level')) {
            $levels = $request->input('experience_level');
            $query->whereHas('profile', function ($q) use ($levels) {
                $q->whereIn('experience_level', $levels);
            });
        }

        if ($request->filled('skills')) {
            $skills = $request->input('skills');
            $query->whereHas('profile', function ($q) use ($skills) {
                $q->where(function ($subQ) use ($skills) {
                    foreach ($skills as $skill) {
                        $subQ->orWhere('skills', 'like', "%$skill%");
                    }
                });
            });
        }


            if ($request->filled('availability_hours')) {
            $hours = $request->input('availability_hours');
            $query->whereHas('profile', function ($q) use ($hours) {
                $q->where(function ($subQ) use ($hours) {
                    foreach ($hours as $hour) {
                        $subQ->orWhere('availability_hours', 'like', "%$hour%");
                    }
                });
            });
        }


        if ($request->filled('transport_available')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('transport_available', $request->input('transport_available'));
            });
        }

        if ($request->filled('physical_condition')) {
            $conditions = $request->input('physical_condition');
            $query->whereHas('profile', function ($q) use ($conditions) {
                $q->whereIn('physical_condition', $conditions);
            });
        }

        if ($request->filled('languages_spoken')) {
            $languages = $request->input('languages_spoken');
            $query->whereHas('profile', function ($q) use ($languages) {
                $q->where(function ($subQ) use ($languages) {
                    foreach ($languages as $lang) {
                        $subQ->orWhere('languages_spoken', 'like', "%$lang%");
                    }
                });
            });
        }


        if ($request->filled('preferred_tasks_multi')) {
            $tasks = $request->input('preferred_tasks_multi');
            $query->whereHas('profile', function ($q) use ($tasks) {
                $q->where(function ($subQ) use ($tasks) {
                    foreach ($tasks as $task) {
                        $subQ->orWhere('preferred_tasks', 'like', "%$task%");
                    }
                });
            });
        }


        if ($request->filled('min_age') || $request->filled('max_age')) {
            $today = Carbon::today();
            $query->whereHas('profile', function ($q) use ($request, $today) {
                if ($request->filled('min_age')) {
                    $minBirthdate = $today->copy()->subYears($request->input('min_age'));
                    $q->where('birthdate', '<=', $minBirthdate);
                }
                if ($request->filled('max_age')) {
                    $maxBirthdate = $today->copy()->subYears($request->input('max_age') + 1)->addDay();
                    $q->where('birthdate', '>=', $maxBirthdate);
                }
            });
        }

        // Datos para los selectores
        $profiles = Profile::query();
        $skillsList = $profiles->distinct()->pluck('skills')->filter()->unique()->flatMap(fn($item) => explode(',', $item))->map(fn($s) => trim($s))->unique()->values();
        $availabilityDaysList = $profiles->distinct()->pluck('availability_days')->filter()->unique()->flatMap(fn($item) => explode(',', $item))->map(fn($d) => trim($d))->unique()->values();
        $availabilityHoursList = $profiles->distinct()->pluck('availability_hours')->filter()->unique()->flatMap(fn($item) => explode(',', $item))->map(fn($h) => trim($h))->unique()->values();
        $preferredTasksList = $profiles->distinct()->pluck('preferred_tasks')->filter()->unique()->flatMap(fn($item) => explode(',', $item))->map(fn($t) => trim($t))->unique()->values();
        $languagesList = $profiles->distinct()->pluck('languages_spoken')->filter()->unique()->flatMap(fn($item) => explode(',', $item))->map(fn($l) => trim($l))->unique()->values();

        // Extraer ciudades únicas
        $cityList = User::distinct()->pluck('address')->filter()->unique()->values();

        // Ejecutar query con conteos
        $users = $query->withCount([
            'tareasAsignadas as tareas_realizadas',
            'distribucionesAsignadas as distribuciones_realizadas'
        ])->paginate(10);

        return view('volunteers.index', compact(
            'users',
            'skillsList',
            'availabilityDaysList',
            'availabilityHoursList',
            'preferredTasksList',
            'languagesList',
            'cityList'
        ))->with('i', (request()->input('page', 1) - 1) * 10);
    }



    public function create(): View
    {
        $user = new User();

        return view('volunteers.create', compact('user'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        
        // Crea usuario con los campos que tiene User
        $user = User::create($validated);
        $user->assignRole('user');
        $user->assignRole('donor');

        // Ahora crea o actualiza perfil con la info adicional
        $user->profile()->create([
            'address' => $validated['address'],
            'location' => $validated['location'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            // Otros campos de profile que quieras guardar
        ]);

        return Redirect::route('volunteers.index')
            ->with('success', 'User created successfully.');
    }


    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('volunteers.index')->with('error', 'Usuario no encontrado');
        }

        return view('volunteers.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = User::find($id);
        return view('volunteers.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        // Validar los campos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
            'required',
            'email',
            Rule::unique('users')->whereNull('deleted_at'),
        ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);


        // Si se proporciona contraseña, la encriptamos
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']); // No actualizar si viene vacía
        }

        // Actualizamos el usuario
        $user->update($validatedData);

        return Redirect::route('volunteers.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return Redirect::route('volunteers.index')
            ->with('success', 'User deleted successfully');
    }
    
    public function trashed(Request $request): View
    {
        $query = User::onlyTrashed(); // Solo usuarios eliminados
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
            });
        }
        $users = $query->paginate();
        return view('volunteers.trashed', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('volunteers.trashed')->with('success', 'User restored successfully.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('volunteers.trashed')->with('success', 'User permanently deleted.');
    }

    public function generatePDF(Request $request)
    {
        $query = User::withTrashed()->with('profile', 'roles');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
            });
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }
        if ($request->filled('city')) {
            $query->where('address', $request->input('city'));
        }

        $users = $query->get();
        $pdf = new Fpdf();
        $pdf->SetTitle('Detalle de Usuarios');
        $pdf->SetFont('Arial', '', 10);
        foreach ($users as $user) {
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Datos del Usuario', 0, 1, 'C');
            $pdf->Ln(2);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50, 7, 'ID:', 0, 0); $pdf->Cell(0, 7, $user->id, 0, 1);
            $pdf->Cell(50, 7, 'Nombre:', 0, 0); $pdf->Cell(0, 7, $user->name, 0, 1);
            $pdf->Cell(50, 7, 'Correo:', 0, 0); $pdf->Cell(0, 7, $user->email, 0, 1);
            $pdf->Cell(50, 7, 'Telefono:', 0, 0); $pdf->Cell(0, 7, $user->phone ?? '-', 0, 1);
            $pdf->Cell(50, 7, 'Direccion:', 0, 0); $pdf->Cell(0, 7, $user->address ?? '-', 0, 1);
            $pdf->Cell(50, 7, 'Email verificado:', 0, 0); $pdf->Cell(0, 7, $user->email_verified_at ?? '-', 0, 1);
            $pdf->Cell(50, 7, 'Fecha de creación:', 0, 0); $pdf->Cell(0, 7, $user->created_at, 0, 1);
            $pdf->Cell(50, 7, 'Fecha de actualización:', 0, 0); $pdf->Cell(0, 7, $user->updated_at, 0, 1);
            $pdf->Cell(50, 7, 'Eliminado:', 0, 0); $pdf->Cell(0, 7, $user->deleted_at ?? 'No', 0, 1);
            $pdf->Ln(5);
            $pdf->Ln(10);
        }
        $pdf->Output('D', 'detalle_usuarios.pdf');
        exit;
    }
}
