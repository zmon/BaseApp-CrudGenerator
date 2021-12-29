<?php

namespace App\Http\Controllers\[[model_uc]];

use App\Http\Controllers\Controller;
use App\Http\Requests\[[model_uc]]\[[model_uc]]ApiIndexRequest;
use App\Models\[[model_uc]];
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;


class [[model_uc]]Api extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index([[model_uc]]ApiIndexRequest $request)
    {

        $filters = Controller::getIndexFiltersFromRequestAndRemember(
            $request,
            [[model_uc]]Controller::getIndexFilters(),
            [[model_uc]]Controller::getIndexFilterKeyPrefix()
        );

        return [[model_uc]]::indexData(10, $filters);
    }

    /**
     * Returns "options" for HTML select.
     * @return Collection;
     */
    public function getOptions(): Collection
    {
        return [[model_uc]]::getOptions();
    }

}
