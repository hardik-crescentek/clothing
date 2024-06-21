<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Material;
use App\Category;
use App\Color;

class MaterialController extends Controller
{
    public function index(Request $request)
    {

        $where = [];
        // if ($q = $request->get('article_no')) {
        //     $where[] = ['article_no', $q];
        // }
        if ($q = $request->get('cat')) {
            $where[] = ['category_id', $q];
        }
        if ($q = $request->get('color')) {
            $where[] = ['color', $q];
        }
        if ($q = $request->get('color_no')) {
            $where[] = ['color_no', $q];
        }

        $objData = Material::where($where);
        $search = $request->get('q');
        if ($search) {
            $objData->where(function ($query) use ($search) {
                $query->where('article_no', 'like', '%' . $search . '%');
                $query->orWhere('barcode', 'like', '%' . $search . '%');
            });
        }
        if ($sort = $request->get('sortBy')) {
            if (strtolower($request->get('sortOrder')) == "desc") {
                $data = $objData->orderBy($sort, 'DESC');
                $objData = $data;
            } else {
                $data = $objData->orderBy($sort, 'ASC');
                $objData = $data;
            }
        }
        $perPage = $request->get('perPage');
        $perPage = (empty($request->article_no) && !empty($perPage) && $perPage != 'all') ? (int) $perPage : 0;
        if ($perPage) {
            $objData = $objData->paginate($perPage)->appends(request()->query());
        } else {
            $objData = $objData->get();
        }
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $objData,            
        ];

        if($request->article_no){
            $colors = Material::select('color')->where('article_no',$request->article_no)->groupBy('color')->get()->pluck('color')->toArray();
            $response['colors'] = $colors;
        }

        return response()->json($response, 200);
    }
    public function single(Request $request)
    {
        $id = $request->get('id');
        $objData = Material::with(['purchaseItems'])->find($id);
        if(!$objData){
            return response()->json([
                'status' => false,
                'message' => 'Item not found',
                'data' => null
            ], 404);
        }
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $objData,            
        ];
        $objData->available_qty = $objData->purchaseItems->sum('available_qty');
        unset($objData->purchaseItems);
        if($objData->article_no){
            $colors = Material::select('color')->where('article_no',$objData->article_no)->groupBy('color')->get()->pluck('color')->toArray();
            $response['colors'] = $colors;
        }

        return response()->json($response, 200);
    }
    public function materialByColor(Request $request)
    {
        $color = Color::where('name', '=', $request->route('color'))->pluck('id');
        return response()->json($color, 200);
        die;
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $color->get(),
        ];
        return response()->json($response, 200);
    }
}
