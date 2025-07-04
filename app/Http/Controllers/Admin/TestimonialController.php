<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all()->sortByDesc('updated_at');;
        return view('admin.pages.testimonial.list', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.pages.testimonial.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'heading' => 'required|string|max:255',
            'description' => 'required',
            'client_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required',
        ]);
        $imagePath = null;
        if ($request->hasFile('client_image')) {
            $file = $request->file('client_image');
            $originalName = "testimonial_".time().".".$file->getClientOriginalExtension();
            $upload_folder = 'testimonials';
            $imagePath = $file->storeAs($upload_folder,$originalName, 'public');
        }
        Testimonial::create([
            'client_name' => $request->client_name,
            'heading' => $request->heading,
            'description' => $request->description,
            'client_image' => $imagePath,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.testimonial.list')->with('success', 'Testimonial added successfully');
    }

    public function edit(Request $request,$id)
    {
        $testimonial = null;
        if($id > 0){
            $testimonial = Testimonial::findOrFail($id);
        }
        return view('admin.pages.testimonial.edit', compact('testimonial'));
    }

    public function update(Request $request)
    {
        if($request->input('id') > 0){
            $testimonial = Testimonial::findOrFail($request->input('id'));
        }
        $request->validate([
            'client_name' => 'required|string|max:255',
            'heading' => 'required|string|max:255',
            'description' => 'required',
            'client_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $data = [
            'client_name' => $request->client_name,
            'heading' => $request->heading,
            'description' => $request->description,
            'status' => $request->status,
        ];
        $imagePath = null;
        if ($request->hasFile('client_image')) {
             $file = $request->file('client_image');
            $originalName = "testimonial_".time().".".$file->getClientOriginalExtension();
            $upload_folder = 'testimonials/';
            $imagePath = $file->storeAs($upload_folder,$originalName, 'public');
            $data['client_image'] = $imagePath;
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonial.list')->with('success', 'Testimonial updated successfully');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->client_image) {
            Storage::disk('public')->delete($testimonial->client_image);
        }

        $testimonial->delete();
        return redirect()->route('admin.testimonial.list')->with('success', 'Testimonial deleted successfully');
    }
    public function delete($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return redirect()->route('admin.testimonial.list')->with('message', 'Testimonial deleted successfully');
    }
}
