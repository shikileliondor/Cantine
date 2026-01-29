<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteEleve\StoreNoteEleveRequest;
use App\Http\Requests\NoteEleve\UpdateNoteEleveRequest;
use App\Models\NoteEleve;
use App\Services\NoteEleveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteEleveController extends Controller
{
    public function __construct(private readonly NoteEleveService $noteEleveService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        return response()->json($this->noteEleveService->list($perPage));
    }

    public function store(StoreNoteEleveRequest $request): JsonResponse
    {
        $noteEleve = $this->noteEleveService->create($request->validated());

        return response()->json($noteEleve, 201);
    }

    public function show(NoteEleve $noteEleve): JsonResponse
    {
        return response()->json($noteEleve->load('eleve'));
    }

    public function update(UpdateNoteEleveRequest $request, NoteEleve $noteEleve): JsonResponse
    {
        $noteEleve = $this->noteEleveService->update($noteEleve, $request->validated());

        return response()->json($noteEleve);
    }

    public function destroy(NoteEleve $noteEleve): JsonResponse
    {
        $this->noteEleveService->delete($noteEleve);

        return response()->noContent();
    }
}
