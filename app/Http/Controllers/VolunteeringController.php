<?php 
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class VolunteeringController extends Controller
{
    // 🔹 LISTAR TAREAS
public function index()
{
    $userId = auth()->id();

    $tasks = Task::with([
        'skills',
        'taskAssignments' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }
    ])
    ->latest()
    ->get();

    return view('volunteering.index', compact('tasks'));
}



    // 🔹 POSTULARSE
    public function apply($taskId)
    {
        $user = auth()->user();

        // evitar duplicados
        $exists = TaskAssignment::where('task_id', $taskId)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Ya te postulaste a esta tarea');
        }

        TaskAssignment::create([
            'task_id' => $taskId,
            'user_id' => $user->id,
            'supervisor' => 1, // ⚠️ luego lo mejoras
            'donation_request_id' => null, // ⚠️ depende de tu lógica
            'status' => 'solicitada',
        ]);

        return back()->with('success', 'Postulación enviada correctamente');
    }
}