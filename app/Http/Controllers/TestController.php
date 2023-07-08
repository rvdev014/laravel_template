<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TestController extends Controller
{
    public function getImage(Request $request): BinaryFileResponse|JsonResponse
    {
        // get params
        $test = $request->get('test');
        if (!$test) {
            return response()->json([
                'success' => false,
                'message' => 'Test param is required'
            ]);
        }
        $imagePath = storage_path('app/tmps/test.jpg');
        return response()->file($imagePath);
    }

    public function storeImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        /** @var UploadedFile $file */
        $file = $request->image;
        $file->storeAs('tmps', 'test.jpg', 'local');
        return response()->json([
            'success' => true,
            'message' => 'You have successfully uploaded image.',
            'image' => $file->getRealPath()
        ]);
    }
}
