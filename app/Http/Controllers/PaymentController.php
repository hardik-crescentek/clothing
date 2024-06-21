<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\PaymentHistory;
use App\User;
use App\Invoice;
use Auth;

class PaymentController extends Controller
{
    public function pendingPayments(Request $request)
    {
        $histories;
        if(Auth::user()->hasRole("payment-receiver")){
            $history =  Invoice::where("invoices.payment_receiver_id","=",Auth::user()->id)
                        ->with('customer','seller','paymentReceiver')
                        ->leftjoin('payment_histories','invoices.id','=','payment_histories.invoice_id')
                        ->select(DB::raw('sum(payment_histories.amount) as total_amount,invoices.*'))
                        ->groupBy('invoices.id','invoices.invoice_no','invoices.order_id','invoices.customer_id','invoices.seller_id','invoices.payment_receiver_id','invoices.sub_total','invoices.tax','invoices.discount','invoices.discount_type','invoices.grand_total','invoices.invoice_date','invoices.note','invoices.status','invoices.deleted_at','invoices.created_at','invoices.updated_at');
        }
        else{
            $history =  Invoice::with('customer','seller','paymentReceiver')
                        ->leftjoin('payment_histories','invoices.id','=','payment_histories.invoice_id')
                        ->select(DB::raw('sum(payment_histories.amount) as total_amount,invoices.*'))
                        ->groupBy('invoices.id','invoices.invoice_no','invoices.order_id','invoices.customer_id','invoices.seller_id','invoices.payment_receiver_id','invoices.sub_total','invoices.tax','invoices.discount','invoices.discount_type','invoices.grand_total','invoices.invoice_date','invoices.note','invoices.status','invoices.deleted_at','invoices.created_at','invoices.updated_at');
            
        }
        
        if($request->to_date!=''&&$request->from_date!=''){
            $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
            $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
            $history=$history->whereBetween('invoice_date',array($from_date,$to_date));  
        }

        if($request->customer_id!=0){
            $history=$history->where('invoices.customer_id','=',$request->customer_id);
        }
        if($request->due_amount){
            $history=$history->whereDate('invoices.invoice_date', '<=', Carbon::now()->subDays(30));
            
        }
        $history=$history->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        $users=['0'=>'--Select Customer--'];
        $users += User::all()->pluck("fullName",'id')->toArray();
        $data=['history' => $history,'users' => $users,'selected_customer'=>$request->customer_id];
        return view('payments/pending-payments',$data);
    }
    public function receivedPayments(Request $request)
    {
        $histories;
        if(Auth::user()->hasRole("payment-receiver")){
            $history = PaymentHistory::where("payment_histories.payment_receiver_id","=",Auth::user()->id)
                        ->with('invoice','invoice.customer','paymentReceiver')
                        ->leftjoin('invoices',function($join){
                            $join->on('payment_histories.invoice_id','=','invoices.id');
                        })
                        ->orderBy('payment_histories.id','DESC');
        }
        else{
            $history = PaymentHistory::with('invoice','invoice.customer','paymentReceiver')
                       ->leftjoin('invoices',function($join){
                            $join->on('payment_histories.invoice_id','=','invoices.id');
                        })->orderBy('payment_histories.id','DESC');
             
        }
        
        
        if($request->to_date!=''&&$request->from_date!=''){
            $to_date=Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
            $from_date=Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
            $history=$history->whereBetween('payment_date',array($from_date,$to_date));
        }
        if($request->customer_id!=0){
            $history=$history->where('invoices.customer_id','=',$request->customer_id);
        }
        
        $history=$history->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        $users = ['0'=>'--Select Customer--'];
        $users += User::all()->pluck("fullName",'id')->toArray();
        $data = [
                    'history' => $history,
                    'users' => $users,
                    'to_date'=>$request->to_date,
                    'from_date'=>$request->from_date,
                    'selected_customer'=>$request->customer_id
                ];
        return view('payments/received-payments',$data);
    }
}
