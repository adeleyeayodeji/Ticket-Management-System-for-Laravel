<?php

namespace App\Http\Controllers;

use App\Models\TicketManagement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        //auth check
        if (!Auth::check()) {
            //redirect to login page
            return redirect()->route('login.form');
        }

        $user = Auth::user();

        //get all recent tickets
        $tickets = TicketManagement::query()->latest()->paginate(10);

        return view('dashboard', ['user' => $user, 'tickets' => $tickets]);
    }
}
