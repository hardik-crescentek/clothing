<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceItem;
use App\InvoiceItemRoll;
use App\PurchaseItem;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $invoice_no=$request->invoice_no;
        
        $invoice = Invoice::with('customer','invoice_item_rolls','invoice_item_rolls.invoice_item','invoice_item_rolls.roll.material')
                    ->where('invoice_no','=',$invoice_no)
                    ->first();
        $data=  [
                    "invoice" => $invoice ,
                    "invoice_no" =>$invoice_no
                ];
        return view('return.index',$data);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'select'=> 'required',
            'meter' => 'required'
        ]);
        foreach ($request->meter as $key => $value) {
            // echo "key ".$key." value ".$value."<br />";
            $roll=PurchaseItem::where('id','=',$key)->first()->toArray();
            $data=["qty"=>$value,"available_qty"=>$value,"return_status"=>1];
            $data+=$roll;
            Purchaseitem::create($data);
        }
        foreach ($request->select as $key => $value) {
            InvoiceItemRoll::where('id','=',$value)->update(['return_status'=>1]);
        }
        return redirect()->route('return')->with('success', 'Return items updated successfully');
    }
}
