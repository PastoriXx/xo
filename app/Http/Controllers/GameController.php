<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Game;

class GameController extends Controller
{
    public function index()
    {
		$gamingMap = Game::createGamingMap();

    	// $gamingMap = Game::updateWeightsOfElements($gamingMap);

    	// dd($gamingMap);

    	return view('game.index', compact('gamingMap'));
    }


}
