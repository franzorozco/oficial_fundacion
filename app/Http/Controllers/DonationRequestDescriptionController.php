<?php

namespace App\Http\Controllers;

use App\Models\DonationRequestDescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequestDescriptionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\DonationRequest;
use Illuminate\Support\Facades\Storage;

class DonationRequestDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $donationRequestDescriptions = DonationRequestDescription::paginate();

        return view('donation-request-description.index', compact('donationRequestDescriptions'))
            ->with('i', ($request->input('page', 1) - 1) * $donationRequestDescriptions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $donationRequestDescription = new DonationRequestDescription();

        return view('donation-request-description.create', compact('donationRequestDescription'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(DonationRequestDescriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/supporting_documents', $filename);
            $data['supporting_documents'] = $filename;
        }

        DonationRequestDescription::create($data);

        return Redirect::route('donation-request-descriptions.index')
            ->with('success', 'Registro creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $donationRequestDescription = DonationRequestDescription::find($id);

        return view('donation-request-description.show', compact('donationRequestDescription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DonationRequestDescription $donationRequestDescription)
{
    $donationRequests = DonationRequest::all(); // o como tú los estés obteniendo

    return view('donation-request-description.edit', [
        'donationRequestDescription' => $donationRequestDescription,
        'donationRequests' => $donationRequests,
    ]);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(DonationRequestDescriptionRequest $request, DonationRequestDescription $donationRequestDescription): RedirectResponse
    {
        $data = $request->validated();

        // Eliminar archivo si el checkbox fue marcado
        if ($request->filled('remove_supporting_document') && $donationRequestDescription->supporting_documents) {
            Storage::delete('public/supporting_documents/' . $donationRequestDescription->supporting_documents);
            $data['supporting_documents'] = null;
        }

        // Si suben un nuevo archivo, reemplaza el anterior
        if ($request->hasFile('supporting_document')) {
            if ($donationRequestDescription->supporting_documents) {
                Storage::delete('public/supporting_documents/' . $donationRequestDescription->supporting_documents);
            }
            $file = $request->file('supporting_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/supporting_documents', $filename);
            $data['supporting_documents'] = $filename;
        } else if (!empty($donationRequestDescription->supporting_documents) && $request->filled('supporting_document_name')) {
            // Cambiar nombre del archivo si se modificó el nombre y no se subió nuevo archivo
            $oldFilename = $donationRequestDescription->supporting_documents;
            $oldPath = 'public/supporting_documents/' . $oldFilename;

            $newNameSanitized = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('supporting_document_name')); // Sanitiza nombre
            $extension = pathinfo($oldFilename, PATHINFO_EXTENSION);
            $newFilename = $newNameSanitized . '.' . $extension;
            $newPath = 'public/supporting_documents/' . $newFilename;

            // Solo renombrar si el nombre cambió
            if ($newFilename !== $oldFilename && Storage::exists($oldPath)) {
                Storage::move($oldPath, $newPath);
                $data['supporting_documents'] = $newFilename;
            }
        }

        $donationRequestDescription->update($data);

        return Redirect::route('donation-request-descriptions.index')
            ->with('success', 'Registro actualizado exitosamente');
    }


    public function destroy($id): RedirectResponse
    {
        DonationRequestDescription::find($id)->delete();

        return Redirect::route('donation-request-descriptions.index')
            ->with('success', 'DonationRequestDescription deleted successfully');
    }

    // Mostrar eliminados
    public function deleted(Request $request): View
    {
        $donationRequestDescriptions = DonationRequestDescription::onlyTrashed()->paginate();

        return view('donation-request-description.trashed', compact('donationRequestDescriptions'))
            ->with('i', ($request->input('page', 1) - 1) * $donationRequestDescriptions->perPage());

    }
    // Restaurar
    public function restore($id): RedirectResponse
    {
        DonationRequestDescription::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('donation-request-descriptions.deleted')
            ->with('success', 'Registro restaurado exitosamente.');
    }

    // Eliminar permanentemente
    public function forceDelete($id): RedirectResponse
    {
        DonationRequestDescription::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('donation-request-descriptions.deleted')
            ->with('success', 'Registro eliminado permanentemente.');
    }
}
