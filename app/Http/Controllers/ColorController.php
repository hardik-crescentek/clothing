<?php

namespace App\Http\Controllers;

use App\Color;
use App\Material;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $colors = Color::orderBy('id','DESC')->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        return view('color.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('color.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->name = preg_replace('/\s+/', ' ', $request->name);
        $this->validate($request, [
            'name' => ['required'],
            'code' => ['required'],
        ]);

        $data = [
            'name'   => $request->name,
            'status' => ($request->status) ? true : false,
            'code'   => $request->code
        ];
        Color::create($data);

        return redirect()->route('color.index')->with('success', 'Color created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect()->route('color.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function edit(Color $color)
    {
        return view('color.edit', ['color' => $color]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Color $color)
    {
        $request->name = preg_replace('/\s+/', ' ', $request->name);
        $this->validate($request, [
            'name' => ['required'],
            'code' => ['required'],
        ]);
        $data = [
            'name'   => $request->name,
            'status' => ($request->status) ? true : false,
            'code'   => $request->code
        ];
        $color->update($data);
        return redirect()->route('color.index')->with('success', 'Color updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('color.index')->with('success', 'Color successfully deleted.');
    }

    public function checkColor(Request $request)
    {
        $material=Material::where('color_id','=',$request->input('color_id'))->get();
        if($material->count()!=0){
            return response()->json([
                'status'=>false,
                'message'=>"This color id used for some material,So it can not delete",
            ]);
        }      
        else{
            return response()->json([
                'status'=>true,
                'message'=>"Are you sure want to delete this color?",
            ]);
        }  
    }
}
