<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\DonationType;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\DonationItemPhoto;
use Illuminate\Http\Request;

class DonationFormController extends Controller
{
    public function show($campaign_id)
    {
        $donationTypes = DonationType::all();
        return view('forms.donation', compact('campaign_id', 'donationTypes'));
    }
    
public function store(Request $request)
{
    $request->validate([
        'name_donation' => 'required|string|max:150',
        'donation_date' => 'required|date',
        'notes' => 'nullable|string',
        'campaign_id' => 'required|exists:campaigns,id',
        'items' => 'required|array|min:1',
        'items.*.donation_type_id' => 'required|exists:donation_types,id',
        'items.*.item_name' => 'required|string|max:255',
        'items.*.quantity' => 'nullable|numeric',
        'items.*.unit' => 'nullable|string|max:50',
        'items.*.description' => 'nullable|string',
        'items.*.photo' => 'nullable|image|max:2048',
    ]);

    DB::beginTransaction();

        // Crear la donación primero sin la referencia
        $donation = new Donation([
            'name_donation' => $request->name_donation,
            'donation_date' => $request->donation_date,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
            'received_by_id' => auth()->id(),
            'status_id' => 1,
            'during_campaign_id' => $request->campaign_id,
        ]);

        // Guardar para obtener el ID
        $donation->save();

        // Generar referencia única basada en el ID
        $donation->referencia = 'REF-' . $donation->id;
        $donation->save();


        // Crear los ítems
        foreach ($request->items as $index => $itemData) {
            $item = DonationItem::create([
                'donation_id' => $donation->id,
                'donation_type_id' => $itemData['donation_type_id'],
                'item_name' => $itemData['item_name'],
                'quantity' => $itemData['quantity'] ?? null,
                'unit' => $itemData['unit'] ?? null,
                'description' => $itemData['description'] ?? null,
            ]);

            // Guardar imagen si viene
            if (isset($itemData['photo']) && $request->hasFile("items.$index.photo")) {
                $photoFile = $request->file("items.$index.photo");
                $path = $photoFile->store('donation_items', 'public');

                DonationItemPhoto::create([
                    'donation_item_id' => $item->id,
                    'photo_url' => $path,
                ]);

            }
        }

        DB::commit();

        return redirect()->route('completed.thankyou')
            ->with('success', 'Donación registrada correctamente.');

  
}



}
