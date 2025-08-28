<?php

namespace App\Http\Controllers\backend;

use App\DataTables\GeneralSettingsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGeneralSettingRequest;
use App\Http\Requests\UpdateGeneralSettingRequest;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GeneralSettingsDataTable $dataTable)
    {
        return $dataTable->render('admin.generalSetting.index');
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
    public function store(StoreGeneralSettingRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->file('logo')) {
            $logo = $request->file('logo');
            $logo_name = uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo_path = "backend/assets/images/logo/" . $logo_name;
            $logo->move(public_path("backend/assets/images/logo/"), $logo_name);
            $validatedData["logo"] = $logo_path;
        }
        GeneralSetting::create($validatedData);
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
        $generalSetting = GeneralSetting::findOrFail($id);
        $generalSetting->logo = asset($generalSetting->logo);
        return response()->json(["status" => "success", "data" => $generalSetting]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGeneralSettingRequest $request, GeneralSetting $general_setting)
    {
        $validatedData = $request->validated();
        if (!empty($validatedData['logo'])) {
            $path = public_path($general_setting->logo);
            if (is_file($path)) {
                unlink($path);
            }
            if ($request->file('logo')) {
                $logo = $request->file('logo');
                $logo_name = uniqid() . '.' . $logo->getClientOriginalExtension();
                $logo_path = "backend/assets/images/logo/" . $logo_name;
                $logo->move(public_path("backend/assets/images/logo/"), $logo_name);
                $validatedData["logo"] = $logo_path;
            }
        }
        $general_setting->update($validatedData);
        return response()->json(["status" => "success", "message" => "Updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            $generalSetting = GeneralSetting::findOrFail($id);
            if ($generalSetting->logo) {
                $path = public_path($generalSetting->logo);
                if (is_file($path)) {
                    unlink($path);
                }
            }
            $generalSetting->delete();
            return response()->json(["status" => "success", "message" => "Updated successfully"]);
        }
    }
}
