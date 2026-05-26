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

    public function store(FavoriteRequest $request)
    {
        return $this->favoriteService->addToFavorites($request->car_id);
    }

    public function destroy(FavoriteRequest $request)
    {
        return $this->favoriteService->removeFromFavorites($request->car_id);
    }

    public function index()
    {
        return $this->favoriteService->getFavorites();
    }
}
