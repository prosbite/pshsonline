<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\LogRecord;

class LogRecordController extends Controller
{
    public function index() {
        $logs = LogRecord::with('user')->orderBy('created_at', 'desc')->get();
        return Inertia::render('admin/LogRecords', [
            'logs' => $logs,
        ]);
    }
}
