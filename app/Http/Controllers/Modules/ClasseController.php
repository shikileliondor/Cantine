<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classe\StoreClasseRequest;
use App\Http\Requests\Classe\UpdateClasseRequest;
use App\Models\Classe;
use App\Services\ClasseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function __construct(private readonly ClasseService $classeService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        return response()->json($this->classeService->list($perPage));
    }

    public function store(StoreClasseRequest $request): JsonResponse
    {
        $classe = $this->classeService->create($request->validated());

        return response()->json($classe, 201);
    }

    public function show(Classe $classe): JsonResponse
    {
        return response()->json($classe);
    }

    public function update(UpdateClasseRequest $request, Classe $classe): JsonResponse
    {
        $classe = $this->classeService->update($classe, $request->validated());

        return response()->json($classe);
    }

    public function destroy(Classe $classe): JsonResponse
    {
        $this->classeService->delete($classe);

        return response()->noContent();
    }
}
