<?php

namespace App\Http\Controllers;

use App\Models\CurrentGame;
use App\Models\Order;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Result::latest()->paginate(10);

        return response([
            'data'  => $results,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $latesResult = Result::latest('created_at')->first();

        CurrentGame::query()->delete();

        $greenColorCount = Order::where('period', $latesResult->period)->where('value', 'green')->sum('amount');
        $redColorCount = Order::where('period', $latesResult->period)->where('value', 'red')->sum('amount');
        $voiletColorCount = Order::where('period', $latesResult->period)->where('value', 'voilet')->sum('amount');

        // get min of color sum
        $colorResultMin = min($greenColorCount, $redColorCount, $voiletColorCount);

        if($colorResultMin == $voiletColorCount)
        {
            $result['result_color'] = 'voilet';
        }
        elseif($colorResultMin == $redColorCount)
        {
            $result['result_color'] = 'red';
        }
        elseif($colorResultMin == $greenColorCount)
        {
            $result['result_color'] = 'green';
        }

        // for number reults
        if($result['result_color'] == 'green')
        {
            $zeroColorCount = Order::where('period', $latesResult->period)->where('value', '0')->sum('amount');
            $oneColorCount = Order::where('period', $latesResult->period)->where('value', '1')->sum('amount');
            $fiveColorCount = Order::where('period', $latesResult->period)->where('value', '5')->sum('amount');
            $eightColorCount = Order::where('period', $latesResult->period)->where('value', '8')->sum('amount');

            $greenNumberResultMin = min($oneColorCount, $fiveColorCount, $eightColorCount);

            if($greenNumberResultMin == $oneColorCount)
            {
                $result['result_number'] = '1';
            }
            elseif($greenNumberResultMin == $fiveColorCount)
            {
                $result['result_number'] = '5';
            }
            elseif($greenNumberResultMin == $zeroColorCount)
            {
                $result['result_number'] = '0';
            }
            elseif($greenNumberResultMin == $eightColorCount)
            {
                $result['result_number'] = '8';
            }
        }
        elseif($result['result_color'] == 'red')
        {
            $twoColorCount = Order::where('period', $latesResult->period)->where('value', '2')->sum('amount');
            $fourColorCount = Order::where('period', $latesResult->period)->where('value', '4')->sum('amount');
            $sevenColorCount = Order::where('period', $latesResult->period)->where('value', '7')->sum('amount');

            $greenNumberResultMin = min($twoColorCount, $fourColorCount, $sevenColorCount);

            if($greenNumberResultMin == $twoColorCount)
            {
                $result['result_number'] = '2';
            }
            elseif($greenNumberResultMin == $fourColorCount)
            {
                $result['result_number'] = '4';
            }
            elseif($greenNumberResultMin == $sevenColorCount)
            {
                $result['result_number'] = '7';
            }
        }
        elseif($result['result_color'] == 'voilet')
        {
            $threeColorCount = Order::where('period', $latesResult->period)->where('value', '3')->sum('amount');
            $sixColorCount = Order::where('period', $latesResult->period)->where('value', '6')->sum('amount');
            $nineColorCount = Order::where('period', $latesResult->period)->where('value', '9')->sum('amount');

            $greenNumberResultMin = min($threeColorCount, $sixColorCount, $nineColorCount);

            if($greenNumberResultMin == $threeColorCount)
            {
                $result['result_number'] = '3';
            }
            elseif($greenNumberResultMin == $sixColorCount)
            {
                $result['result_number'] = '6';
            }
            elseif($greenNumberResultMin == $nineColorCount)
            {
                $result['result_number'] = '9';
            }
        }

        $result['period'] = $latesResult->period + 1;

        // store in db
        Result::create($result);

        $currentGame['period'] = $result['period'] + 1;
        $currentGame['expired_time'] = now()->addMinutes(3);

        $currentGame = CurrentGame::create($currentGame);

        $currentGame->expired_time = date('M d, Y H:i:s', strtotime($currentGame->expired_time));
        
        return response([
            'message'           => 'Result Updated Successfully',
            'data'              => $result,
            'current_game'      => $currentGame,
        ]);
    }

    // get currentgame functions
    public function getCurrentGame(Request $request)
    {
        $currentGame = CurrentGame::first();
        // dd($currentGame->expired_time);

        $currentGame->expired_time = date('M d, Y H:i:s', strtotime($currentGame->expired_time));

        return response([
            'message'           => 'Current game data successfully get',
            'current_game'      => $currentGame,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $result)
    {
        //
    }
}
