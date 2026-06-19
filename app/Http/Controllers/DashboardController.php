<?php

namespace App\Http\Controllers;

use App\Models\TicketManagement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        //get all recent tickets
        $tickets = TicketManagement::query()->latest()->paginate(10);

        return view('dashboard', ['user' => $user, 'tickets' => $tickets]);
    }
}
