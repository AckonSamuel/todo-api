<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Http\Resources\TodoResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

/**
 * @group Todos
 *
 * APIs for managing todos
 */
class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
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

            $query->orderBy('updated_at', 'desc');

            return TodoResource::collection($query->get());
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch todos.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Define the allowed parameters for this request
            $allowedParams = ['title', 'details', 'status'];

            // Get all the input from the request
            $requestParams = array_keys($request->all());

            // Check if there are any unwanted parameters in the request
            $unexpectedParams = array_diff($requestParams, $allowedParams);

            if (!empty($unexpectedParams)) {
                return response()->json([
                    'error' => 'Unwanted parameters found: ' . implode(', ', $unexpectedParams),
                ], 400); // Bad Request
            }

            // Check if the request body is empty
            if (empty($request->all())) {
                return response()->json([
                    'error' => 'Request body cannot be empty.',
                ], 400); // Bad Request
            }

            // Validate required fields in the body
            $request->validate([
                'title' => 'required|string|max:255',
                'details' => 'required|string',
                'status' => 'required|in:completed,in progress,not started',
            ]);

            // Create the Todo item
            $todo = Todo::create($request->only(['title', 'details', 'status']));

            return new TodoResource($todo);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to create the todo.',
                'message' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $todo = Todo::findOrFail($id);
            return new TodoResource($todo);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Todo not found.',
                'message' => $e->getMessage(),
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to fetch the todo.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $todo = Todo::findOrFail($id);

            // Define the allowed parameters for this request
            $allowedParams = ['title', 'details', 'status'];

            // Get all the input from the request
            $requestParams = array_keys($request->all());

            // Check if there are any unwanted parameters in the request
            $unexpectedParams = array_diff($requestParams, $allowedParams);

            if (!empty($unexpectedParams)) {
                return response()->json([
                    'error' => 'Unwanted parameters found: ' . implode(', ', $unexpectedParams),
                ], 400); // Bad Request
            }

            // Validate request data, only if provided
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'details' => 'sometimes|string',
                'status' => 'sometimes|in:completed,in progress,not started',
            ]);

            // Update the Todo item
            $todo->update($request->only(['title', 'details', 'status']));

            return new TodoResource($todo);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Todo not found.',
                'message' => $e->getMessage(),
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to update the todo.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $todo = Todo::findOrFail($id);
            $todo->delete();
            return response()->json([
                'message' => 'Todo deleted successfully.',
            ], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Todo not found.',
                'message' => $e->getMessage(),
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to delete the todo.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
