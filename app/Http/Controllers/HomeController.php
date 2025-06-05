<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Get leaderboard data
        $dailyLeaders = $this->getDailyLeaders();
        $weeklyLeaders = $this->getWeeklyLeaders();
        $allTimeLeaders = $this->getAllTimeLeaders();

        return view('home', compact('dailyLeaders', 'weeklyLeaders', 'allTimeLeaders'));
    }

    private function getDailyLeaders()
    {
        return User::select('users.name', 'users.username', 'users.avatar', DB::raw('COUNT(games.id) as daily_wins'))
            ->leftJoin('games', function($join) {
                $join->on('users.id', '=', 'games.winner_id')
                    ->whereDate('games.completed_at', today());
            })
            ->where('users.games_won', '>', 0)
            ->groupBy('users.id', 'users.name', 'users.username', 'users.avatar')
            ->orderBy('daily_wins', 'desc')
            ->limit(5)
            ->get();
    }

    private function getWeeklyLeaders()
    {
        return User::select('users.name', 'users.username', 'users.avatar', DB::raw('COUNT(games.id) as weekly_wins'))
            ->leftJoin('games', function($join) {
                $join->on('users.id', '=', 'games.winner_id')
                    ->whereBetween('games.completed_at', [now()->startOfWeek(), now()->endOfWeek()]);
            })
            ->where('users.games_won', '>', 0)
            ->groupBy('users.id', 'users.name', 'users.username', 'users.avatar')
            ->orderBy('weekly_wins', 'desc')
            ->limit(5)
            ->get();
    }

    private function getAllTimeLeaders()
    {
        return User::select('name', 'username', 'avatar', 'games_won')
            ->where('games_won', '>', 0)
            ->orderBy('games_won', 'desc')
            ->limit(5)
            ->get();
    }
}
