<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ClubAccomplishmentController extends Controller
{
    public function index()
    {
        return Inertia::render('ClubAccomplishment');
    }
}
