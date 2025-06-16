<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{

    public function __construct(public TaskService $taskService)
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // use filter if exists
            $filters = $request->only(['status']);
            $perPage = $request->get('perPage', 10);

            $tasks = $this->taskService->getAllTasks($filters, $perPage);

            if ($tasks->isEmpty()) {
                return apiResponseWithStatusCode([], 'success', 'No tasks found', '', 200);
            }

            $paginated = [
                'data' => $tasks->items(),
                'total' => $tasks->total(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
            ];

            return apiResponseWithStatusCode($paginated, 'success', 'Tasks retrieved successfully', '', 200);
        } catch (\Exception $e) {
            return apiResponseWithStatusCode([], 'error', $e->getMessage(), '', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
            $task = $this->taskService->createTask($request->validated());
            return apiResponseWithStatusCode($task, 'success', 'Task created successfully', '', 201);
        } catch (\Exception $e) {
            Log::error('Task creation failed: ' . $e->getMessage() . ' | Request Data: ' . json_encode($request->validated()));
            return apiResponseWithStatusCode([], 'error', $e->getMessage(), '', 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            if (empty($id)) {
                return apiResponseWithStatusCode([], 'error', 'Task ID is required.', '', 422);
            }

            $task = $this->taskService->showTask($id);

            if (!$task) {
                return apiResponseWithStatusCode([], 'error', 'Task not found.', '', 404);
            }

            return apiResponseWithStatusCode($task, 'success', 'Task created successfully', '', 201);
        } catch (\Exception $e) {
            Log::error('Task show failed: ' . $e->getMessage() . ' | id-'. $id);
            return apiResponseWithStatusCode([], 'error', $e->getMessage(), '', 422);
        } 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest  $request, string $id)
    {
        try {
            $task = $this->taskService->updateTask($id, $request->validated());
            return apiResponseWithStatusCode($task, 'success', 'Task updated successfully', '', 200);
        } catch (\Exception $e) {
            Log::error('Task update failed: ' . $e->getMessage() . ' | task id: ' . $id);
            return apiResponseWithStatusCode([], 'error', $e->getMessage(), '', 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = $this->taskService->deleteTask($id);
            return apiResponseWithStatusCode([], 'success', 'Task deleted successfully', '', 200);
        } catch (\Exception $e) {
            Log::error('Task deleted failed: ' . $e->getMessage() . ' | task id: ' . $id);
            return apiResponseWithStatusCode([], 'error', $e->getMessage(), '', 422);
        }
    }

    /**
     * Mark the specified task as complete.
     */
    public function complete(string $id)
    {
        try {
            $task = $this->taskService->completeTask($id);
            return apiResponseWithStatusCode($task, 'success', 'Task marked as complete successfully', '', 200);
        } catch (\Exception $e) {
            Log::error('Task complete update failed: ' . $e->getMessage() . ' | task id: ' . $id);
            return apiResponseWithStatusCode([], 'error', $e->getMessage(), '', 422);
        }
    }
}
