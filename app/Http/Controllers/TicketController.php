<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\TicketComment;
use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('client', 'technicien');

        if ($request->user()->isClient()) {
            $query->where('client_id', $request->user()->id);
        } elseif ($request->user()->isTechnicien()) {
            $query->where('technicien_id', $request->user()->id);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($statut = $request->statut) {
            $query->where('statut', $statut);
        }

        if ($priorite = $request->priorite) {
            $query->where('priorite', $priorite);
        }

        return Inertia::render('Ticket/Index', [
            'tickets' => $query->latest()->get(),
            'filters' => $request->only(['search', 'statut', 'priorite']),
            'techniciens' => $request->user()->isAdmin()
                ? User::where('role', 'technicien')->where('actif', true)->get()
                : [],
        ]);
    }

    public function create()
    {
        if (request()->user()->isTechnicien()) {
            return redirect()->route('tickets.index')->with('error', 'Vous n\'êtes pas autorisé à créer des tickets.');
        }
        return Inertia::render('Ticket/Create');
    }

    public function store(Request $request)
    {
        if ($request->user()->isTechnicien()) {
            return redirect()->route('tickets.index')->with('error', 'Vous n\'êtes pas autorisé à créer des tickets.');
        }

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'priorite' => 'required|in:Critique,Haute,Moyenne,Basse',
            'categorie' => 'required|in:Reseau,Logiciel,Materiel,Electrique,Autre',
        ]);

        if ($request->hasFile('piece_jointe')) {
            $data['piece_jointe'] = $request->file('piece_jointe')->store('uploads', 'public');
        }

        $count = Ticket::count();
        $data['ref'] = 'TK-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        $data['client_id'] = $request->user()->id;
        $data['statut'] = 'Ouvert';

        $ticket = Ticket::create($data);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'action' => 'Ticket créé par ' . $request->user()->name,
        ]);

        AppNotification::create([
            'user_id' => 1,
            'title' => 'Nouveau ticket',
            'message' => "Le ticket {$ticket->ref} a été créé par {$request->user()->name}.",
            'ticket_ref' => $ticket->ref,
        ]);

        // Auto-assign
        $tech = User::where('role', 'technicien')
            ->where('specialite', $data['categorie'])
            ->where('actif', true)
            ->first();

        if ($tech) {
            $ticket->update(['technicien_id' => $tech->id]);
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => 1,
                'action' => "Ticket assigné automatiquement à {$tech->name}",
            ]);
            AppNotification::create([
                'user_id' => $tech->id,
                'title' => 'Nouveau ticket assigné',
                'message' => "Le ticket {$ticket->ref} vous a été assigné.",
                'ticket_ref' => $ticket->ref,
            ]);
        }

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket créé avec succès.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('client', 'technicien', 'history.user', 'comments.user', 'report', 'evaluation');

        return Inertia::render('Ticket/Show', [
            'ticket' => $ticket,
            'techniciens' => request()->user()->isAdmin()
                ? User::where('role', 'technicien')->where('actif', true)->get()
                : [],
        ]);
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $data = $request->validate(['statut' => 'required|in:Ouvert,En cours,Rapport en rédaction,Résolu,Fermé']);

        $ticket->update($data);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'action' => "Statut passé à \"{$data['statut']}\" par {$request->user()->name}",
        ]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $data = $request->validate(['technicien_id' => 'required|exists:users,id']);
        $tech = User::find($data['technicien_id']);

        $ticket->update(['technicien_id' => $tech->id]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'action' => "Ticket réaffecté à {$tech->name}",
        ]);

        AppNotification::create([
            'user_id' => $tech->id,
            'title' => 'Ticket réaffecté',
            'message' => "Le ticket {$ticket->ref} vous a été réaffecté.",
            'ticket_ref' => $ticket->ref,
        ]);

        return back()->with('success', 'Technicien assigné.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'text' => 'required|string',
            'internal' => 'boolean',
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'text' => $data['text'],
            'internal' => $data['internal'] ?? false,
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'action' => "Commentaire ajouté par {$request->user()->name}",
        ]);

        return back()->with('success', 'Commentaire ajouté.');
    }

    public function createReport(Ticket $ticket)
    {
        return Inertia::render('Ticket/ReportForm', [
            'ticket' => $ticket->load('client', 'technicien'),
        ]);
    }
}
