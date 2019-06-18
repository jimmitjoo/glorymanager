<?php

namespace App\Http\Controllers;

use App\Club;
use App\Lineup;
use Illuminate\Http\Request;

class LineupController extends Controller
{
    public function edit(Club $club, $squad)
    {
        if (!$lineup = Lineup::where('club_id', $club->id)->where('team', $squad)->first()) {
            $lineup = Lineup::create([
                'club_id' => $club->id,
                'team' => $squad,
            ]);
        }

        $this->authorize('view', $lineup);

        $type = getContractType($squad);
        $players = $club->players($type)->get();

        return view('lineups.edit')->with(['club' => $club, 'lineup' => $lineup, 'players' => $players]);
    }

    public function update(Lineup $lineup)
    {
        $this->authorize('update', $lineup);

        if ($lineup->update(\request()->all())) {
            return response('Success!');
        }
    }
}