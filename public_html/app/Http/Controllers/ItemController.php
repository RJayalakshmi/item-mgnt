<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller {

    /**
     * User can Add new item, view added items and swap the items
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        // Fetch all items
        $items = Item::orderBy('updated_at', 'desc')->get();
        // Format the items based on the position
        $item_by_position = $items->groupBy([
            'position'
                ], $preserveKeys = true);
        //print_r($item_by_position);
        if ($request->is('api/*')) {
            return response()->json($item_by_position, 200);
        } else {
            return view('items.index')->with('item_by_position', $item_by_position)->with('data', json_decode($item_by_position));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->has('name')) {
            try {
                $item = Item::whereRaw( 'LOWER(`name`) LIKE ?', [strtolower($request->name)] )->first();
                //var_dump($item);
                if (!$item) {
                    $newItem = new Item();
                    
                    $newItem->name = $request->name;
                    
                    $newItem->save();
                    
                    return response()->json(['error' => 0, 'message' => 'Item has been added successfully', 'data' => $this->all()], 200);
                } else {
                    return response()->json(['error' => 1, 'message' => 'Oops! Duplicate item'], 200);
                }
            } catch (Exception $ex) {
                return response()->json(['error' => 1, 'message' => $ex->getMessage()], 200);  
            }
        } else {
            return response()->json(['error' => 1, 'message' => 'Item name missing'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @required data: id String
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $item = Item::find($id);
        try {
            if ($item) {
                $item->position = $request->position;
                $item->save();

                return response()->json(['error' => 0, 'message' => 'Item has been updated successfully', 'data' => $this->all()], 200);
            } else {
                return response()->json(['error' => 1, 'message' => 'Item is not found'], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => 1, 'message' => $ex->getMessage()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $item = Item::find($id);
        try {
            if ($item) {
                $item->delete();

                return response()->json(['error' => 0, 'message' => 'Item has been deleted successfully', 'data' => $this->all()], 200);
            } else {
                return response()->json(['error' => 1, 'message' => 'Item is not found'], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => 1, 'message' => $ex->getMessage()], 200);
        }
    }

    /**
     * Get all items
     * 
     * @required data:none
     * @optional data: none
     * 
     * @return \Illuminate\Http\Response
     */
    private function all() {
        // Fetch all items
        $items = Item::orderBy('updated_at', 'desc')->get();
        // Format the items based on the position
        $item_by_position = $items->groupBy([
            'position'
                ], $preserveKeys = true);

        return $item_by_position;
    }

}
