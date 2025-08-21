<?php

namespace App\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class UploadImage
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|max:5120', // max 5MB
        ]);

        $file = $request->file('image');
        $filename = 'temp/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
        Storage::disk('s3')->putFileAs('temp', $file, basename($filename), 'public');
        $url = Storage::disk('s3')->url($filename);

        return response()->json(['url' => $url]);
    }
}
