<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\DonationRequest;
use App\Models\DonationRequestDescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDonationRequestController extends Controller
{
    // Mostrar formulario
    public function create()
    {
        return view('ayuda.form');
    }

    // Guardar solicitud
    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|min:3|max:255',
            
            'recipient_contact' => [
                'required',
                'regex:/^[0-9]{7,15}$/'
            ],

            'recipient_type' => 'required|in:individual,other,organization,community',

            'reason' => 'required|string|min:10|max:1000',

            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            // Mensajes personalizados
            'recipient_name.required' => 'El nombre del beneficiario es obligatorio',
            'recipient_name.min' => 'El nombre debe tener al menos 3 caracteres',

            'recipient_contact.required' => 'El contacto es obligatorio',
            'recipient_contact.regex' => 'El contacto debe ser un número válido (solo números, sin letras)',

            'recipient_type.required' => 'Debes seleccionar para quién es la ayuda',

            'reason.required' => 'Debes explicar el motivo',
            'reason.min' => 'El motivo debe ser más detallado (mínimo 10 caracteres)',

            'latitude.required' => 'Debes seleccionar una ubicación en el mapa',
            'longitude.required' => 'Debes seleccionar una ubicación en el mapa',
        ]);

        DB::beginTransaction();

        try {
            // Crear solicitud principal
            $donationRequest = DonationRequest::create([
                'referencia' => 'REQ-' . strtoupper(Str::random(8)),
                'applicant_user__id' => Auth::id(),
                'donation_id' => null,
                'notes' => $request->notes,
                'state' => 'pendiente',
            ]);

            // Crear descripción
            DonationRequestDescription::create([
                'donation_request_id' => $donationRequest->id,
                'recipient_name' => $request->recipient_name,
                'recipient_address' => $request->recipient_address,
                'recipient_contact' => $request->recipient_contact,
                'tipo_beneficiario' => $request->recipient_type, // ✅ CORRECTO
                'reason' => $request->reason,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'extra_instructions' => $request->extra_instructions,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Solicitud enviada correctamente');

        }catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}