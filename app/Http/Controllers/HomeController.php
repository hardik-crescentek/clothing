<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentHistory;
use App\Invoice;
use App\PurchaseItem;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(Auth::user()->hasRole("payment-receiver")){
            $total_received=PaymentHistory::where("payment_histories.payment_receiver_id","=",Auth::user()->id)
                            ->select(DB::raw('sum(amount) as total_received_amount'))
                            ->first();

            $total_pending = Invoice::where("invoices.payment_receiver_id","=",Auth::user()->id)
                             ->select(DB::raw('sum(grand_total) as total_amount'))
                             ->first();

            $total_pending_amount=(float)($total_pending->total_amount - $total_received->total_received_amount);
            // $due_amount=Invoice::whereMonth('invoice_date','=',sprintf("%02d",now()->subMonth()->month))->whereDay('invoice_date','<=',sprintf("%02d",now()->day))->get();
            $due_amount =   Invoice::where("invoices.payment_receiver_id","=",Auth::user()->id)
                            ->whereDate('invoice_date','<=',Carbon::now()->subDays(30))
                            ->leftjoin('payment_histories','invoices.id','=','payment_histories.invoice_id')
                            ->whereNull('payment_histories.id')
                            ->select(DB::raw('sum(grand_total) as total_amount'))
                            ->first();
        }
        else{
            $total_received=PaymentHistory::select(DB::raw('sum(amount) as total_received_amount'))->first();
            $total_pending=Invoice::select(DB::raw('sum(grand_total) as total_amount'))->first();
            $total_pending_amount=(float)($total_pending->total_amount - $total_received->total_received_amount);
            // $due_amount=Invoice::whereMonth('invoice_date','=',sprintf("%02d",now()->subMonth()->month))->whereDay('invoice_date','<=',sprintf("%02d",now()->day))->get();
            $due_amount=Invoice::whereDate('invoice_date','<=',Carbon::now()->subDays(30))
                        ->leftjoin('payment_histories','invoices.id','=','payment_histories.invoice_id')
                        ->whereNull('payment_histories.id')
                        ->select(DB::raw('sum(grand_total) as total_amount'))
                        ->first();
        }
        $items = PurchaseItem::with([
                    'material',
                    'color'
                ])->whereHas('material',function ($q){
                    $q->whereNull('deleted_at');
                })
                ->where('available_qty','<=','material.min_alert_qty')
                ->orderBy('available_qty','ASC')
                ->paginate(env('ITEMS_PER_PAGE'))
                ->appends($request->query());
       
        $orders = new Order;
        $where = [];
        $where[] = ['status', '!=', 0 ];
        if(Auth::user()->hasRole("client")){
            $where[] = ['customer_id', Auth::user()->id ];
        }
        $orders = $orders->with('customer','seller','invoice','order_items')
                  ->whereHas('customer',function ($q){
                    $q->whereNull('deleted_at');
                  })
                  ->whereHas('seller',function ($q){
                    $q->whereNull('deleted_at');
                  })
                  ->whereHas('invoice',function ($q){
                    $q->whereNull('deleted_at');
                  })
                  ->whereHas('order_items',function ($q){
                    $q->whereNull('deleted_at');
                  })
                  ->where($where)
                  ->latest()
                  ->limit(10)
                  ->get();

        $pending_orders = Order::where('status','=',"'0'")->count();
        $complited_orders = Order::where('status','!=',"'0'")->count();

        $data =[
                    'pending_amount'=>$total_pending_amount,
                    'received_amount'=>$total_received->total_received_amount,
                    'due_amount'=>$due_amount->total_amount,
                    'items'=>$items,
                    'orders'=>$orders,
                    'pending_orders'=>$pending_orders,
                    'complited_orders'=>$complited_orders
                ];
        // echo "<pre>"; print_r($data); die();
        return view('home',$data);
    }
    public function headerNotifaction(Request $request)
    {
        if ($request->ajax()) {
            $notifyItems = PurchaseItem::with(['material'])
                       ->where('available_qty','<=','material.min_alert_qty')
                       ->orderBy('available_qty','DESC')
                       ->limit(4)
                       ->get();
            if ($notifyItems) {
                return response()->json($notifyItems, 200);    
            }
        }
        $notifyItems = PurchaseItem::with(['material'])
                        ->where('available_qty','<=','material.min_alert_qty')
                        ->paginate(env('ITEMS_PER_PAGE'))
                        ->appends($request->query());

        // dd($notifyItems);
        return view('notifaction.viewallnotifaction',compact('notifyItems'));

    }
}
