<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource. 
     */
    public function index()
    {
        $messages = Message::all();
        return view('admin.pages.messages.list', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add()
    {
        $users = DB::table('users')->select('id', 'name')->get();
        return view('admin.pages.messages.add', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
            'message' => 'required',
        ]);

        foreach ($request->user_id as $list) {

            Message::create(['user_id' => $list, 'message' => $request->message]);
        }

        return redirect()->back()->with('success', 'Added Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $message = Message::findOrFail($id);
        return view('admin.pages.messages.edit', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'message' => 'required',
            'role' => 'required|in:buyer,seller',
        ]);

        $message = Message::findOrFail($id);
        $message->update($request->all());

        return redirect()->route('admin.pages.messages.list')->with('success', 'Message updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        // 🔧 FIX: Ensure this route name matches your web.php route definition
        return redirect()->route('pages.messages.list')->with('success', 'Message deleted successfully.');
    }
}
