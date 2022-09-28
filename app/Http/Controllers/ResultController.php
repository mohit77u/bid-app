<?php

namespace App\Http\Controllers;

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
        $greenColorCount = Order::where('value', 'green')->sum('amount');
        $redColorCount = Order::where('value', 'red')->sum('amount');
        $voiletColorCount = Order::where('value', 'voilet')->sum('amount');

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
            $zeroColorCount = Order::where('value', '0')->sum('amount');
            $oneColorCount = Order::where('value', '1')->sum('amount');
            $fiveColorCount = Order::where('value', '5')->sum('amount');
            $eightColorCount = Order::where('value', '8')->sum('amount');

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
            $twoColorCount = Order::where('value', '2')->sum('amount');
            $fourColorCount = Order::where('value', '4')->sum('amount');
            $sevenColorCount = Order::where('value', '7')->sum('amount');

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
            $twoColorCount = Order::where('value', '3')->sum('amount');
            $fourColorCount = Order::where('value', '6')->sum('amount');
            $sevenColorCount = Order::where('value', '9')->sum('amount');

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

        $latesResult = Result::latest('created_at')->first();
        $result['period'] = $latesResult->period + 1;

        // store in db
        Result::create($result);

        return response([
            'message'   => 'Result Updated Successfully',
            'data'      => $result,
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
