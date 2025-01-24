<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Process;
use App\Http\Controllers\AppBaseController;

class ProcessController extends AppBaseController
{
    /**
     * Display a process scoring.
     *
     *
     *
     * @return Response
     */
    public function index()
    {
        $steps = Process::orderBy('order')->get();
        return view('process.index', compact('steps'));
    }

    /**
     * Show the form for creating a new Chauffeur.
     *
     * @return Response
     */
    public function create()
    {
        return view('process.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        Process::create($validated);
        return redirect()->back()->with('success', 'Étape créée avec succès');
    }
}
