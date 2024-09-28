<?php

namespace App\Http\Controllers;

use App\WareHouse;
use App\PurchaseItemWareHouseHistory;
use App\Purchase;
use App\PurchaseItem;
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

    public function stockLocation(){
        // Fetch unique article_no and color for the dropdowns
        $articles = PurchaseItem::select('article_no')->whereNotNull('article_no')->distinct()->get();
        $colors = PurchaseItem::select('color')->whereNotNull('article_no')->distinct()->get();
        $wareHouse = WareHouse::pluck('name', 'id')->all();

        return view('stockLocation.index', compact('articles', 'colors','wareHouse'));
    }

    public function filterStockLocation(Request $request)
    {
        // Initialize the query
        $query = PurchaseItem::query()
                                ->leftJoin('ware_houses', 'purchase_items.warehouse_id', '=', 'ware_houses.id')
                                ->select('purchase_items.*', 'ware_houses.name as current_warehouse');

        // Check if any filter is applied
        $filtersApplied = false;

        // Apply filters if they exist
        if ($request->has('article_no') && $request->article_no != '') {
            $query->where('article_no', $request->article_no);
            $filtersApplied = true;
        }

        if ($request->has('color') && $request->color != '') {
            $query->where('color', $request->color);
            $filtersApplied = true;
        }

        // If no filters are applied, return an empty array
        if (!$filtersApplied) {
            return response()->json([]);
        }

        // Fetch filtered results
        $purchaseItem = $query->orderBy('created_at', 'desc')->get();

        return response()->json($purchaseItem);
    }

    // Update a single warehouse location
    // public function updateWarehouseLocation(Request $request, $id)
    // {
    //     $purchaseItem = PurchaseItem::findOrFail($id);

    //     // Record the old warehouse
    //     $oldWarehouseId = $purchaseItem->warehouse_id;

    //     // Update the warehouse
    //     $purchaseItem->update(['warehouse_id' => $request->warehouse_id]);

    //     // Save history
    //     PurchaseItemWareHouseHistory::create([
    //         'purchase_item_id' => $purchaseItem->id,
    //         'warehouse_id' => $oldWarehouseId,
    //     ]);

    //     return response()->json(['success' => 'Warehouse location updated successfully.']);
    // }

    public function updateWarehouseLocation(Request $request, $id)
    {
        // Find the purchase item by ID or fail
        $purchaseItem = PurchaseItem::findOrFail($id);

        // Record the old warehouse
        $oldWarehouseId = $purchaseItem->warehouse_id;

        // Update the warehouse with the new ID
        $purchaseItem->update(['warehouse_id' => $request->warehouse_id]);

        // Save history, including both old and current warehouse IDs
        PurchaseItemWareHouseHistory::create([
            'purchase_item_id' => $purchaseItem->id,
            'old_warehouse_id' => $oldWarehouseId, // Make sure to have this column in your table
            'current_warehouse_id' => $request->warehouse_id, // New/current warehouse
            'changed_at' => now(), // Add the timestamp of the change
        ]);

        return response()->json(['success' => 'Warehouse location updated successfully.']);
    }

    public function updateMultipleWarehouseLocations(Request $request)
    {
        // Iterate over the IDs provided in the request
        foreach ($request->ids as $item_v) {
            // Find the purchase item by its ID or fail
            $purchaseItem = PurchaseItem::findOrFail($item_v);
            $oldWarehouseId = $purchaseItem->warehouse_id; // Record the old warehouse ID

            // Update the warehouse with the new ID
            $purchaseItem->update(['warehouse_id' => $request->new_warehouse]);

            // Save the history, including both old and new warehouse IDs
            PurchaseItemWareHouseHistory::create([
                'purchase_item_id' => $purchaseItem->id,
                'old_warehouse_id' => $oldWarehouseId, // Previous warehouse
                'current_warehouse_id' => $request->new_warehouse, // New/current warehouse
                'changed_at' => now(), // Timestamp of the change
            ]);
        }

        return response()->json(['success' => 'Warehouse locations updated successfully.']);
    }

    public function getWarehouseHistory($id)
    {
        // Fetch the current purchase item based on the provided ID
        $purchaseItem = PurchaseItem::findOrFail($id);
        
        // Fetch the warehouse history for this purchase item
        $history = PurchaseItemWareHouseHistory::where('purchase_item_id', $id)
            ->with('warehouse') // Load associated warehouse data
            ->orderBy('changed_at', 'desc')
            ->get();

        return view('warehouse.history', compact('history'));
    }
}
