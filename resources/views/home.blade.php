@extends('layouts.app')

@section('content')
    <div class="container">

        @if ($ongoing->count())
            <h1>{{ __('Ongoing games') }}</h1>
            <div class="row">
                <div class="col-xs-6">
                    <table class="table">
                        @foreach ($ongoing->take(5) as $game)
                            <tr>
                                <td>
                                    @include('tournaments.partials.tournamentname', ['group' => $game->group])
                                </td>
                                <td>
                                    <a href="{{ link_route('show_game', ['game' => $game]) }}">{!! $game->GameStatus  !!}</a>
                                </td>
                                <td>
                                    @include('clubs.partials.clubname', ['club' => $game->hometeam])
                                </td>
                                <td>
                                    <a href="{{ link_route('show_game', ['game' => $game]) }}">{{ $game->hometeam_score }}
                                        -{{ $game->awayteam_score }}</a></td>
                                <td>
                                    @include('clubs.partials.clubname', ['club' => $game->awayteam])
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-xs-4">
                    <table class="table">
                        @foreach ($ongoing->slice(5)->take(5) as $game)
                            <tr>
                                <td>
                                    @include('tournaments.partials.tournamentname', ['group' => $game->group])
                                </td>
                                <td>
                                    <a href="{{ link_route('show_game', ['game' => $game]) }}">{!! $game->GameStatus  !!}</a>
                                </td>
                                <td>
                                    @include('clubs.partials.clubname', ['club' => $game->hometeam])
                                </td>
                                <td>
                                    <a href="{{ link_route('show_game', ['game' => $game]) }}">{{ $game->hometeam_score }}
                                        -{{ $game->awayteam_score }}</a></td>
                                <td>
                                    @include('clubs.partials.clubname', ['club' => $game->awayteam])
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif

        @if (count($tournaments) > 0)
            @foreach($tournaments as $nationality => $leagues)
                <h2>{{ $nationality }}</h2>
                <ul>
                    @foreach($leagues as $league)
                        <li>{{ $league->name }}</li>
                    @endforeach
                </ul>
            @endforeach
        @endif

        @auth
            @if(!auth()->user()->club)
                @include('clubs.partials.available-list')
            @endif

            @if (auth()->user()->isAdmin())
                <div class="row">

                    <div class="col-sm-6">
                        <a href="{{ link_route('create_player') }}" class="btn btn-primary">{{ __('New Manager') }}</a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ link_route('list_players') }}" class="btn btn-primary">{{ __('Managers') }}</a>
                    </div>

                </div>
                <div class="row">

                    <div class="col-sm-6">
                        <a href="{{ link_route('create_federation') }}"
                           class="btn btn-primary">{{ __('New Federation') }}</a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ link_route('create_tournament') }}"
                           class="btn btn-primary">{{ __('New Tournament') }}</a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ link_route('list_tournaments') }}"
                           class="btn btn-primary">{{ __('Tournaments') }}</a>
                    </div>

                </div>
            @endif
        @endauth
    </div>
@endsection
