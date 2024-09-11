<?php

namespace App\Http\Controllers;

use App\ClientArticle;
use App\Order;
use App\OrderItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Supplier;
use App\Material;
use App\Color;
use App\Purchase;
use App\PurchaseItem;
use App\Invoice;
use App\InvoiceItem;
use App\InvoiceItemRoll;
use App\CustomerItemPrice;
use Auth;
use App\PaymentHistory;
use App\SalesPersonCommision;
use Carbon\Carbon;


class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices;
        if(Auth::user()->hasRole("payment-receiver")){
            $invoices = Invoice::where("payment_receiver_id","=",Auth::user()->id)
                        ->with('customer','seller','invoice_items','invoice_item_rolls')
                        ->whereNull('deleted_at')
                        ->whereHas('invoice_items.item',function ($q){
                            $q->whereNull('deleted_at');
                        })
                        ->orderBy('id','DESC');
        }
        else{
            $invoices = Invoice::with('customer','seller','invoice_items','invoice_item_rolls')
                        ->orderBy('id','DESC')
                        ->whereNull('deleted_at')
                        ->whereHas('invoice_items.item',function ($q){
                            $q->whereNull('deleted_at');
                        });
        }
        if($request->to_date!=''&&$request->from_date!=''){
            $to_date = Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
            $from_date = Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
            $invoices = $invoices->whereBetween('invoice_date',array($from_date,$to_date));
        }
        if($request->customer_id!=0){
            $invoices = $invoices->where('customer_id','=',$request->customer_id);
        }
        if($request->search!=''){
            $invoices = $invoices->where('invoice_no','LIKE',"%{$request->search}%");
        }

        $invoices = $invoices->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        $users=['0'=>'--Select Customer--'];
        $users += User::all()->pluck("fullName",'id')->toArray();
        $data = [
                    'invoices'         => $invoices,
                    'users'            => $users,
                    'to_date'          => $request->to_date,
                    'from_date'        => $request->from_date,
                    'selected_customer'=> $request->customer_id,
                    'search'           => $request->search
                ];
        return view('invoice.index',$data);
    }
    public function create(Order $order)
    {

        // $users = User::pluck('firstname', 'id');
        $user = new User();
        $payment_receiver=[""=>'--Select Payment Receiver--'];
        $payment_receiver += $user->role('payment-receiver')->get()->pluck('fullName', 'id')->toArray();
        // $payment_receiver = $user->role('payment-receiver')->get()->pluck('fullName', 'id');
        $items=OrderItem::where('order_id','=',$order->id)->with('item','color')->get();
        // $rolls=PurchaseItem::pluck('roll_no','id');
        return view('invoice.generateInvoice',compact('order','items','payment_receiver'));
    }
    public function getRollData(Request $request)
    {
        $response=[];
            // $roll = PurchaseItem::where('material_id','=',$request->input('material_id'))->where('available_qty','!=','0')->orderBy('sort_order', 'ASC')->get();

            $roll = PurchaseItem::leftJoin('purchases','purchases.id','=','purchase_items.purchase_id')
            ->select('purchase_items.*','purchases.pcs_no as pcs_no')
            ->where('purchase_items.material_id','=',$request->input('material_id'))
            ->where('purchase_items.available_qty','!=','0')
            ->orderBy('purchase_items.sort_order', 'ASC')
            ->get();
            $response = [
                            'status'=>'success',
                            'roll'=>$roll,
                        ];
        return response()->json($response,200);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'invoice_no'=>'required',
            'payment_receiver_id'=>'required',
            'sub_total'=>'required',
            'grand_total'=>'required',
            'generate_date'=>'required',
        ]);
        $order=Order::where('id','=',$request->input('order_id'))->first();
        $invoice_data = [
                            "invoice_no"          => $request->input('invoice_no'),
                            "order_id"            => $order->id,
                            "customer_id"         => $order->customer_id,
                            "seller_id"           => $order->seller_id,
                            "payment_receiver_id" => $request->input('payment_receiver_id'),
                            "sub_total"           => $request->input('sub_total'),
                            "tax"                 => $request->input('tax') ?? 0,
                            "discount"            => $request->input('discount') ?? 0,
                            "discount_type"       => $request->input('discount_type'),
                            "grand_total"         => $request->input('grand_total'),
                            "invoice_date"        => $request->input('generate_date'),
                            "note"                => $order->note,
                        ];
        $invoice=Invoice::create($invoice_data);

        User::where('id','=',$order->customer_id)
        ->update(["last_invoice"=>$order->customer->last_invoice+1]);

        $order_items=OrderItem::where('order_id','=',$order->id)->get();

        $total_roll=0;
        $invoice_item = null;

        foreach($order_items as $order_item){
            foreach($request->input('item_roll') as $key => $value)
            {
                if($key==$order_item->id){
                    foreach($value as $k => $v){
                        $total_roll++;
                    }
                    break;
                }

            }
            $selected_meter = 0;
            foreach($request->input('selected_meter') as $k => $v){
                if($k==$order_item->id)
                {
                    if($v!=$order_item->meter){
                        OrderItem::where('id','=',$order_item->id)->update(['meter'=>$v]);
                        $selected_meter=$v;
                    break;
                    }
                }
            }

            $price = 0;
            foreach($request->input('price') as $k => $v){
                if($k==$order_item->id)
                {
                    $price=$v;
                break;
                }
            }

            CustomerItemPrice::updateOrCreate(["customer_id"=>$order->customer_id,"material_id"=>$order_item->item_id],["price"=>$price]);
            $invoice_item_data=[
                "invoice_id"=>$invoice->id,
                "order_id"=>$order->id,
                "item_id"=>$order_item->item_id,
                "total_meter"=>$selected_meter,
                "total_rolls"=>$total_roll,
                "price"=>$price,
            ];

            $total_roll=0;
            $invoice_item=InvoiceItem::create($invoice_item_data);

            foreach($request->input('item_roll') as $item_id => $value)
            {
                if($item_id==$order_item->id){
                    foreach($value as $roll_id => $meter){
                        // echo " item id = ".$item_id." roll id = ".$roll_id." meter = ".$meter."<br />";
                        $roll_item=PurchaseItem::where('id','=',$roll_id)->first();
                        $invoice_item_roll_data=[
                            "invoice_item_id"=>$invoice_item->id,
                            "invoice_id"=>$invoice->id,
                            "roll_id"=>$roll_id,
                            "roll_no"=>$roll_item->roll_no,
                            "meter"=>$meter,
                        ];
                        InvoiceItemRoll::create($invoice_item_roll_data);
                        $purchase_item=PurchaseItem::where('id','=',$roll_id)->first();
                        $available_meter=$purchase_item->available_qty;
                        $purchase_item->update(['available_qty'=>$available_meter-$meter]);
                    }
                    break;
                }
            }
        }
        return redirect()->route('invoice.index')->with('success', 'Invoice Created successfully');
    }
    public function create2(Request $request)
    {
        $users=[''=>'--Select Customer--'];
        $users += User::all()->pluck("fullName",'id')->toArray();
        $sales_user =new User();
        $sales_person=[""=>'--Select Sales Person--'];
        $sales_person += $sales_user->role('sales-person')->get()->pluck('fullName', 'id')->toArray();
        $payment_user = new User();
        $payment_receiver=[""=>'--Select Payment Receiver--'];
        $payment_receiver += $payment_user->role('payment-receiver')->get()->pluck('fullName', 'id')->toArray();
        $items = $request->session()->get('order_items', []);
        $customer_item_price=CustomerItemPrice::get();

        $article_no = ['' => "Select Article No"];
        $article_no += Material::active()
                        ->pluck('article_no', 'article_no')
                        ->all();

        $colors = ['' => "All Color"];
        $colors += Material::active()
                    ->get()
                    ->pluck('color_code','color_no')
                    ->all();
        // $rolls=PurchaseItem::pluck('roll_no','id');
        return view('invoice.createinvoice',compact('article_no', 'colors', 'users','sales_person','payment_receiver','items','customer_item_price'));
    }

    // public function getMaterial(Request $request){

    //     $materials = Material::select('id', 'name', 'barcode','color','weight','article_no', 'color_no','wholesale_price', 'retail_price', 'sample_price')
    //                     ->where(['article_no' => $request->artical, "color_no"=>$request->color])
    //                     ->first();

    //     return response()->json($materials, 200);
    // }

    public function getMaterial(Request $request)
    {
        $customerId = $request->cus_id;
        $articleNo = $request->artical;
        $colorNo = $request->color;

        // Fetch the material from Material model
        $material = Material::select('id', 'name', 'barcode', 'color', 'weight', 'article_no', 'color_no', 'cut_wholesale','cut_wholesale_per_mtr', 'retail','retail_per_mtr', 'roll','roll_per_mtr','unit_purchased_in','weight_gsm','weight_per_mtr','weight_per_yard')
            ->where(['article_no' => $articleNo, 'color_no' => $colorNo])
            ->first();

        if ($customerId) {
            // Fetch from ClientArticle if customer is selected
            $clientArticle = ClientArticle::select('cut_wholesale', 'retail','roll')
                ->where(['client_id' => $customerId, 'article_no' => $articleNo])
                ->first();

            // Override price fields if client article exists
            if (isset($clientArticle) && !empty($clientArticle)) {
                $material->cut_wholesale = $clientArticle->cut_wholesale;
                $material->cut_wholesale_per_mtr = $clientArticle->cut_wholesale_per_mtr;
                $material->retail = $clientArticle->retail;
                $material->retail_per_mtr = $clientArticle->retail_per_mtr;
                $material->roll = $clientArticle->roll;
                $material->roll_per_mtr = $clientArticle->roll_per_mtr;
            }
        }

        return response()->json($material, 200);
    }

    public function getClientMaterial(Request $request){
        $materials = ClientArticle::select('id','roll','cut_wholesale','retail')
                        ->where(['client_id' => $request->cus_id,'article_no'=>$request->artical])
                        ->first();

        return response()->json($materials, 200);
    }

    public function store2(Request $request)
    {

        $this->validate($request, [
            'item_id'=>'required|distinct'
        ]);
        //for order
        $order_data=[
            "customer_id" => $request->input('customer_id'),
            "seller_id"   => $request->input('seller_id'),
            "order_date"  => $request->input('generate_date'),
            "note"        => $request->input("note"),
        ];
        $order = Order::create($order_data);
        //for invoice
        $invoice_data=[
            "invoice_no"           => $request->input('invoice_no'),
            "order_id"             => $order->id,
            "customer_id"          => $request->input('customer_id'),
            "seller_id"            => $request->input('seller_id'),
            "payment_receiver_id"  => $request->input('payment_receiver_id'),
            "sub_total"            => $request->input('sub_total'),
            "payment_terms"        => $request->input('payment_terms'),
            "credit_days"          => $request->input('credit_days'),
            "sales_type"           => $request->input('sales_type'),
            "charge_in_unit"       => $request->input('chareg_in_unit'),
            "sales_commision"      => $request->input('sales_commision'),
            "commision_type"       => $request->input('commision_type'),
            "commision_amount_thb" => $request->input('commision_amount_thb'),
            "commision_amount_sale"=> $request->input('commision_amount_sale'),
            "tax"                  => $request->input('tax'),
            "discount"             => $request->input('discount'),
            "discount_type"        => $request->input('discount_type'),
            "grand_total"          => $request->input('grand_total'),
            "invoice_date"         => $request->input('generate_date'),
            "note"                 => $request->note,
        ];
        // dd($invoice_data);
        $invoice = Invoice::create($invoice_data);

        //for update last invoice

        User::where('id','=',$order->customer_id)
        ->update(["last_invoice"=>$order->customer->last_invoice+1]);

        //for Sales person
        $sales_commision = $request->input('sales_commision');
        if ($sales_commision == "Yes") {
            $commision_type = $request->input('subtotal_commision_type');

            if ($commision_type == "percentage") {
                $sub_total = $request->input('sub_total ');
                $commision_amount_sale = $request->input('commision_amount_sale');
                $commison_amount = $sub_total*$commision_amount_sale/100;
            }elseif ($commision_type == "thb") {
                $commision_amount_thb = $request->input('commision_amount_thb');
                $meter = $request->input('meter');
                $meter_sum = array_sum($meter);
                $commison_amount = $meter_sum * $commision_amount_thb ;
            }
                $sales_person_commision =   [
                                                "sales_person_id"          => $request->input('seller_id'),
                                                "order_id"                 => $order->id,
                                                "commision_type"           => $request->input('commision_type'),
                                                "unit_commision"           => $request->input('commision_amount_thb'),
                                                "subtotal_commision"       => $request->input('commision_amount_sale'),
                                                "subtotal_commision_type"  => $request->input('subtotal_commision_type'),
                                                "subtotal_commision_amount"=> $commison_amount,
                                            ];

            $sales_person_commison =  SalesPersonCommision::create($sales_person_commision);
        }

        $total_roll=0;
        $item_count=count($request->input('item_id'));
        for($i=0; $i<$item_count; $i++)
        {
            //count price
            $price = 0;
            foreach($request->input('price') as $k => $v){
                if($k==$request->item_id[$i])
                {
                    $price=$v;
                break;
                }
            }
            //for order item
            $order_item_data= [
                                "order_id"     => $order->id,
                                "item_id"      => $request->item_id[$i],
                                "type_of_sale" => $request->type_of_sale[$i],
                                "meter"        => $request->meter[$i],
                                "price"        => $price,
                              ];
            $order_item_id=OrderItem::create($order_item_data);
            //for count total roll
            foreach($request->input('item_roll') as $key => $value)
            {
                if($request->item_id[$i]==$key){
                    foreach($value as $k => $v)
                    {
                        $total_roll++;
                    }
                break;
                }
            }
            //for invoice_item
            $invoice_item_data =[
                                    "invoice_id"   => $invoice->id,
                                    "order_id"     => $order->id,
                                    "item_id"      => $request->item_id[$i],
                                    "type_of_sale" => $request->type_of_sale[$i],
                                    "total_meter"  => $request->meter[$i],
                                    "total_rolls"  => $total_roll,
                                    "price"        => $price
                                ];
            $invoice_item_id=InvoiceItem::create($invoice_item_data);

            //for save customer and material and it's price

            $customer_item_price=[
                "customer_id"=>$order->customer_id,
                "material_id"=>$order_item_id->item_id,
                "price"=>$price,
            ];
            CustomerItemPrice::create($customer_item_price);

            //for invoice item roll

            foreach($request->input('item_roll') as $item_id => $value){
                if($item_id==$request->item_id[$i]){
                    foreach($value as $roll_id => $meter){
                        $roll_item=PurchaseItem::where('id','=',$roll_id)->first();
                        $invoice_item_roll_data=[
                                                    "invoice_item_id"=> $invoice_item_id->id,
                                                    "invoice_id"     => $invoice->id,
                                                    "roll_id"        => $roll_id,
                                                    "roll_no"        => $roll_item->roll_no,
                                                    "meter"          => $meter,
                                                ];
                        InvoiceItemRoll::create($invoice_item_roll_data);
                        $purchase_item=PurchaseItem::where('id','=',$roll_id)->first();
                        $available_meter=$purchase_item->available_qty;
                        $purchase_item->update(['available_qty'=>$available_meter-$meter]);
                    }
                break;
                }
            }
        }
        return redirect()->route('invoice.index')->with('success', 'Invoice Created successfully');
    }
    public function getLastInvoiceInfo(Request $request)
    {
        return response(User::where('id','=',$request->id)->get()->first());
    }
    public function edit(Invoice $invoice)
    {
        $user = new User();
        $payment_receiver=[""=>'--Select Payment Receiver--'];
        $payment_receiver += $user->role('payment-receiver')->get()->pluck('fullName', 'id')->toArray();
        $items=InvoiceItem::where('invoice_id','=',$invoice->id)->with('color','item','invoice_item_roll')->get();
        return view('invoice.editinvoice',compact('invoice','items','payment_receiver'));
    }
    public function update(Request $request,Invoice $invoice)
    {
        $invoice->fill([
            "payment_receiver_id"  => $request->input('payment_receiver_id'),
            "sub_total"            => $request->input('sub_total'),
            "payment_terms"        => $request->input('payment_terms'),
            "credit_days"          => $request->input('credit_days'),
            "sales_type"           => $request->input('sales_type'),
            "charge_in_unit"       => $request->input('chareg_in_unit'),
            "sales_commision"      => $request->input('sales_commision'),
            "commision_type"       => $request->input('commision_type'),
            "commision_amount_thb" => $request->input('commision_amount_thb'),
            "commision_amount_sale"=> $request->input('commision_amount_sale'),
            "tax"                  => $request->input('tax'),
            "discount"             => $request->input('discount'),
            "discount_type"        => $request->input('discount_type'),
            "grand_total"          => $request->input('grand_total'),
        ]);
        $invoice->save();
        //for Sales person
        $sales_commision = $request->input('sales_commision');
        if ($sales_commision == "Yes") {
            $commision_type = $request->input('subtotal_commision_type');

            if ($commision_type == "percentage") {
                $sub_total = $request->input('sub_total ');
                $commision_amount_sale = $request->input('commision_amount_sale');
                $commison_amount = $sub_total*$commision_amount_sale/100;
            }elseif ($commision_type == "thb") {
                $commision_amount_thb = $request->input('commision_amount_thb');
                $meter = $request->input('meter');
                $meter_sum = array_sum($meter);
                $commison_amount = $meter_sum * $commision_amount_thb ;
            }
            $sales_person_commison =  SalesPersonCommision::where('sales_person_id','=',$invoice->seller_id)
                                        ->where('order_id','=',$invoice->order_id)
                                        ->update([
                                            "commision_type"            => $request->commision_type,
                                            "unit_commision"            => $request->commision_amount_thb,
                                            "subtotal_commision"        => $request->commision_amount_sale,
                                            "subtotal_commision_type"   => $request->subtotal_commision_type,
                                            "subtotal_commision_amount" => $commison_amount,
                                        ]);
        }
        $invoice_items=InvoiceItem::where('invoice_id','=',$invoice->id)->get();
        $total_roll=0;
        $invoice_item_data=[
                            "total_rolls"=>"",
                            "price"=>"",
                           ];
        $id;
        foreach($invoice_items as $invoice_item){
            foreach($request->input('item_roll') as $key => $value)
            {
                if($key==$invoice_item->id){
                    foreach($value as $k => $v){
                        $total_roll++;
                    }
                    $invoice_item_data['total_rolls']=$total_roll;
                    $total_roll=0;
                    break;
                }
            }
            $selected_meter;
            foreach($request->input('selected_meter') as $k => $v){
                if($k==$invoice_item->id)
                {
                    if($v!=$invoice_item->total_meter){

                        OrderItem::where('order_id','=',$request->order_id)
                        ->where('item_id','=',$invoice_item->item_id)
                        ->where('price','=',$invoice_item->price)
                        ->update(['meter'=>$v]);

                        $selected_meter=$v;
                        $invoice_item_data['total_meter']=$v;
                        $id=$k;
                    break;
                    }
                }
            }
            $price;
            foreach($request->input('price') as $k => $v){
                if($k==$invoice_item->id)
                {
                    $price=$v;
                    $invoice_item_data['price']=$v;
                    $id=$k;
                break;
                }
            }
           $invoice_item_id= InvoiceItem::find($id)->fill($invoice_item_data)->save();
           foreach($request->input('item_roll') as $item_id => $value)
            {
                if($item_id==$invoice_item->id){
                    foreach($value as $roll_id => $meter){
                        // echo " item id = ".$item_id." roll id = ".$roll_id." meter = ".$meter."<br />";
                        $roll_item=PurchaseItem::where('id','=',$roll_id)->first();
                        $invoice_item_roll_data=[
                                                    "roll_id"=> $roll_id,
                                                    "roll_no"=> $roll_item->roll_no,
                                                    "meter"  => $meter,
                                                ];
                        $invoice_item_roll_data_new=[
                                                        "invoice_item_id"=> $invoice_item->id,
                                                        "invoice_id"     => $invoice->id,
                                                        "roll_id"        => $roll_id,
                                                        "roll_no"        => $roll_item->roll_no,
                                                        "meter"          => $meter,
                                                    ];
                        if (InvoiceItemRoll::where('roll_id',$roll_id)->where('invoice_item_id',$item_id)->count()){

                            $invoice_item_roll=InvoiceItemRoll::find($item_id);
                            $new_meter=(int)$meter-($invoice_item_roll->meter);
                            $purchase_item=PurchaseItem::where('id','=',$roll_id)->first();
                            $available_meter=$purchase_item->available_qty;

                            if($purchase_item->qty < ($available_meter-$new_meter))
                            {
                                $purchase_item->update(['available_qty'=>$purchase_item->qty]);
                            }
                            else {
                                $purchase_item->update(['available_qty'=>$available_meter-$new_meter]);
                            }
                            $invoice_item_roll->fill($invoice_item_roll_data)->save();
                        }
                        else{

                            InvoiceItemRoll::create($invoice_item_roll_data_new);
                            $purchase_item=PurchaseItem::where('id','=',$roll_id)->first();
                            $available_meter=$purchase_item->available_qty;
                            $purchase_item->update(['available_qty'=>$available_meter-$meter]);
                            $purchase_item->save();
                        }
                    }
                    break;

                }
            }
        }
        return redirect()->route('invoice.index')->with('success', 'Invoice Updated successfully');
    }
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success', 'Invoice successfully deleted.');
    }
    public function print(Invoice $invoice)
    {
        return view('invoice.print-invoice',compact('invoice'));
    }
    public function addPayment(Invoice $invoice,Request $request)
    {
        $history = PaymentHistory::where('invoice_id','=',$invoice->id)
                    ->with('invoice','paymentReceiver')
                    ->paginate(env('ITEMS_PER_PAGE'))
                    ->appends($request->query());
        return view('invoice.addpayment',compact('invoice','history'));
    }
    public function savePayment(Request $request)
    {
        $data = [
                    "invoice_id"         => $request->input('invoice_id'),
                    "payment_receiver_id"=> $request->input('payment_receiver_id'),
                    "payment_receiver_id"=> $request->input('payment_receiver_id'),
                    "payment_type"       => $request->input('payment_type'),
                    "amount"             => $request->input('amount'),
                    "chequee_no"         => $request->input('chequee_no'),
                    "note"               => $request->input('note'),
                    "payment_date"       => $request->input('generate_date'),
                ];
        PaymentHistory::create($data);
        return redirect()->route('invoice.add-payment',$request->input('invoice_id'))->with('success', 'Payment successfully Add.');
    }

    public function customerPriceBook($id){
        $customer_price = CustomerItemPrice::with('customer','material')->where("customer_id",$id)->orderBy('id','DESC')->get();
        return response()->json($customer_price,200);
    }

    public function isCreditDaysExits(Request $request)
    {
        $customer_id = $request->customer_id;
        $material_id = $request->material_id;

        if ($customer_id == "") {
            $msg = ["msg" => "Please Select Customer First"];
            return response()->json($msg,200);
        }

        $customer_credit_days = CustomerItemPrice::select('retail_credit_days')
                                ->where("customer_id",$customer_id)
                                ->where("material_id",$material_id)
                                ->orderBy('id','DESC')->first();
        if ($customer_credit_days) {
            return response()->json($customer_credit_days, 200);
        }
        return response()->json('Customer OR Material Not Found', 200);

    }

}
