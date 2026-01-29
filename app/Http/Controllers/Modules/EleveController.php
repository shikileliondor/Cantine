<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eleve\StoreEleveRequest;
use App\Http\Requests\Eleve\UpdateEleveRequest;
use App\Models\Eleve;
use App\Services\EleveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    public function __construct(private readonly EleveService $eleveService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        return response()->json($this->eleveService->list($perPage));
    }

    public function store(StoreEleveRequest $request): JsonResponse
    {
        $eleve = $this->eleveService->create($request->validated());

        return response()->json($eleve, 201);
    }

    public function show(Eleve $eleve): JsonResponse
    {
        return response()->json($eleve->load('classe'));
    }

    public function update(UpdateEleveRequest $request, Eleve $eleve): JsonResponse
    {
        $eleve = $this->eleveService->update($eleve, $request->validated());

        return response()->json($eleve);
    }

    public function destroy(Eleve $eleve): JsonResponse
    {
        $this->eleveService->delete($eleve);

        return response()->noContent();
    }
}
