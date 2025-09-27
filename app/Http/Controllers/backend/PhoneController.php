<?php

namespace App\Http\Controllers\backend;

use App\DataTables\PhonesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhoneRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Models\Contact;
use App\Models\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PhonesDataTable $dataTable)
    {
        $contacts = Contact::all();
        return $dataTable->render('admin.phone.index', compact('contacts'));
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
    public function store(StorePhoneRequest $request)
    {
        $validatedData = $request->validated();
        Phone::create($validatedData);
        return response()->json(["status" => "success", "message" => "Added successfully"]);
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
        $phones = Phone::findOrFail($id);
        $contacts = Contact::all();
        return response()->json(['status' => "success","data" => $phones,'contacts' => $contacts]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhoneRequest $request, Phone $phone)
    {
        $validatedData = $request->validated();
        $phone->update($validatedData);
        return response()->json(["status" => "success","message" => "Updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if($id){
            $phone = Phone::findOrFail($id)->delete();
            return response()->json(["status" => "success","message" => "Deleted successfully"]);
        }
    }
}
