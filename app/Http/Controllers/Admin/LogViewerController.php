<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = "";

        if (File::exists($logPath)) {
            // Read last 500 lines for performance
            $file = file($logPath);
            $lines = array_slice($file, -500);
            $logs = implode("", array_reverse($lines));
        } else {
            $logs = "Log file not found.";
        }

        return view('admin.logs.index', compact('logs'));
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, "");
        }
        return back()->with('success', 'Log berhasil dibersihkan.');
    }
}
