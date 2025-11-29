<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->todos()->with('category');

        // Search
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'incomplete') {
                $query->incomplete();
            } elseif ($request->status === 'overdue') {
                $query->overdue();
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $todos = $query->get();

        return response()->json($todos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Ensure category belongs to user
        if (isset($validated['category_id'])) {
            $category = $request->user()->categories()->find($validated['category_id']);
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
        }

        $todo = $request->user()->todos()->create($validated);
        $todo->load('category');

        return response()->json($todo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Todo $todo)
    {
        // Ensure user owns this todo
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $todo->load('category');
        return response()->json($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        // Ensure user owns this todo
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'is_completed' => 'sometimes|boolean',
        ]);

        // Ensure category belongs to user
        if (isset($validated['category_id']) && $validated['category_id']) {
            $category = $request->user()->categories()->find($validated['category_id']);
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
        }

        // Handle completion status
        if (isset($validated['is_completed'])) {
            $validated['completed_at'] = $validated['is_completed'] ? Carbon::now() : null;
        }

        $todo->update($validated);
        $todo->load('category');

        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Todo $todo)
    {
        // Ensure user owns this todo
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully']);
    }

    /**
     * Toggle the completion status of a todo.
     */
    public function toggle(Request $request, Todo $todo)
    {
        // Ensure user owns this todo
        if ($todo->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $todo->update([
            'is_completed' => !$todo->is_completed,
            'completed_at' => !$todo->is_completed ? Carbon::now() : null,
        ]);

        $todo->load('category');

        return response()->json($todo);
    }
}
