<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\TaskAssignment;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $tasks = Task::paginate();

        return view('task.index', compact('tasks'))
            ->with('i', ($request->input('page', 1) - 1) * $tasks->perPage());
    }

    public function create(): View
    {
        $task = new Task();

        return view('task.create', compact('task'));
    }

    public function store(TaskRequest $request): RedirectResponse
    {
        Task::create($request->validated());

        return Redirect::route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show($id): View
    {
        $task = Task::with('user')->findOrFail($id);

        $assignments = collect(
            TaskAssignment::with('user')
                ->where('task_id', $id)
                ->get()
                ->groupBy('status')
        );

        return view('task.show', compact('task', 'assignments'));
    }

    public function edit($id): View
    {
        $task = Task::find($id);

        return view('task.edit', compact('task'));
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return Redirect::route('tasks.index')
            ->with('success', 'Task updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Task::find($id)->delete();

        return Redirect::route('tasks.index')
            ->with('success', 'Task deleted successfully');
    }
}
