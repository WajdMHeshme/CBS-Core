<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create booking (transaction)
     */
    public function create(array $data): Booking
    {
        $car = Car::find($data['car_id'] ?? null);

        if (!$car) {
            throw new \Exception('Car not found');
        }

        $userId = auth('sanctum')->id();

        if (!$userId) {
            throw new \Exception('Unauthenticated');
        }

        return DB::transaction(function () use ($data, $userId, $car) {

            // ✅ Lock الصف داخل الـ transaction لمنع Race Condition
            $car = Car::lockForUpdate()->findOrFail($car->id);

            // ✅ التحقق من التعارض داخل الـ transaction
            if (!empty($data['start_date']) && !empty($data['end_date'])) {
                $this->ensureCarAvailable($car->id, $data['start_date'], $data['end_date']);
            }

            $booking = Booking::create([
                'car_id'       => $car->id,
                'user_id'      => $userId,
                'employee_id'  => $car->employee_id ?? null,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'start_date'   => $data['start_date'] ?? null,
                'end_date'     => $data['end_date'] ?? null,
                'status'       => 'pending',
            ]);

            return $booking->load(['car', 'employee', 'user']);
        });
    }

    /**
     * ✅ منطق التحقق الصحيح — يغطي كل حالات التعارض الممكنة:
     *
     *  موجود:   |-------|
     *  جديد:  |---|              ← start قبل، end في المنتصف
     *  جديد:          |---|      ← start في المنتصف، end بعد
     *  جديد:    |---|            ← جوا الموجود بالكامل
     *  جديد:  |-----------|      ← يغطي الموجود بالكامل ← whereBetween كان يفشل هون!
     */
    private function ensureCarAvailable(int $carId, string $startDate, string $endDate): void
    {
        $conflict = Booking::where('car_id', $carId)
            ->whereNotIn('status', ['canceled', 'rejected']) // ✅ تجاهل الحجوزات الملغية
            ->where('start_date', '<', $endDate)            // ✅ الشرط الصحيح
            ->where('end_date', '>', $startDate)
            ->exists();

        if ($conflict) {
            throw new \Exception('Car is already booked for this period');
        }
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        return $booking->load([
            'car',
            'employee',
            'user',
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        $booking->update([
            'status' => 'canceled',
        ]);

        return $booking;
    }
}
