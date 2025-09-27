<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderImageRequest;
use App\Models\Slider;
use App\Models\SliderImage;
use Illuminate\Http\Request;

class SliderImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::all();
        $sliderImages = SliderImage::all();
        return view('admin.sliderImage.index', compact('sliders', 'sliderImages'));
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
    public function store(StoreSliderImageRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = "backend/assets/images/slider/" . $imageName;
                $image->move(public_path("backend/assets/images/slider/"), $imageName);
                $validatedData['image'] = $imagePath;
                SliderImage::create([
                    "slider_id" => $validatedData['slider_id'],
                    "image" => $validatedData['image'],
                    "type" => $validatedData['type'],
                ]);
            }
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id) {
            $sliderImage = SliderImage::findOrFail($id);
            $path = public_path($sliderImage->image);
            if (is_file($path)) {
                unlink($path);
            }
            $sliderImage->delete();
            $notification = [
                "alert-type" => "success",
                "message" => "Deleted successfully"
            ];

            return redirect()->back()->with($notification);
        }
    }
}
