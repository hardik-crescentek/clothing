<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PurchaseItem;
use App\Purchase;
use App\Audit;
use Auth;
use Carbon\Carbon;
use DB;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [];
        $search = $request->search;
        // $where[] = ['status', '==', 0 ];
        if($search != ''){
            $where[] = ['barcode', $search];
        }
        $audit = new Audit;
        $audit = $audit->with('customer')->where($where)->orderBy('id','DESC');
        $audit = $audit->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        // echo "<pre>"; print_r($audit->toArray()); die();
        return view('audit/index',compact('audit'));
    }
}
