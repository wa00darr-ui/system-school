<?php

namespace App\Http\Controllers;

use App\Models\Document;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Document::count();

        $pending = Document::where('status', 'pending')->count();

        $completed = Document::where('status', 'completed')->count();

        $signed = Document::where('status', 'signed')->count();

        $recentDocuments = Document::latest()->take(10)->get();

        return view('dashboard', compact(
            'total',
            'pending',
            'completed',
            'signed',
            'recentDocuments'
        ));
    }
}
