<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

use FPDF;

class DonantesController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();
        $query->whereHas('roles', function ($q) {
            $q->where('name', 'Donador');
        });

        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
            });
        }

        if ($request->filled('email_domain')) {
            $query->where('email', 'like', '%' . $request->input('email_domain'));
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

        $loginFilters = $request->input('login_activity_values', []);
        if (!is_array($loginFilters)) {
            $loginFilters = [$loginFilters];
        }

        // Top 10 por cantidad de donaciones
        if (in_array('top_donations', $loginFilters)) {
            $query->withCount('donationsMade')->orderByDesc('donations_made_count')->limit(10);
        }

        // Top 10 por cantidad de ítems donados
        if (in_array('top_items', $loginFilters)) {
            $query->with(['donationsMade.items']);
            // No se puede limitar directamente con flatMap, tendrás que filtrar esto desde la vista si es necesario.
        }

        // Nunca han iniciado sesión (por ejemplo, sin actividad de login o sin donaciones)
        if (in_array('never_logged', $loginFilters)) {
            $query->whereDoesntHave('logins') // Asegúrate de tener la relación `logins`
                ->orWhereDoesntHave('donationsMade');
        }

        // Mostrar solo inactivos (sin donaciones)
        if (in_array('inactive', $loginFilters)) {
            $query->whereDoesntHave('donationsMade');
        }

        // Mostrar solo activos (con al menos una donación)
        if (in_array('active', $loginFilters)) {
            $query->has('donationsMade');
        }

        $users = $query->withCount('donationsMade')
            ->with(['donationsMade.items' => function ($q) {
                $q->select('donation_id');
            }])
            ->paginate(10);

        $cityList = User::distinct()->pluck('address');

        return view('donantes.index', compact('users', 'cityList'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }



    public function create(): View
    {
        $user = new User();

        return view('donantes.create', compact('user'));
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

        return Redirect::route('donantes.index')
            ->with('success', 'User created successfully.');
    }


    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('donantes.index')->with('error', 'Usuario no encontrado');
        }

        return view('donantes.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = User::find($id);
        return view('donantes.edit', compact('user'));
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

        return Redirect::route('donantes.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return Redirect::route('donantes.index')
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
        return view('donantes.trashed', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    public function restore($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('donantes.trashed')->with('success', 'User restored successfully.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('donantes.trashed')->with('success', 'User permanently deleted.');
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
