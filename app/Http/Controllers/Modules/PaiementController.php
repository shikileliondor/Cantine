<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Paiement\StorePaiementRequest;
use App\Http\Requests\Paiement\UpdatePaiementRequest;
use App\Models\Paiement;
use App\Services\PaiementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function __construct(private readonly PaiementService $paiementService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        return response()->json($this->paiementService->list($perPage));
    }

    public function store(StorePaiementRequest $request): JsonResponse
    {
        $paiement = $this->paiementService->create($request->validated());

        return response()->json($paiement, 201);
    }

    public function show(Paiement $paiement): JsonResponse
    {
        return response()->json($paiement->load(['eleve', 'facture']));
    }

    public function update(UpdatePaiementRequest $request, Paiement $paiement): JsonResponse
    {
        $paiement = $this->paiementService->update($paiement, $request->validated());

        return response()->json($paiement);
    }

    public function destroy(Paiement $paiement): JsonResponse
    {
        $this->paiementService->delete($paiement);

        return response()->noContent();
    }
}
