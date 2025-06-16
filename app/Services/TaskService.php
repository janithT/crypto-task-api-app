<?php

namespace App\Services;
use App\Models\Task;

class TaskService
{

    public function __construct(public Task $task)
    {
        
    }

    /**
     * Get all tasks.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Task>
     */
    public function getAllTasks(array $filters = [], int $perPage = 10)
    {

        return $this->task
            ->when(isset($filters['status']) && $filters['status'] !== 'all', function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })

            // if we have more filters, we can add them here
            // ...
            ->orderBy('id', 'desc')
            ->paginate($perPage);

    }
    
    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {

        $task_key = generateTaskKey('task_', true);

        // assume title should unique .
        return $this->task->firstOrCreate(
            ['taskkey' => $task_key, 'title' => $data['title']], 
            [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status'      => $data['status'] ?? 'pending',
            ]
        );
    }

    /**
     * Update an existing task.
     *
     * @param string $id
     * @param array $data
     * @return Task
     */
    public function updateTask(string $id, array $data): Task
    {
        $task = $this->task->findOrFail($id);
        $task->update($data);
        return $task;
    }

    /**
     * Update an existing task.
     *
     * @param string $id
     * @return Task
     */
    public function showTask(string $id) {

        $task = $this->task->findOrFail($id);
        return $task;
    }

    /**
     * Delete a task.
     *
     * @param string $id
     * @return bool|null
     */
    public function deleteTask(string $id): ?bool
    {
        $task = $this->task->findOrFail($id);
        return $task->delete();
    }

    /**
     * Mark a task as complete.
     *
     * @param string $id
     * @return Task
     */
    public function completeTask(string $id): Task
    {
        // or user completeStatus like common function for update statuses.
        $task = $this->task->findOrFail($id);

        if ($task && $task->status === 'complete') {
            throw new \Exception('Task is already completed.');
        }

        $task->status = 'complete';
        $task->save();

        return $task;
    }

}