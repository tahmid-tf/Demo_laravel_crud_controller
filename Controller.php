<?php

namespace App\Http\Controllers;

use App\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trade_page_images = Trade::all();
        return view('admin.trade.sliders.index', compact('trade_page_images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.trade.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = \request()->validate([
            'content' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png',
        ]);

        if (request('image')) {
            $inputs['image'] = \request('image')->store('images');
        }

        Trade::create($inputs);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Trade $trade
     * @return \Illuminate\Http\Response
     */
    public function show(Trade $trade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Trade $trade
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trade_page_image = Trade::find($id);
        return view('admin.trade.sliders.edit', compact('trade_page_image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Trade $trade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $trade = Trade::find($id);

        $inputs = \request()->validate([
            'content' => 'required'
        ]);

        if (request('image')) {
            $inputs['image'] = \request('image')->store('images');
        } else {
            $inputs['image'] = $trade->image;
        }

        $trade->update($inputs);
        return redirect()->route("trades.index");


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Trade $trade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trade $trade)
    {

        if (Storage::disk('public')->exists($trade->image)) {
            $image = 'storage/' . $trade->image;
            unlink($image);
        }

        $trade->delete();

        return redirect()->route('trades.index');
    }
}
