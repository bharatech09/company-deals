<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::all()->sortByDesc('updated_at');;
        return view('admin.pages.announcement.list', compact('announcements'));
    }

    public function create()
    {
        return view('admin.pages.announcement.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'announcement_date' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);
        
        Announcement::create([
            'title' => $request->title,
            'announcement_date' => $request->announcement_date,
            'description' => $request->description,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.announcement.list')->with('success', 'Announcement added successfully');
    }

    public function edit(Request $request,$id)
    {
        $announcement = null;
        if($id > 0){
            $announcement = Announcement::findOrFail($id);
        }
        return view('admin.pages.announcement.edit', compact('announcement'));
    }

    public function update(Request $request)
    {
        if($request->input('id') > 0){
            $announcement = Announcement::findOrFail($request->input('id'));
        }
        $request->validate([
           'title' => 'required|string|max:255',
            'announcement_date' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);
        $data = [
            'title' => $request->title,
            'announcement_date' => $request->announcement_date,
            'description' => $request->description,
            'status' => $request->status,
        ];
        $announcement->update($data);

        return redirect()->route('admin.announcement.list')->with('success', 'Announcement updated successfully');
    }

    
    public function delete($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcement.list')->with('message', 'Announcement deleted successfully');
    }
}
