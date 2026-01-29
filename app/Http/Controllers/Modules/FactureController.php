<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Facture\StoreFactureRequest;
use App\Http\Requests\Facture\UpdateFactureRequest;
use App\Models\Facture;
use App\Services\FactureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function __construct(private readonly FactureService $factureService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        return response()->json($this->factureService->list($perPage));
    }

    public function store(StoreFactureRequest $request): JsonResponse
    {
        $facture = $this->factureService->create($request->validated());

        return response()->json($facture, 201);
    }

    public function show(Facture $facture): JsonResponse
    {
        return response()->json($facture->load('eleve'));
    }

    public function update(UpdateFactureRequest $request, Facture $facture): JsonResponse
    {
        $facture = $this->factureService->update($facture, $request->validated());

        return response()->json($facture);
    }

    public function destroy(Facture $facture): JsonResponse
    {
        $this->factureService->delete($facture);

        return response()->noContent();
    }
}
