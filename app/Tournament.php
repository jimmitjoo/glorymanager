<?php

namespace App;

use Carbon\Carbon;
use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;

class Tournament extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected $with = ['tournamentGroups', 'qualifications'];

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

    public function getStartDateAttribute()
    {
        $groupIds = $this->tournamentGroups()->pluck('id');
        try {
            $firstGameDate = TournamentGame::whereIn('group_id', $groupIds)->orderBy('start_time', 'asc')->firstOrFail('start_time');
        } catch (\Exception $exception) {
            $firstGameDate = new TournamentGame();
            $firstGameDate->start_time = now()->addCenturies(2);
        }

        return Carbon::createFromTimeString($firstGameDate->start_time)->format('Y-m-d');
    }

    public function getEndDateAttribute()
    {
        $groupIds = $this->tournamentGroups()->pluck('id');
        try {
            $lastGameDate = TournamentGame::whereIn('group_id', $groupIds)->orderBy('start_time', 'desc')->firstOrFail('start_time');
        } catch (\Exception $exception) {
            $firstGameDate = new TournamentGame();
            $firstGameDate->start_time = now()->addCenturies(2);
        }

        return Carbon::createFromTimeString($lastGameDate->start_time)->addMinutes(106)->format('Y-m-d');
    }

    public function getStatusAttribute()
    {
        if ($this->start_date > now()->addCenturies(1)) {
            return 'NOT_DECIDED';
        } elseif ($this->start_date > now()) {
            return 'NOT_STARTED';
        } else if ($this->end_date < now()) {
            return 'ENDED';
        }

        return 'ACTIVE';
    }


    /**
     * Relationships
     */

    public function tournamentGroups()
    {
        return $this->hasMany(TournamentGroup::class);
    }

    public function qualifications()
    {
        return $this->hasMany(TournamentQualification::class);
    }

}
