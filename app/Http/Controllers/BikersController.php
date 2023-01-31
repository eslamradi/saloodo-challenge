<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Http\Requests\Parcel\ReserveParcel;
use App\Repositories\ParcelRepository;
use Illuminate\Http\Request;

class BikersController extends Controller
{
    public function reserveParcel(int $id, ReserveParcel $request, ParcelRepository $parcelRepository)
    {
        $parcel = $parcelRepository->getAvailableForReservation($id);
        if (!$parcel) {
            return UnifiedJsonResponse::error([], __('Parcel is no longer available to work with'), 400);
        }

        $parcelRepository->reserve($parcel, array_merge($request->validated(), [
            'biker_id' => auth()->user()->id
        ]));

        return UnifiedJsonResponse::success([], __('Parcel Updated'));
    }
    public function pickupParcel(int $id, ParcelRepository $parcelRepository)
    {
        $parcel = $parcelRepository->getAvailableForPickup($id, auth()->user());
        if (!$parcel) {
            return UnifiedJsonResponse::error([], __('Parcel is no longer available to work with'), 400);
        }

        $parcelRepository->pickup($parcel);

        return UnifiedJsonResponse::success([], __('Parcel Updated'));
    }
    public function deliverParcel(int $id, ParcelRepository $parcelRepository)
    {
        $parcel = $parcelRepository->getAvailableForDelivery($id, auth()->user());
        if (!$parcel) {
            return UnifiedJsonResponse::error([], __('Parcel is no longer available to work with'), 400);
        }

        $parcelRepository->deliver($parcel);

        return UnifiedJsonResponse::success([], __('Updated'));
    }
}
