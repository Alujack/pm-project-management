<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        // What should be added to AttachmentController.php store() method
        $request->validate([
            'file' => 'required|file|max:102400', // 10MB max
            'project_id' => 'required|exists:projects,id'
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        $attachment = Attachment::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'projectId' => $request->project_id,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'user_id' => auth()->id(),
        ]);

        return response()->json($attachment, 201);
    }
    public function index(Project $project)
    {
        $attachments = Attachment::where('projectId', $project->id)->get();
        return response()->json($attachments);
    }

    
    public function show(Attachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('public')->path($attachment->path),
            [
                'Content-Type' => $attachment->mime_type,
                'Content-Disposition' => 'inline; filename="' . $attachment->name . '"'
            ]
        );
    }



    public function destroy(Attachment $attachment)
    {

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return response()->noContent();
    }
}