<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

 /**
 * @OA\Info(
 *     title="Task Manager API",
 *     version="1.0.0",
 *     description="API documentation for the Task Manager project."
 * )
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     required={"title", "description", "due_date", "create_date", "status", "priority", "category"},
 *     @OA\Property(property="title", type="string", description="The title of the task"),
 *     @OA\Property(property="description", type="string", description="The description of the task"),
 *     @OA\Property(property="due_date", type="string", format="date-time", description="The due date of the task"),
 *     @OA\Property(property="create_date", type="string", format="date-time", description="The creation date of the task"),
 *     @OA\Property(property="status", type="string", description="The status of the task"),
 *     @OA\Property(property="priority", type="string", description="The priority level of the task"),
 *     @OA\Property(property="category", type="string", description="The category of the task")
 * )
 */
class TaskController extends Controller
{
/**
     * @OA\Get(
     *     path="/api/tasks",
     *     operationId="getTasks",
     *     tags={"Tasks"},
     *     summary="Get all tasks",
     *     description="Returns a list of all tasks with optional search and sorting.",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search term for task titles",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         required=false,
     *         description="Sort by 'due_date' or 'create_date'",
     *         @OA\Schema(type="string", enum={"due_date", "create_date"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items()
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Start with the base query
        $tasks = Task::query();

        // Apply search filter if provided
        if ($request->has('search')) {
            $tasks->where('title', 'LIKE', '%' . $request->search . '%');
        }

        // Apply sorting if provided
        if ($request->has('sort') && in_array($request->sort, ['due_date', 'create_date'])) {
            $tasks->orderBy($request->sort, 'asc'); // Default to ascending order
        }

        // Execute the query and return results
        return response()->json($tasks->get());
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     description="Create a new task with the provided details",
     *     operationId="createTask",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->create_date = $request->create_date;
        $task->status = $request->status;
        $task->priority = $request->priority;
        $task->category = $request->category;
        $task->save();

        return  response()->json([
            'id' => $task->id,
            'message' => 'Task created successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get a single task",
     *     description="Retrieve details of a specific task by its ID",
     *     operationId="getTask",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task details",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        return response()->json($task);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update a task",
     *     description="Update the details of an existing task by ID",
     *     operationId="updateTask",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $task = Task::find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->create_date = $request->create_date;
        $task->status = $request->status;
        $task->priority = $request->priority;
        $task->category = $request->category;
        $task->save();
        return response()->json(['message' => 'Task updated successfully']);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete a task",
     *     description="Delete an existing task by ID",
     *     operationId="deleteTask",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        Task::destroy($id);
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
