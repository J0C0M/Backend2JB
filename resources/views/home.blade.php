@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Wordle Game</h1>
            <p class="text-xl text-gray-600 mb-8">Guess the 5-letter word in 6 tries!</p>

            @guest
                <div class="space-x-4">
                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 text-lg font-semibold">
                        Start Playing
                    </a>
                    <a href="{{ route('login') }}" class="border border-green-600 text-green-600 px-6 py-3 rounded-lg hover:bg-green-50 text-lg font-semibold">
                        Login
                    </a>
                </div>
            @else
                <a href="{{ route('dashboard') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 text-lg font-semibold">
                    Play Now
                </a>
            @endguest
        </div>

        <!-- Wordle Game Preview -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
            <h2 class="text-2xl font-bold text-center mb-8">Game Preview</h2>

            <!-- Game Board -->
            <div class="max-w-sm mx-auto">
                <div class="grid grid-rows-6 gap-2">
                    <!-- Row 1 - Example guess -->
                    <div class="grid grid-cols-5 gap-2">
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-yellow-200 border-yellow-400">
                            S
                        </div>
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-gray-200 border-gray-400">
                            T
                        </div>
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-green-200 border-green-400">
                            A
                        </div>
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-gray-200 border-gray-400">
                            R
                        </div>
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-yellow-200 border-yellow-400">
                            E
                        </div>
                    </div>

                    <!-- Row 2 - Example guess -->
                    <div class="grid grid-cols-5 gap-2">
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-green-200 border-green-400">
                            P
                        </div>
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-green-200 border-green-400">
                            L
                        </div>
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-green-200 border-green-400">
                            A
                        </div>
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-green-200 border-green-400">
                            N
                        </div>
                        <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold bg-green-200 border-green-400">
                            E
                        </div>
                    </div>

                    <!-- Empty rows -->
                    @for($i = 0; $i < 4; $i++)
                        <div class="grid grid-cols-5 gap-2">
                            @for($j = 0; $j < 5; $j++)
                                <div class="w-12 h-12 border-2 border-gray-300 rounded flex items-center justify-center text-xl font-bold"></div>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Game Rules -->
            <div class="mt-8 text-center">
                <h3 class="text-lg font-semibold mb-4">How to Play</h3>
                <div class="grid md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-green-200 border border-green-400 rounded"></div>
                        <span>Green = Correct letter in correct position</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-yellow-200 border border-yellow-400 rounded"></div>
                        <span>Yellow = Correct letter in wrong position</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-gray-200 border border-gray-400 rounded"></div>
                        <span>Gray = Letter not in word</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaderboards -->
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Daily Leaders -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-center mb-4 text-blue-600">Today's Leaders</h3>
                @if($dailyLeaders && $dailyLeaders->count() > 0)
                    <div class="space-y-3">
                        @foreach($dailyLeaders as $index => $leader)
                            <div class="flex items-center justify-between p-2 {{ $index === 0 ? 'bg-yellow-50 border border-yellow-200 rounded' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-lg">{{ $index + 1 }}.</span>
                                    <span class="font-semibold">{{ $leader->username }}</span>
                                </div>
                                <span class="text-blue-600 font-bold">{{ $leader->daily_wins }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">No games played today</p>
                @endif
            </div>

                <!-- Weekly Leaders -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-center mb-4 text-green-600">This Week</h3>
                @if($weeklyLeaders && $weeklyLeaders->count() > 0)
                    <div class="space-y-3">
                        @foreach($weeklyLeaders as $index => $leader)
                            <div class="flex items-center justify-between p-2 {{ $index === 0 ? 'bg-yellow-50 border border-yellow-200 rounded' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-lg">{{ $index + 1 }}.</span>
                                    <span class="font-semibold">{{ $leader->username }}</span>
                                </div>
                                <span class="text-green-600 font-bold">{{ $leader->weekly_wins }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">No games played this week</p>
                @endif
            </div>

            <!-- All Time Leaders -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-center mb-4 text-purple-600">All Time</h3>
                @if($allTimeLeaders && $allTimeLeaders->count() > 0)
                    <div class="space-y-3">
                        @foreach($allTimeLeaders as $index => $leader)
                            <div class="flex items-center justify-between p-2 {{ $index === 0 ? 'bg-yellow-50 border border-yellow-200 rounded' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-lg">{{ $index + 1 }}.</span>
                                    <span class="font-semibold">{{ $leader->username }}</span>
                                </div>
                                <span class="text-purple-600 font-bold">{{ $leader->games_won }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">No games played yet</p>
                @endif
            </div>
        </div>
    </div>
@endsection
