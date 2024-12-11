<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Http\Resources\TodoResource;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;

/**
 * @group Todos
 *
 * APIs for managing todos
 */
class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @queryParam status Filter by status. Example: pending
     * @queryParam search Search by title or details. No-example
     * @queryParam sort_by Sort by field. Example: title
     * @queryParam sort_direction Sort direction. Example: asc
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Todo::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by title and details
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('details', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        if ($request->has('sort_by')) {
            $query->orderBy($request->sort_by, $request->sort_direction ?? 'asc');
        }

        return TodoResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @bodyParam title string required The title of the todo. Example: Buy groceries
     * @bodyParam details string The details of the todo. Example: Milk, Bread, Butter
     * @bodyParam status string required The status of the todo. Example: pending
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTodoRequest $request)
    {
        $todo = Todo::create($request->validated());
        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     *
     * @urlParam id integer required The ID of the todo. Example: 1
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $todo = Todo::findOrFail($id);
        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @urlParam id integer required The ID of the todo. Example: 1
     * @bodyParam title string The title of the todo. Example: Updated title
     * @bodyParam details string The details of the todo. Example: Updated details
     * @bodyParam status string The status of the todo. Example: completed
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTodoRequest $request, $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update($request->validated());
        return new TodoResource($todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @urlParam id integer required The ID of the todo. Example: 1
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        return response()->noContent();
    }
}

