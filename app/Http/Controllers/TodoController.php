<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return response()->json([]);
        }

        $todos = Todo::where('student_id', $student->id)
                     ->orderBy('due_date')
                     ->orderBy('created_at', 'desc')
                     ->get();
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $student = Auth::guard('student')->user();

        if (!$student) {
            return response()->json(['error' => 'غير مصرح، يرجى تسجيل الدخول'], 401);
        }

        $todo = Todo::create([
            'student_id'   => $student->id,
            'title'        => $request->title,
            'due_date'     => $request->due_date,
            'is_completed' => false,
        ]);

        return response()->json($todo, 201);
    }

    public function update(Request $request, $id)
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return response()->json(['error' => 'غير مصرح'], 401);
        }
        
        $todo = Todo::where('student_id', $student->id)->findOrFail($id);
        
        $todo->update([
            'is_completed' => $request->boolean('is_completed')
        ]);

        return response()->json($todo);
    }

    public function destroy($id)
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return response()->json(['error' => 'غير مصرح'], 401);
        }
        
        $todo = Todo::where('student_id', $student->id)->findOrFail($id);
        $todo->delete();
        
        return response()->json(null, 204);
    }
}