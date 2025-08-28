<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ContactsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ContactsDataTable $dataTable)
    {
        $generalSetting = GeneralSetting::first();
        return $dataTable->render('admin.contact.index', compact('generalSetting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $validatedData = $request->validated();
        Contact::create($validatedData);
        return response()->json(["status" => "success", "message" => "Update successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $generalSettings = GeneralSetting::all();
        $contact = Contact::findOrFail($id);
        return response()->json(["status" => "success", "data" => $contact, 'generalSettings' => $generalSettings]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $validatedData = $request->validated();
        $contact->update($validatedData);
        return response()->json(["status" => "success","message" => "Updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            $contact = Contact::findOrFail($id);
            $contact->delete();
            return response()->json(["status" => "success", "message" => "Updated successfully"]);
        }
    }
}
