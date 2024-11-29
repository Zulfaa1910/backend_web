<?php

namespace App\Http\Controllers;

use App\Models\TaskPekerjaan;
use Illuminate\Http\Request;

class TaskPekerjaanController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $tasks = TaskPekerjaan::all();
        return response()->json($tasks);
    }

    // Show the form for creating a new resource
    public function create()
    {
        // Return view for creating a new task (if using views)
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'kode_sales' => 'required|string',
            'tanggal' => 'required|date',
            'kode_reseller' => 'required|string',
            'keterangan' => 'required|string',
            'status' => 'required|integer',
        ]);

        $task = TaskPekerjaan::create($request->all());
        return response()->json($task, 201);
    }

    // Display the specified resource
    public function show($id)
    {
        $task = TaskPekerjaan::findOrFail($id);
        return response()->json($task);
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        // Return view for editing the task (if using views)
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_sales' => 'string',
            'tanggal' => 'date',
            'kode_reseller' => 'string',
            'keterangan' => 'string',
            'status' => 'integer',
        ]);

        $task = TaskPekerjaan::findOrFail($id);
        $task->update($request->all());

        return response()->json($task);
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $task = TaskPekerjaan::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
