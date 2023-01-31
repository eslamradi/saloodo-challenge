<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Http\Requests\Parcel\CreateParcelRequest;
use App\Repositories\ParcelRepository;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function createParcel(CreateParcelRequest $request, ParcelRepository $parcelRepository)
    {
        $parcel = $parcelRepository->create(auth()->user(), $request->validated());
        return  UnifiedJsonResponse::success(['parcel' => $parcel], __('Parcel Created Successfully'), 201);
    }
}
