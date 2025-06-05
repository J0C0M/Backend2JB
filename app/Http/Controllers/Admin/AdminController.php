<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->isAdmin()) {
                $request->session()->regenerate();
                Auth::user()->update(['last_login_at' => now()]);
                return redirect()->intended('/admin/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have admin privileges.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_games' => Game::count(),
            'games_today' => Game::whereDate('created_at', today())->count(),
            'active_users' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
        ];

        $recentUsers = User::latest()->limit(5)->get();
        $recentGames = Game::with(['player1', 'player2', 'winner'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentGames'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function games()
    {
        $games = Game::with(['player1', 'player2', 'winner'])
            ->latest()
            ->paginate(20);
        return view('admin.games', compact('games'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->isAdmin() && User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot delete the last admin user.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin() && User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot remove admin privileges from the last admin user.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $message = $user->is_admin ? 'Admin privileges granted.' : 'Admin privileges removed.';
        return back()->with('success', $message);
    }
}
