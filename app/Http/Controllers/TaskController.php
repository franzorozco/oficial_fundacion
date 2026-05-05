<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\TaskAssignment;
use App\Models\TaskRequirement;
use App\Models\Skill;

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
        $skills = Skill::all();

        return view('task.create', compact('task', 'skills'));
    }

    public function store(TaskRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // 🔹 Usuario autenticado
        $data['creator_id'] = auth()->id();

        // 🔹 Días
        $data['required_days'] = implode(',', $request->required_days ?? []);

        // 🔹 Horas
        $data['required_hours'] = $request->start_hour . ' - ' . $request->end_hour;

        // 🔥 🔥 🔥 GUARDAR UBICACIÓN CORRECTAMENTE
        $data['latitude'] = $request->latitude;
        $data['longitude'] = $request->longitude;

        // Crear tarea
        $task = Task::create($data);

        // 🔹 Guardar habilidades (SIN lat/lng aquí)
        if ($request->skills) {
            foreach ($request->skills as $index => $skillId) {
                if ($skillId) {
                    TaskRequirement::create([
                        'task_id' => $task->id,
                        'skill_id' => $skillId,
                        'required_level' => $request->levels[$index] ?? 'básico',
                    ]);
                }
            }
        }

        return Redirect::route('tasks.index')
            ->with('success', 'Tarea creada correctamente');
    }

    public function show($id): View
    {
        $task = Task::with('creator')->findOrFail($id);

        $assignments = collect(
            TaskAssignment::with(['assignedUser', 'supervisorUser'])
                ->where('task_id', $id)
                ->get()
                ->groupBy('status')
        );

        return view('task.show', compact('task', 'assignments'));
    }

    public function edit($id): View
    {
        $task = Task::with('requirements')->findOrFail($id);
        $skills = Skill::all();

        return view('task.edit', compact('task', 'skills'));
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $data = $request->validated();

        // 🔹 Días
        $data['required_days'] = implode(',', $request->required_days ?? []);

        // 🔹 Horas
        $data['required_hours'] = $request->start_hour . ' - ' . $request->end_hour;

        // 🔥 🔥 🔥 ACTUALIZAR UBICACIÓN CORRECTAMENTE
        $data['latitude'] = $request->latitude;
        $data['longitude'] = $request->longitude;

        $task->update($data);

        // 🔥 eliminar skills anteriores
        $task->requirements()->delete();

        // 🔥 guardar nuevas
        if ($request->skills) {
            foreach ($request->skills as $index => $skillId) {
                if ($skillId) {
                    TaskRequirement::create([
                        'task_id' => $task->id,
                        'skill_id' => $skillId,
                        'required_level' => $request->levels[$index] ?? 'básico',
                    ]);
                }
            }
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tarea actualizada correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Task::find($id)->delete();

        return Redirect::route('tasks.index')
            ->with('success', 'Task deleted successfully');
    }
}