<?php

namespace App;

use App\Casts\Colors;
use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

class Club extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'colors', 'locale'];

    protected $casts = [
        'colors' => Colors::class,
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function getClubColorsAttribute()
    {
        return $this->colors;
        $colors = json_decode($this->colors);

        if (!isset($colors[2])) {
            $colors[2] = ($colors[1] == '#FFFFFF') ? '#000000' : '#FFFFFF';
        }

        return $colors;
    }

    public function homegames()
    {
        return $this->hasMany(TournamentGame::class, 'hometeam_id', 'id');
    }

    public function awaygames()
    {
        return $this->hasMany(TournamentGame::class, 'awayteam_id', 'id');
    }

    public function manager()
    {
        return $this->hasOneThrough(User::class, ManagerContract::class, 'club_id', 'id', 'id', 'user_id');
    }

    public function tournament()
    {
        /*$season = Season::whereDate('start_time', '<=', date('Y-m-d'))
            ->whereDate('end_time', '>=', date('Y-m-d'))
            ->first();
*/
        return $this->hasOneThrough(Tournament::class, TournamentParticipant::class, 'club_id', 'id', 'id', 'tournament_id');
    }

    public function arenas()
    {
        return $this->hasMany(Arena::class);
    }

    public function players($type = [])
    {
        $players = $this->belongsToMany(Person::class, 'player_contracts', 'club_id', 'person_id')
            // Only get contracts that are currently valid
            ->whereDate('from', '<', date('Y-m-d'))
            ->whereDate('until', '>', date('Y-m-d'));

        if (count($type) > 0) {
            $players->whereIn('type', $type);
        }

        return $players;
    }
}
