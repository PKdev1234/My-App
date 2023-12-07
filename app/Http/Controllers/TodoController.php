<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    //todos lisitng page display
    public function index(){

        $userId = auth()->id();

        // Fetch todos for the authenticated user
        $todoDetails = Todo::where('user_id', $userId)->get();

        return view('todos.index', compact('todoDetails'));
        
    }

    //todos create page display
    public function create(){

        return view('todos.create');
    }

    //todos create code
    public function store(Request $request){

        $validationRules = [
            'title' => 'required',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'i_is_marked' => 'required',
        ];

        $request->validate($validationRules);

        $data = $request->all();

        $data['user_id'] = Auth::id();
        //image file
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $todo = Todo::create($data);

        if ($request->ajax()) {
            // todo create success message
            return response()->json(['success' => true, 'message' => 'Todo detail created successfully.']);
        }

        // redirection after success message
        return redirect()->route('todos.index')->with('success', 'Todo detail created successfully.');
    
    }

    //todos edit page display
    public function edit($id){

        $todo = Todo::findOrFail($id);
        return view('todos.edit', compact('todo'));
    }

    public function update(Request $request, $id){

        $todo = Todo::findOrFail($id);
    
        $request->validate([
            'title' => 'required',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'i_is_marked' => 'required',
        ]);
    
        $data = $request->all();
    
        // Check if a new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($todo->image) {
                Storage::disk('public')->delete($todo->image);
            }
    
            // Store the new image
            $data['image'] = $request->file('image')->store('images', 'public');
        }
    
        $todo->update($data);
    
        if ($request->ajax()) {
            // todo edit success message
            return response()->json(['success' => true, 'message' => 'Todo detail updated successfully.']);
        }

        // redirection after success message
        return redirect()->route('todos.index')->with('success', 'Todo detail updated successfully.');
    }

    //todos delete code
    public function destroy(Request $request, $todo){

        $todoDetail = Todo::findOrFail($todo);
        $todoDetail->delete();

        if ($request->ajax()) {
            // todo delete success message
            return response()->json(['success' => true, 'message' => 'Todo detail deleted successfully.']);
        }

        // redirection after success message
        return redirect()->route('todos.index')->with('success', 'Todo detail deleted successfully.');
        
    }
}
