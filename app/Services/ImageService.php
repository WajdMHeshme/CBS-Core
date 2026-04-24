<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public const MAX_IMAGES_PER_CAR = 10;

    public function upload(Car $car, array $images, ?string $alt = null): array
    {
        $created = [];

        $hasMain = CarImage::where('car_id', $car->id)
            ->where('is_main', true)
            ->exists();

        foreach ($images as $i => $image) {
            $path = $image->store("cars/{$car->id}", 'public');

            $created[] = CarImage::create([
                'car_id' => $car->id,
                'path' => $path,
                'is_main' => (! $hasMain && $i === 0),
                'alt' => $alt,
            ]);
        }

        return $created;
    }

    public function setMain(Car $car, CarImage $image): void
    {
        CarImage::where('car_id', $car->id)->update(['is_main' => false]);
        $image->update(['is_main' => true]);
    }

    public function softDelete(Car $car, CarImage $image): void
    {
        $wasMain = (bool) $image->is_main;

        $image->delete();

        if ($wasMain) {
            $this->makeAnyImageMain($car->id);
        }
    }

    public function forceDelete(Car $car, CarImage $image): void
    {
        $wasMain = (bool) $image->is_main;

        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->forceDelete();

        if ($wasMain) {
            $this->makeAnyImageMain($car->id);
        }
    }

    public function restore(Car $car, CarImage $image): void
    {
        $image->restore();

        $hasMain = CarImage::where('car_id', $car->id)
            ->where('is_main', true)
            ->exists();

        if (! $hasMain) {
            CarImage::where('car_id', $car->id)->update(['is_main' => false]);
            $image->update(['is_main' => true]);
        }
    }

    private function makeAnyImageMain(int $carId): void
    {
        $next = CarImage::where('car_id', $carId)->first();

        if ($next) {
            CarImage::where('car_id', $carId)->update(['is_main' => false]);
            $next->update(['is_main' => true]);
        }
    }
}
