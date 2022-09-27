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

        $latesResult = Result::latest('created_at')->first();
        $result['period'] = $latesResult->period + 1;
        $result['result_number'] = '2';

        // dd($result);
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
