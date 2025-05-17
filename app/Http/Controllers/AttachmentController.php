<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Issue $issue)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 10MB max
        ]);
        
        $file = $request->file('file');
        $path = $file->store('attachments', 'public');
        
        $attachment = $issue->attachments()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'user_id' => auth()->id(),
        ]);
        
        return response()->json($attachment, 201);
    }
    
    public function show(Attachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404);
        }
        
        return response()->file(
            Storage::disk('public')->path($attachment->path)
        );
    }
    
    public function destroy(Attachment $attachment)
    {
        
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();
        
        return response()->noContent();
    }
}