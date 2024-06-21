<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderItem;
use App\Material;
use App\Purchase;
use App\PurchaseItem;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
   public function salesReport(Request $request)
   {
        $filter = $request->filter;
        if ($filter) {
            $orderDetails = OrderItem::with('item','order','order.customer','order.seller')
                            ->whereNull('deleted_at')
                            ->whereHas('order',function($q){
                                $q->whereNull('deleted_at');
                            })->whereHas('order.customer',function($q){
                                $q->whereNull('deleted_at');
                            })->whereHas('order.seller',function($q){
                                $q->whereNull('deleted_at');
                            });
            if($request->to_date!='' && $request->from_date!=''){

                $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
                $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
                $orderDetails = $orderDetails->whereHas('order',function ($q) use ($from_date,$to_date){
                    $q->whereBetween('order_date',[$from_date,$to_date])->orderBy('order_date','DESC');
                });
            }

            $orderDetails= $orderDetails->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
                return view('report.salesreport',compact('orderDetails'));
        }
        return view('report.salesreport');
    }

    public function purchesReport(Request $request)
    {
        $filter = $request->filter;
        if ($filter) {
            $purches = Purchase::with('supplier')
                        ->whereNull('deleted_at')
                        ->orderBy('purchase_date','DESC');
            if($request->to_date!='' && $request->from_date!=''){
                $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
                $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
                $purches = $purches->whereBetween('purchase_date',[$from_date,$to_date]);
            }
            $purches = $purches->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
            return view('report.purchesreport', compact('purches'));
        }
        return view('report.purchesreport');
    }

    public function topSalesMaterial(Request $request)
    {
        $topMaterial = OrderItem::with('item')
                        ->whereHas('item',function($q){
                            $q->whereNull('deleted_at');
                        })
                        ->select(['item_id',DB::raw('COUNT(item_id) as material_id')])
                        ->groupBy('item_id')
                        ->orderBY('material_id','DESC')
                        ->limit(20)
                        ->get();
        return view('report.topsaledmaterial',compact('topMaterial'));
    }

    public function bestClientByType(Request $request,$type)
    {
        $type = $request->type;
        if ($type == "by-cost") {
            $orderDetails = OrderItem::with('item','order','order.customer')
                            ->whereHas('order',function ($q){
                                $q->whereNull('deleted_at');
                            })
                            ->whereHas('order.customer',function ($q){
                                $q->whereNull('deleted_at');
                            })
                            ->select(['order_id',DB::raw('meter*price as total_price')])
                            ->groupBy('order_id')
                            ->orderBy('total_price','DESC')
                            ->limit(20)->get();
            return view('report.bestclients',compact('orderDetails'));
        }elseif ($type == "by-order") {
            $orderDetailsByTopOrder = Order::with('customer')
                                      ->select(['customer_id',DB::raw('count(customer_id) as total_order')])
                                      ->groupBy('customer_id')
                                      ->orderBy('total_order','DESC')
                                      ->whereHas('customer',function ($q){
                                            $q->whereNull('deleted_at');
                                        })
                                      ->whereNull('deleted_at')
                                      ->limit(20)->get();
            return view('report.bestclients',compact('orderDetailsByTopOrder'));
        }
    }

    public function materialSendedToClient(Request $request)
    {
       if ($request->ajax()) {
           $customer_id = $request->customer_id;
           $material_name = $request->article_name;
            if ($customer_id) {
                $orders = [''=>'---Select Material---'];
                $orders += OrderItem::with('item')->whereHas('order', function($q) use ($customer_id){
                                $q->where('customer_id',$customer_id);
                            })
                            ->get()
                            ->pluck('item.name','item.name')
                            ->all();
                return response()->json($orders, 200);
            }

            if ($material_name) {
                $orders = [''=>'---Select Articel No---'];
                $orders += OrderItem::with('item')
                            ->whereHas('item', function($q) use ($material_name){
                                $q->where('name',$material_name);
                            })
                            ->get()
                            ->pluck('item.article_no','item.article_no')
                            ->all();
                return response()->json($orders, 200);
            }
       }

       $order = Order::with('customer')
                ->groupBy('customer_id')
                ->get();

       if ($request->submit) {
        $customer_id = $request->cust_name;
        $material_name = $request->article_name;
        $article_code = $request->article_code;

        $sendedMaterial = OrderItem::with('order','item','order.customer')
                            ->whereHas('order',function ($q){
                                $q->whereNull('deleted_at');
                            })
                            ->whereHas('item',function ($q){
                                $q->whereNull('deleted_at');
                            })
                            ->whereHas('order.customer',function($q){
                                $q->whereNull('deleted_at');
                            });

        if($customer_id != ''  && $material_name != '' && $article_code != '') {
          $sendedMaterial = $sendedMaterial
                            ->whereHas('order.customer',function($q) use ($customer_id){
                                $q->where('id',$customer_id);
                            })
                            ->whereHas('item',function($q) use ($material_name){
                                $q->where('name',$material_name);
                            })
                            ->whereHas('item',function($q) use ($article_code){
                                $q->where('article_no',$article_code);
                            });
        } elseif ($customer_id) {
            $sendedMaterial = $sendedMaterial->whereHas('order.customer',function($q) use ($customer_id){
                $q->where('id',$customer_id);
            });
        }elseif ($material_name) {
            $sendedMaterial = $sendedMaterial->whereHas('item',function($q) use ($material_name){
                $q->where('name',$material_name);
            });
        }elseif ($article_code) {
            $sendedMaterial = $sendedMaterial->whereHas('item',function($q) use ($article_code){
                $q->where('article_no',$article_code);
            });
        }

        $sendedMaterial = $sendedMaterial->paginate()->appends($request->query());
        return view('report.sendedmaterialtoclient',compact('sendedMaterial','order'));
       }
        return view('report.sendedmaterialtoclient',compact('order'));
    }

    public function stockReport(Request $request)
    {

      $article_no = ['' => "Select Article No"];
      $article_no += Material::active()->orderBy('article_no','ASC')->pluck('article_no', 'article_no')->all();

      $colors = ['' => "All Color"];

      $where = [];
      $where[] = ["available_qty", ">", 0];

      $article = $request->search_article;
      $color = $request->color;
      if($article){
        $where['article_no'] = $article;
        $colors += Material::active()->where('article_no', $article)->get()->pluck('color_code', 'color_no')->all();
      }
      if($color){
        $where['color_no'] = $color;
      }

      $stock_items = PurchaseItem::with(['material',])->whereHas('material',function ($q){
                  $q->whereNull('deleted_at');
              })
              ->where($where)
              ->selectRaw('*, sum(qty) as total_qty, sum(available_qty) as total_available_qty, count(roll_no) as total_rolls')
              ->groupBy('article_no')
              ->orderBy('article_no')
              ->get();
              // dd($stock_items->toArray());
              $data = [
                          'stock_items' => $stock_items,
                          'article_no' => $article_no,
                          'colors' => $colors,
                          'article' => $article,
                          'color' => $color
                      ];
             return view('report.stock',$data);

    }
}
