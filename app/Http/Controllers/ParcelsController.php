<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Repositories\ParcelRepository;
use Illuminate\Http\Request;

class ParcelsController extends Controller
{
    public function list(ParcelRepository $parcelRepository)
    {
        return  UnifiedJsonResponse::success(['parcels' => $parcelRepository->get(auth()->user())]);
    }

    public function show(int $id, ParcelRepository $parcelRepository)
    {
        $parcel = $parcelRepository->getOneByUser(auth()->user(), $id);
        return  UnifiedJsonResponse::success(['parcel' => $parcel]);
    }
}
