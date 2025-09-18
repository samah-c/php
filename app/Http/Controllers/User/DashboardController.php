<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('utilisateur')->user();
        
        $tickets = $user->tickets()
            ->with(['agent', 'categorie'])
            ->orderBy('date_creation', 'desc')
            ->paginate(10);

        $ticketStats = [
            'total' => $user->tickets()->count(),
            'nouveau' => $user->tickets()->where('statut', TicketStatus::NOUVEAU)->count(),
            'en_cours' => $user->tickets()->where('statut', TicketStatus::EN_COURS)->count(),
            'resolu' => $user->tickets()->where('statut', TicketStatus::RESOLU)->count(),
        ];

        return Inertia::render('User/Dashboard', [
            'user' => $user->load('uniteOrganisationnelle'),
            'tickets' => $tickets,
            'ticketStats' => $ticketStats,
        ]);
    }
}