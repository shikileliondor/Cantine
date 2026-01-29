<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Remise\StoreRemiseRequest;
use App\Http\Requests\Remise\UpdateRemiseRequest;
use App\Models\Remise;
use App\Services\RemiseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RemiseController extends Controller
{
    public function __construct(private readonly RemiseService $remiseService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        return response()->json($this->remiseService->list($perPage));
    }

    public function store(StoreRemiseRequest $request): JsonResponse
    {
        $remise = $this->remiseService->create($request->validated());

        return response()->json($remise, 201);
    }

    public function show(Remise $remise): JsonResponse
    {
        return response()->json($remise->load('eleve'));
    }

    public function update(UpdateRemiseRequest $request, Remise $remise): JsonResponse
    {
        $remise = $this->remiseService->update($remise, $request->validated());

        return response()->json($remise);
    }

    public function destroy(Remise $remise): JsonResponse
    {
        $this->remiseService->delete($remise);

        return response()->noContent();
    }
}
