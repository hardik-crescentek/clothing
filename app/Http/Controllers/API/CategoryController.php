<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    /**
     * Returns list of Categories. 
     * Request object may have optional query string parameters 
     * @Queryparam  $q search in name 
     * @Queryparam  $perPage number of items per page. set false to all recourds. Default false
     *
     * @param Request $request
     * @return Response
     */

    public function index(Request $request)
    {
        
        $objData = new Category;
        if ($q = $request->get('q')) {
            $objData->where('name', 'LIKE', "%{$q}%");
        }
        $perPage = $request->get('perPage');
        $perPage = (!empty($perPage) && $perPage != 'all') ? (int) $perPage : 0;
        if ($perPage) {
            $objData = $objData->paginate($perPage)->appends(request()->query());
        } else {
            $objData = $objData->get();
        }
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $objData
        ];
        return response()->json($response, 200);
    }

    /**
     * Returns Category Details. 
     *
     * @param Int $id Category id
     * @return Response
     */
    public function single($id)
    {
        $objData = Category::findOrFail($id);
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $objData
        ];
        return response()->json($response, 200);
    }
}
