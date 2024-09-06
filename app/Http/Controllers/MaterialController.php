<?php

namespace App\Http\Controllers;

use App\Material;
use Illuminate\Http\Request;
use App\Category;
use App\Color;
use App\Settings;
use App\Supplier;
use App\Utils\Util;
use Illuminate\Support\Str;
use Validator;
use File;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $materials = Material::with('category', 'color')->orderBy('id','DESC');

        $article_no = ['' => "Select Article No"];
        $article_no += Material::active()->orderBy('article_no','ASC')->pluck('article_no', 'article_no')->all();

        if ($request->ajax()) {
            $article=$request->article;
            if($article!=''){
                $materials = $materials->where('article_no', $article);
                if ($request->has('specific_page') && $request->specific_page == 'materials_page') {
                    $colors = ['' => "All Color"];
                    $colors += Material::active()->where('article_no', $article)->get()->pluck('color_code', 'color_no')->all();
                } else {
                    $colors = Material::active()->where('article_no', $article)->get()->pluck('color_code', 'color_no')->all();
                }

                return response()->json($colors, 200);
            }
        }
        $colors = ['' => "All Color"];
        $colors += Material::active()->get()->pluck('color_code','color_no')->all();

        //category search
        $category_id = $request->category_id;
        $query_param = [];
        if ($category_id!=0) {
            $materials = $materials->where('category_id', $category_id);
            $query_param['category_id'] = $category_id;
        }

        //article_no search
        $article=$request->search_article;
        if($article!=''){
            $materials = $materials->where('article_no', $article);
            $colors = ['' => "All Color"];
            $colors += Material::active()->where('article_no', $article)->get()->pluck('color_code', 'color_no')->all();
            $query_param['article_no'] = $article;
        }

        //color search
        $color=$request->color;
        if($color!=''){
            $materials = $materials->where('color_no', $color);
            $query_param['color'] = $color;
        }

        //text search in [name]
        // $search = $request->search;
        // if ($search!='') {
        //     // $materials = $materials->where('name', 'LIKE', "%{$search}%")->where('color', 'LIKE', "%{$search}%");
        //     $materials=$materials->where(function ($query) use ($search) {
        //         return $query->orWhere('name', 'LIKE', "%{$search}%");
        //     });
        //     $query_param['search'] = $search;
        // }

        $materials = $materials->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        if (!empty($query_param)) {
            $materials->appends($query_param);
        }

        $categories = [0 => "Select Category"];
        $categories += Category::active()->pluck('name', 'id')->all();

        // Group materials by article_no
        $groupedMaterials = $materials->groupBy('article_no');

        $data = [
                    'groupedMaterials' => $groupedMaterials,
                    'materials' => $materials,
                    'categories' => $categories,
                    'category_id' => $category_id,
                    'article_no' => $article_no,
                    'colors' => $colors,
                    'article' => $article,
                    'color' => $color
                ];
        // With Search => $data = ['materials' => $materials, 'categories' => $categories, 'category_id' => $category_id, 'search' => $search,'article_no' => $article_no,'colors' => $colors,'article' => $article,'color' => $color];
        return view('materials.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = [''=>"Select Category"];
        $categories += Category::active()->pluck('name', 'id')->all();
        $colors = Color::active()->pluck('name', 'id')->all();
        $suppliers = [''=>"Select Supplier"];
        $suppliers += Supplier::pluck('name', 'id')->all();
        $settings = Settings::where('id',1)->first();

        return view('materials.create', compact('categories', 'colors','settings','suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd(request()->all());
        $item=count($request->input('color_no'));
        $this->validate($request, [
            'name'        => 'required',
            'category_id' => 'required',
            'width_cm'       => 'required',
            'width_inch'      => 'required',
            // 'selvage'  => 'required',
            // 'construction' => 'required',
            'article_no'  => 'required|unique:materials',
            // 'image'    => 'required|image|mimes:png,jpg,jpeg|max:' . config('constants.image_size_limit'),
        ]);

        for($i=0;$i<$item;$i++){
            // $i=0;
        // foreach($request->image as $image){
            if(isset($request->image[$i])){
                if ($request->image[$i]->getSize() <= config('constants.image_size_limit')) {
                    $new_file_name = Util::generateFileName() . '.' . $request->image[$i]->getClientOriginalExtension();
                    $img[$i] = $request->image[$i]->storeAs(config('constants.material_img_path'), $new_file_name);
                }
            }elseif(isset($request->image_binary[$i]) && !empty($request->image_binary[$i])){
                $imageName = Str::random() . ".jpg";
                $baseFromJavascript = $request->image_binary[$i];
                $base_to_php = explode(',', $baseFromJavascript);
                $originalImage = base64_decode($base_to_php[1]);
                File::put(public_path('uploads/').config('constants.material_img_path'). '/' . $imageName, $originalImage);
                $img[$i] = config('constants.material_img_path'). '/'  . $imageName;
            }
            // $img = Util::uploadFile($request, $image, config('constants.material_img_path'));
            if(isset($img[$i])){
                Util::genrateThumb($img[$i]);
            }

            $data = [
                        'name'          => $request->name,
                        'category_id'   => $request->category_id,
                        'supplier_id'   => $request->supplier_id,
                        'made_in'       => $request->made_in,
                        'currency'      => $request->currency,
                        'price'         => $request->price,
                        'article_no'    => $request->article_no,
                        'barcode'       => $this->generateCode($request->article_no),
                        'roll'          => $request->roll,
                        'cut_wholesale' => $request->cut_wholesale,
                        'retail'        => $request->retail,
                        'width_inch'    => $request->width_inch,
                        'width_cm'      => $request->width_cm,
                        'weight_gsm'    => $request->weight_gsm,
                        'weight_per_mtr'=> $request->weight_per_mtr,
                        'weight_per_yard'=> $request->weight_per_yard,
                        'selvage'       => $request->selvage,
                        'construction'  => $request->construction,
                        'description'   => $request->description,
                        'color_no'      => $request->color_no[$i],
                        'color'         => Str::ucfirst($request->color[$i]),
                        'width'         => $request->width,
                        'weight'        => $request->weight,
                        'min_alert_qty' => $request->min_alert_qty[$i],
                        'image'         => isset($img[$i]) ? $img[$i] : null,
                        // 'wholesale_price' => $request->wholesale_price,
                        // 'retail_price'    => $request->retail_price,
                        // 'sample_price'    => $request->sample_price,
                        'unit_purchased_in' => $request->unit_purchased_in,
                    ];
                    // dd($data);
            Material::create($data);
            // $i++;
        }

        return redirect()->route('materials.index')->with('success', 'Material created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        $categories = [''=>"Select Category"];
        $categories += Category::active()->pluck('name', 'id')->all();
        $colors = Color::active()->pluck('name', 'id')->all();
        $suppliers = [''=>"Select Supplier"];
        $suppliers += Supplier::pluck('name', 'id')->all();
        return view('materials.edit', compact('material', 'categories', 'colors','suppliers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {

        $this->validate($request, [
            'name'         => 'required',
            'barcode'      => 'required',
            'category_id'  => 'required',
            'color'        => 'required',
            'article_no'   => 'required',
            'width_cm'       => 'required',
            'width_inch'      => 'required',
            // 'selvage'  => 'required',
            // 'construction' => 'required',
            // 'image'    => 'required|image|mimes:png,jpg,jpeg|max:' . config('constants.image_size_limit'),
        ]);

        $data = [
                    'name'         => $request->name,
                    'category_id'   => $request->category_id,
                    'supplier_id'   => $request->supplier_id,
                    'made_in'       => $request->made_in,
                    'currency'      => $request->currency,
                    'price'         => $request->price,
                    'article_no'    => $request->article_no,
                    'barcode'      => $request->barcode,
                    'roll'          => $request->roll,
                    'cut_wholesale' => $request->cut_wholesale,
                    'retail'        => $request->retail,
                    'width_inch'    => $request->width_inch,
                    'width_cm'      => $request->width_cm,
                    'weight_gsm'    => $request->weight_gsm,
                    'weight_per_mtr'=> $request->weight_per_mtr,
                    'weight_per_yard'=> $request->weight_per_yard,
                    'selvage'      => $request->selvage,
                    'construction' => $request->construction,
                    'description'  => $request->description,
                    'color'        => $request->color,
                    // 'weight'       => $request->weight,
                    'status'       => ($request->status) ? true : false,
                    'min_alert_qty' => $request->min_alert_qty,
                    // 'wholesale_price' => $request->wholesale_price,
                    // 'retail_price'    => $request->retail_price,
                    // 'sample_price'    => $request->sample_price,
                    'unit_purchased_in' => $request->unit_purchased_in,
                ];

        if ($request->image) {
            $image = Util::uploadFile($request, 'image', config('constants.material_img_path'));
            Util::genrateThumb($image);
            $data['image'] = $image;
        }elseif(isset($request->image_binary) && !empty($request->image_binary)){
            $imageName = Str::random() . ".jpg";
            $baseFromJavascript = $request->image_binary;
            $base_to_php = explode(',', $baseFromJavascript);
            $originalImage = base64_decode($base_to_php[1]);
            File::put(public_path('uploads/').config('constants.material_img_path') .'/'. $imageName, $originalImage);
            $data['image'] = config('constants.material_img_path') .'/'. $imageName;
            Util::genrateThumb($data['image']);
        }
        $material->fill($data);
        $material->save();

        return redirect()->route('materials.index')->with('success', 'Material updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Material successfully deleted.');
    }

    public function generateCode($article_no)
    {
        // return Util::generateID();
        return Util::gen_new_barcode_id($article_no);
    }


    public function getItem($id)
    {
        $material = Material::find($id);
        return response()->json($material, 200);
    }

    /**
     * search for autocomplete
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $search = $request->term;
        $article_no = $request->article_no;
        $color = Str::ucfirst($request->color);
        if($search!=''){
            $materials = Material::select('id', 'name', 'barcode','color','article_no','wholesale_price', 'retail_price', 'sample_price')
                        ->where('barcode', 'LIKE', "%{$search}%")
                        ->orderBy('name', 'asc')
                        ->limit(10)
                        ->get();
        }
        if($article_no!=''){
            $materials = Material::select('id', 'name', 'barcode','color','article_no','wholesale_price', 'retail_price', 'sample_price')
                        ->where('article_no', 'LIKE', "%{$article_no}%")
                        ->orderBy('name', 'asc')
                        ->limit(10)
                        ->get();
        }
        if($color!=''){
            $materials = Material::select('id', 'name', 'barcode','color','article_no','wholesale_price', 'retail_price', 'sample_price')
                        ->where('color', 'LIKE', "%{$color}%")
                        ->orderBy('name', 'asc')
                        ->limit(10)
                        ->get();
        }
        return response()->json($materials, 200);
    }

    public function getSelectedPrice(Request $request)
    {
        $id = $request->id;
        $option = $request->val;
        if ($option == "R") {
            $column = "retail_price";
        }elseif($option == "W"){
            $column = "wholesale_price";
        }else{
            $column = "sample_price";
        }
        $materialPrice = Material::select($column)->where('id',$id)->get();
        $price = $materialPrice->first()->$column;
        return response()->json(['Price'=>$price], 200);
    }
}
