<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactParent\StoreContactParentRequest;
use App\Http\Requests\ContactParent\UpdateContactParentRequest;
use App\Models\ContactParent;
use App\Services\ContactParentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactParentController extends Controller
{
    public function __construct(private readonly ContactParentService $contactParentService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        return response()->json($this->contactParentService->list($perPage));
    }

    public function store(StoreContactParentRequest $request): JsonResponse
    {
        $contactParent = $this->contactParentService->create($request->validated());

        return response()->json($contactParent, 201);
    }

    public function show(ContactParent $contactParent): JsonResponse
    {
        return response()->json($contactParent->load('eleve'));
    }

    public function update(UpdateContactParentRequest $request, ContactParent $contactParent): JsonResponse
    {
        $contactParent = $this->contactParentService->update($contactParent, $request->validated());

        return response()->json($contactParent);
    }

    public function destroy(ContactParent $contactParent): JsonResponse
    {
        $this->contactParentService->delete($contactParent);

        return response()->noContent();
    }
}
