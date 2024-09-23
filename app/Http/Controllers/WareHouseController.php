<?php

namespace App\Http\Controllers;

use App\WareHouse;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $warehouses = WareHouse::all();
        return view('warehouse.index',compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        WareHouse::create($validatedData);

        return redirect()->route('warehouse.index')->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WareHouse  $wareHouse
     * @return \Illuminate\Http\Response
     */
    public function show(WareHouse $wareHouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WareHouse  $wareHouse
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warehouse = WareHouse::findOrFail($id);
        return view('warehouse.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WareHouse  $wareHouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $warehouse = WareHouse::findOrFail($id);
        $warehouse->update($validatedData);

        return redirect()->route('warehouse.index')->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WareHouse  $wareHouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $warehouse = WareHouse::findOrFail($id);
        $warehouse->delete();

        return redirect()->route('warehouse.index')->with('success', 'Warehouse deleted successfully.');
    }
}
