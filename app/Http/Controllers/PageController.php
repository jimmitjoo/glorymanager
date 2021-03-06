<?php

namespace App\Http\Controllers;

use App\Club;
use App\Tournament;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($locale, $slug)
    {
        if ($tournament = Tournament::where('slug', $slug)->first()) {
            $tournamentController = new TournamentController();
            return $tournamentController->show($locale, $tournament);
        } elseif ($club = Club::with(['homegames', 'awaygames'])->where('slug', $slug)->first()) {
            $clubController = new ClubController();
            return $clubController->show($locale, $club);
        }
    }
}
