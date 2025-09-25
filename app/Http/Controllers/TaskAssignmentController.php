<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TaskAssignmentRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use FPDF;

class TaskAssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $query = TaskAssignment::query()->with(['task', 'donationRequest', 'assignedUser', 'supervisorUser']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('task', fn($q2) => $q2->where('name', 'LIKE', "%{$search}%"))
                ->orWhereHas('donationRequest', fn($q3) => $q3->where('referencia', 'LIKE', "%{$search}%"))
                ->orWhereHas('assignedUser', fn($q4) => $q4->where('name', 'LIKE', "%{$search}%"))
                ->orWhereHas('supervisorUser', fn($q5) => $q5->where('name', 'LIKE', "%{$search}%"))
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhere('notes', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('supervisor')) {
            $query->where('supervisor', $request->supervisor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            if ($request->type === 'task') {
                $query->whereNotNull('task_id');
            } elseif ($request->type === 'donation') {
                $query->whereNotNull('donation_request_id');
            }
        }

        if ($request->filled('from')) {
            $query->where('assigned_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->where('assigned_at', '<=', $request->to);
        }

        $taskAssignments = $query->paginate(10)->appends($request->query());

        $users = \App\Models\User::orderBy('name')->get();

        return view('task-assignment.index', compact('taskAssignments', 'users'))
            ->with('i', ($request->input('page', 1) - 1) * $taskAssignments->perPage());
    }

    public function create(): View
    {
        $taskAssignment = new TaskAssignment();

        return view('task-assignment.create', compact('taskAssignment'));
    }

    public function store(TaskAssignmentRequest $request): RedirectResponse
    {
        TaskAssignment::create($request->validated());

        return Redirect::route('task-assignments.index')
            ->with('success', 'TaskAssignment created successfully.');
    }

    public function show($id): View
    {
        $taskAssignment = TaskAssignment::find($id);

        return view('task-assignment.show', compact('taskAssignment'));
    }

    public function edit($id): View
    {
        $taskAssignment = TaskAssignment::find($id);

        return view('task-assignment.edit', compact('taskAssignment'));
    }

    public function update(TaskAssignmentRequest $request, TaskAssignment $taskAssignment): RedirectResponse
    {
        $taskAssignment->update($request->validated());

        return Redirect::route('task-assignments.index')
            ->with('success', 'TaskAssignment updated successfully');
    }

    public function updateStatus(Request $request, $id, $status)
    {
        $validStatuses = ['asignada', 'denegada'];
        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Estado inválido.');
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $assignment = TaskAssignment::findOrFail($id);
        $assignment->status = $status;
        $assignment->notes = $request->input('notes');
        $assignment->supervisor = Auth::id(); // Aquí se registra el supervisor actual
        $assignment->save();

        return redirect()->back()->with('success', 'Asignación actualizada correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        TaskAssignment::find($id)->delete();

        return Redirect::route('task-assignments.index')
            ->with('success', 'TaskAssignment deleted successfully');
    }



public function exportPdf(Request $request)
{
    $query = TaskAssignment::with(['task', 'donationRequest', 'assignedUser', 'supervisorUser']);

    // Aplicar filtros igual que en index
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->whereHas('task', fn($q) => $q->where('name', 'like', '%'.$request->search.'%'))
              ->orWhereHas('donationRequest', fn($q) => $q->where('referencia', 'like', '%'.$request->search.'%'))
              ->orWhere('notes', 'like', '%'.$request->search.'%');
        });
    }

    if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
    if ($request->filled('supervisor')) $query->where('supervisor', $request->supervisor);
    if ($request->filled('status')) $query->where('status', $request->status);
    if ($request->filled('type')) {
        $request->type === 'task'
            ? $query->whereNotNull('task_id')
            : $query->whereNotNull('donation_request_id');
    }
    if ($request->filled('from')) $query->where('assigned_at', '>=', $request->from);
    if ($request->filled('to')) $query->where('assigned_at', '<=', $request->to);

    $assignments = $query->get();

    // PDF horizontal
    $pdf = new Fpdf('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Listado de Asignaciones', 0, 1, 'C');
    $pdf->Ln(5);

    // Encabezados de tabla
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(200, 220, 255);

    $header = ['#', 'Tarea', 'Donación', 'Responsable', 'Supervisor', 'Estado', 'Fecha', 'Notas'];
    $widths = [10, 45, 35, 35, 35, 25, 35, 60];

    foreach ($header as $i => $col) {
        $pdf->Cell($widths[$i], 7, $col, 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Cuerpo de tabla
    $pdf->SetFont('Arial', '', 9);
    foreach ($assignments as $i => $a) {
        $pdf->Cell($widths[0], 6, $i + 1, 1);
        $pdf->Cell($widths[1], 6, substr($a->task->name ?? '-', 0, 30), 1);
        $pdf->Cell($widths[2], 6, substr($a->donationRequest->referencia ?? '-', 0, 25), 1);
        $pdf->Cell($widths[3], 6, substr($a->assignedUser->name ?? '-', 0, 25), 1);
        $pdf->Cell($widths[4], 6, substr($a->supervisorUser->name ?? '-', 0, 25), 1);
        $pdf->Cell($widths[5], 6, ucfirst($a->status), 1);
        $pdf->Cell($widths[6], 6, \Carbon\Carbon::parse($a->assigned_at)->format('d/m/Y H:i'), 1);
        $pdf->Cell($widths[7], 6, substr($a->notes, 0, 50), 1);
        $pdf->Ln();
    }

    $pdf->Output('I', 'asignaciones.pdf');
    exit;
}

}
