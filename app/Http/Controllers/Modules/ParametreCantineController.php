<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParametreCantine\StoreParametreCantineRequest;
use App\Http\Requests\ParametreCantine\UpdateParametreCantineRequest;
use App\Models\ParametreCantine;
use App\Services\ParametreCantineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParametreCantineController extends Controller
{
    public function __construct(private readonly ParametreCantineService $parametreCantineService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        return response()->json($this->parametreCantineService->list($perPage));
    }

    public function store(StoreParametreCantineRequest $request): JsonResponse
    {
        $parametreCantine = $this->parametreCantineService->create($request->validated());

        return response()->json($parametreCantine, 201);
    }

    public function show(ParametreCantine $parametreCantine): JsonResponse
    {
        return response()->json($parametreCantine);
    }

    public function update(UpdateParametreCantineRequest $request, ParametreCantine $parametreCantine): JsonResponse
    {
        $parametreCantine = $this->parametreCantineService->update($parametreCantine, $request->validated());

        return response()->json($parametreCantine);
    }

    public function destroy(ParametreCantine $parametreCantine): JsonResponse
    {
        $this->parametreCantineService->delete($parametreCantine);

        return response()->noContent();
    }
}
