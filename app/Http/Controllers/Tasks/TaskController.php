<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\Tasks\TaskRequest;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return $this->apiResponse($tasks, 'Task list fetched successfully');
    }

    public function store(TaskRequest $request, TaskService $service)
    {
        $task = $service->create($request->validated());
        return $this->apiResponse($task, 'Task created successfully', null, true, 201);
    }

    public function show(Task $task)
    {
        return $this->apiResponse($task, 'Task fetched successfully');
    }

    public function update(TaskRequest $request, Task $task, TaskService $service)
    {
        $service->update($task, $request->validated());
        return $this->apiResponse($task, 'Task updated successfully');
    }

    public function destroy(Task $task, TaskService $service)
    {
        $service->delete($task);
        return $this->apiResponse(null, 'Task deleted successfully');
    }
}
