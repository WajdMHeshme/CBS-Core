<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Services\FavoriteService;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    // إضافة سيارة إلى المفضلة
    public function store(FavoriteRequest $request)
    {
        // الآن، التحقق قد تم بالفعل من قبل FavoriteRequest
        return $this->favoriteService->addToFavorites($request->car_id);
    }

    // حذف سيارة من المفضلة
    public function destroy(FavoriteRequest $request)
    {
        // التحقق قد تم أيضًا بواسطة FavoriteRequest
        return $this->favoriteService->removeFromFavorites($request->car_id);
    }

    // عرض كل السيارات المفضلة للمستخدم
    public function index()
    {
        return $this->favoriteService->getFavorites();
    }
}
