<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarImagesRequest;
use App\Models\Car;
use App\Models\CarImage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class CarImageController extends Controller
{
    // Images Page
    public function index(Car $car)
    {
        $images = CarImage::where('car_id', $car->id)
            ->latest()
            ->get();

        return view('dashboard.cars.images', compact('car', 'images'));
    }

    // Upload Images
    public function store(
        StoreCarImagesRequest $request,
        Car $car,
        ImageService $imageService
    ) {
        $imageService->upload(
            $car,
            $request->file('images'),
            $request->input('alt')
        );

        return redirect()
            ->route('admin.cars.images.index', $car->id)
            ->with('success', __('messages.images.images_uploaded'));
    }

    // Set Main Image
    public function setMain(
        Request $request,
        Car $car,
        CarImage $image,
        ImageService $imageService
    ) {
        $imageService->setMain($car, $image);

        return back()->with('success', __('messages.images.main_image_set'));
    }

    // Soft Delete
    public function destroy(
        Car $car,
        CarImage $image,
        ImageService $imageService
    ) {
        $imageService->softDelete($car, $image);

        return back()->with('success', __('messages.images.image_soft_deleted'));
    }

    // Force Delete
    public function forceDestroy(
        Car $car,
        CarImage $image,
        ImageService $imageService
    ) {
        $imageService->forceDelete($car, $image);

        return back()->with('success', __('messages.images.image_permanently_deleted'));
    }

    // Trashed Images
    public function trashed(Car $car)
    {
        $trashedImages = CarImage::onlyTrashed()
            ->where('car_id', $car->id)
            ->latest()
            ->get();

        return view(
            'dashboard.cars.images_trashed',
            compact('car', 'trashedImages')
        );
    }

    // Restore Image
    public function restore(
        Car $car,
        $image,
        ImageService $imageService
    ) {
        $img = CarImage::withTrashed()
            ->where('car_id', $car->id)
            ->findOrFail($image);

        $imageService->restore($car, $img);

        return back()->with('success', __('messages.images.image_restored'));
    }
}
