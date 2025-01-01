<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Supplier;
use App\Material;
use App\Color;
use App\PurchaseItem;
use App\Purchase;
use App\Settings;
use App\Invoice;
use App\InvoiceItem;
use App\InvoiceItemRoll;
use App\CustomerItemPrice;
use Auth;
use Carbon\Carbon;
use DB;
use DNS1D;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [];
        if(Auth::user()->hasRole("client")){
            $where[] = ['customer_id', Auth::user()->id ];
        }
        $orders = new Order;
        $orders = $orders->with('customer','seller','invoice','order_items','dispatcher')
                  ->where($where)->orderBy('id','DESC');
        $search = $request->search;
        $start_date = isset($request->start_date) ? \Carbon\Carbon::parse($request->start_date)->format("Y-m-d"):"";
        $end_date = isset($request->end_date) ? \Carbon\Carbon::parse($request->end_date)->format("Y-m-d"):"";
        $dispatcher_id = $request->dispatcher_id;
        if ($search) {
            $orders->WhereHas(
                'customer',
                function ($q) use ($search){
                 $q->Where('firstname', 'LIKE', "%{$search}%")->orWhere('lastname', 'LIKE', "%{$search}%");
                }
            );
            $orders->orWhereHas(
                'seller',
                function ($q) use ($search){
                    $q->Where('firstname', 'LIKE', "%{$search}%")->orWhere('lastname', 'LIKE', "%{$search}%");
                }
            );
            $orders->orWhereHas(
                'order_items.item',
                function ($q) use ($search){
                    $q->Where('name', 'LIKE', "%{$search}%");
                }
            );
        }
        if (!empty($start_date) && !empty($end_date)) {
            $orders->Where('order_date',">=",$start_date);
            $orders->Where('order_date',"<=",$end_date);
        }

        if (!empty($dispatcher_id)) {
            $orders->where('dispatcher_id', $dispatcher_id);
        }

        if ($request->filled('status')) {
            $orders->whereHas('order_items', function ($subQuery) use ($request) {
                $subQuery->where('status', $request->status);
            });
        }

        $orders = $orders->get();
        $dispatchers = User::role('dispatcher')
                        ->with('warehouse')
                        ->select('id', 'firstname', 'lastname','warehouse_id')
                        ->get()
                        ->mapWithKeys(function ($user) {
                            $warehouseName = $user->warehouse ? $user->warehouse->name : 'No Warehouse';
                            return [$user->id => $user->fullName . ' - ' . $warehouseName];
                        })
                        ->toArray();
        return view('order/index',compact('orders','dispatchers'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBookings(Request $request)
    {
        $where = [];
        $where[] = ['status','==', 0 ];
        if(Auth::user()->hasRole("client")){
            $where[] = ['customer_id', Auth::user()->id ];
        }
        $orders = new Order;
        $orders = $orders->with('customer','seller','invoice','order_items')->where($where)->orderBy('id','DESC');
        $search = $request->search;
        if ($search) {
            $orders->WhereHas(
                'customer',
                function ($q) use ($search){
                 $q->Where('firstname', 'LIKE', "%{$search}%")->orWhere('lastname', 'LIKE', "%{$search}%");
                }
            );
            $orders->orWhereHas(
                'seller',
                function ($q) use ($search){
                    $q->Where('firstname', 'LIKE', "%{$search}%")->orWhere('lastname', 'LIKE', "%{$search}%");
                }
            );
            $orders->orWhereHas(
                'order_items.item',
                function ($q) use ($search){
                    $q->Where('name', 'LIKE', "%{$search}%");
                }
            );

        }
        $orders = $orders->where($where)->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        return view('order/bookings',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $today = Carbon::now();
        $formattedDate = strtoupper($today->format('dm')); 
        $totalOrders = Order::count();
        
        $sequence = $totalOrders + 1;
        $orderNumber = sprintf("OR%s_%d", $formattedDate, $sequence);

        $dispatchers = User::role('dispatcher')
                        ->with('warehouse')
                        ->select('id', 'firstname', 'lastname','warehouse_id')
                        ->get()
                        ->mapWithKeys(function ($user) {
                            $warehouseName = $user->warehouse ? $user->warehouse->name : 'No Warehouse';
                            return [$user->id => $user->fullName . ' - ' . $warehouseName];
                        })
                        ->toArray();

        $users=[''=>'--Select Customer--'];
        $users += User::all()->pluck("fullName",'id')->toArray();

        // Fetch the "Direct Sale" user record
        $directSale = User::where('email', 'direct.sale@example.com')->first();

        $sales_user =new User();
        $sales_person=[];
        if ($directSale) {
            $sales_person[$directSale->id] = $directSale->fullName;
        }
        $sales_person += $sales_user->role('sales-person')->get()->pluck('fullName', 'id')->toArray();
        $colors = Color::active()->pluck('name', 'id')->all();
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
                    
        // gst or vat amount from settings
        $vat = Settings::where('id', 1)->value('vat') ?? 0;

        // echo "<pre>"; print_r($article_no); die();
        return view('order/create', compact('article_no', 'colors', 'users', 'sales_person', 'colors', 'items','customer_item_price','vat','dispatchers','orderNumber'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function posCreate(Request $request)
    {
        $users=[''=>'--Select Customer--'];
        $users += User::all()->pluck("fullName",'id')->toArray();
        $sales_user =new User();
        $sales_person=[""=>'--Select Sales Person--'];
        $sales_person += $sales_user->role('sales-person')->get()->pluck('fullName', 'id')->toArray();
        $colors = Color::active()->pluck('name', 'id')->all();
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

        return view('order/posCreate', compact('article_no', 'colors', 'users', 'sales_person', 'colors', 'items','customer_item_price'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'item_id'        => 'required|distinct',
            // 'name'           => 'required',
            'description'    => 'required',
            'user_id'        => 'required',
            // 'selse_person_id'=> 'required',
            // 'barcode'        => 'required',
            'price'          => 'required',
            // 'meter'          => 'required',
            'purchase_date'  => 'required',
            'payment_term'      => 'required|string|in:cash,credit',
            'credit_day'        => 'nullable|integer|min:1',
            'dispatcher_id'  => 'required',
            'image' => 'nullable|string',
            'original_file_name' => 'nullable|string|max:255',
        ]);

        $filePath = null;

        if ($request->filled('image')) {
            $imageData = $request->input('image');
            $originalFileName = $request->input('original_file_name', 'unknown.png');
    
            $imageParts = explode(";base64,", $imageData);
            $imageTypeAux = explode("image/", $imageParts[0]);
            $imageExtension = $imageTypeAux[1] ?? 'png';
            $imageBase64 = base64_decode($imageParts[1]);
    
            $sanitizedFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
            $sanitizedFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $sanitizedFileName);
            $fileNameWithExtension = $sanitizedFileName . '.' . $imageExtension;
    
            $filePath = 'uploads/orders/' . $fileNameWithExtension;
    
            Storage::disk('public')->put($filePath, $imageBase64);
        }

        // Add conditional validation for credit_day
        if ($request->input('payment_term') === 'credit') {
            $rules['credit_day'] = 'required|integer|min:1'; // Make credit_day required if payment_term is credit
        } else {
            $rules['credit_day'] = 'nullable'; // Allow null if payment_term is cash
        }

        $total_qty = 0;
        $items = [];
        $materials = $request->input('item_id');
        if (is_array($materials)) {
            foreach ($materials as $key => $value) {
                $items[] = array(
                                'item_id'         => $value,
                                // 'name'            => $request->input('name.' . $key),
                                'description'     => $request->input('description.' . $key),
                                'user_id'         => $request->input('user_id.' . $key),
                                'barcode'         => $request->input('barcode.' . $key),
                                'type_of_sale'    => $request->input('type_of_sale.' . $key),
                                'price'           => $request->input('price.' .$key),
                                'total-price'     => $request->input('total-price.' .$key),
                                'meter'           => $request->input('meter.' . $key),
                                'roll_id'         => $request->input('roll_id.' . $key),
                                'yard'            => meter2yard($request->input('meter.' . $key)),
                                'item_roll'       => $request->input("item_roll.$value", []),
                                'status'          => 'Pending',
                                'status_date'     => Carbon::now()->format('Y-m-d H:i:s'),
                            );
                $total_qty +=  $request->input('meter.' . $key);
            }
        }
        $request->session()->flash('order_items', $items);
        $user = Auth::user();
        $payment_term = $request->input("payment_term");
        $data = [
                    "customer_id"=> $request->input('user_id'),
                    "seller_id"  => $request->input('selse_person_id', $user->id),
                    "order_date" => $request->input('purchase_date'),
                    "note"       => $request->input("note"),
                    "role_cutter_name" => $request->input("role_cutter_name"),
                    "payment_term" => $payment_term,
                    "price_vat"    => $request->input("price_vat"),
                    "credit_day" => isset($payment_term) && ($payment_term == 'cash') ? NULL : $request->input("credit_day"),
                    "entered_by" => $request->input("entered_by"),
                    "arranged_by" => $request->input("arranged_by"),
                    "inspected_by" => $request->input("inspected_by"),
                    "delivered_by" => $request->input("delivered_by"),
                    "total_number_of_items" => $request->input("total_number_of_items"),
                    "approximate_weight" => $request->input("approximate_weight"),
                    "grand_total" => $request->input("grand_total"),
                    "vat_percentage" => $request->input("vat_percentage"),
                    "vat_amount" => $request->input("vat_amount"),
                    "delivered_date" => $request->input("delivered_date"),
                    "total_profit" => $request->input("total_profit"),
                    "dispatcher_id" => $request->input("dispatcher_id"),
                    "order_no" => $request->input("order_no"),
                    "dispatcher_name" => $request->input("dispatcher_name"),
                    "warehouse_name" => $request->input("warehouse_name"),
                    'image'          => $filePath,
                ];
        $order = Order::create($data);
        if($items)
        {
            foreach($items as $item)
            {
                // if (isset($item['roll_id']) && $item['roll_id'] != '' && $item['roll_id'] != 0) {
                //     $purchase_data = PurchaseItem::where('id',$item['roll_id'])->first();
                //     if ($purchase_data != '') 
                //     {
                //         $total_qty = $purchase_data->available_qty - $item['meter'];
                //         $update_data['available_qty'] = $total_qty;
                //         PurchaseItem::where('id',$item['roll_id'])->update($update_data);
                //     }
                // }

                if (isset($item['item_roll']) && is_array($item['item_roll'])) {
                    foreach ($item['item_roll'] as $roll_id => $quantity) {
                        if ($roll_id && $quantity > 0) {
                            $purchase_data = PurchaseItem::where('id', $roll_id)->first();
                            if ($purchase_data) {
                                $total_qty = $purchase_data->available_qty - $quantity;
                                
                                // Ensure no negative quantities
                                $update_data['available_qty'] = max(0, $total_qty);
                                
                                // Update the roll's available quantity
                                PurchaseItem::where('id', $roll_id)->update($update_data);
                            }
                        }
                    }
                }
                $item_data= [
                                "order_id"     => $order->id,
                                "item_id"      => $item['item_id'],
                                "type_of_sale" => $item['type_of_sale'],
                                "meter"        => $item['meter'],
                                "price"        => $item['price'],
                                "roll_id"      => $item['roll_id'],
                                "item_total"   => $item['total-price'],
                                "status"       => $item['status'],
                                "status_date"  => $item['status_date'],
                            ];

                OrderItem::create($item_data);

                $customerPrice = CustomerItemPrice::where('customer_id',$request->input('user_id'))
                                ->where('material_id',$item['item_id'])
                                ->select('customer_id','material_id')
                                ->first();
                $salesType = $item['type_of_sale'];
                $customerPriceData = [
                                        'customer_id'     => $request->input('user_id'),
                                        'material_id'     => $item['item_id'],
                                        'wholesale_price' => "0",
                                        'price'           => "0",
                                        'sample_price'    => "0",
                                    ];

                $price = $item['price'];
                if ($salesType == 'W') {
                    $customerPriceData['wholesale_price'] = $price;
                }elseif ($salesType == 'R') {
                    $customerPriceData['price'] = $price;
                }else{
                    $customerPriceData['sample_price'] = $price;
                }

               if ($customerPrice) {
                    CustomerItemPrice::where('customer_id',$request->input('user_id'))
                    ->where('material_id',$item['item_id'])
                    ->update($customerPriceData);
               }else{
                    CustomerItemPrice::create($customerPriceData);
               }

            }

        }

        // $this->sendNotificationToDispatcher($order);

        $action = $request->input('action');
        if (isset($action) && $action === 'generate_invoice') {
            return redirect()->route('invoice.create', $order->id);
        } else {
            return redirect()->route('order.index')->with('success', 'Order added successfully');
        }
    }

    private function sendNotificationToDispatcher($order)
    {
        $dispatcher = User::find($order->dispatcher_id);
        if ($dispatcher && $dispatcher->device_token) {
            $data = [
                'to' => $dispatcher->device_token,
                'notification' => [
                    'title' => 'New Order Assigned',
                    'body' => "Order #{$order->order_no} has been assigned to you.",
                    'sound' => 'default',
                ],
                'data' => [
                    'order_id' => $order->id,
                ],
            ];

            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
            $serverKey = config('services.fcm.server_key');

            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])->post($fcmUrl, $data);

            if ($response->failed()) {
                Log::error('FCM Notification Failed', [
                    'response' => $response->body(),
                    'data' => $data,
                ]);
            } else {
                Log::info('FCM Notification Sent Successfully', [
                    'response' => $response->body(),
                ]);
            }
        } else {
            Log::warning('Dispatcher not found or missing FCM token', [
                'dispatcher_id' => $order->dispatcher_id,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $users=[''=>'--Select Customer--'];
        $users += User::all()->pluck("fullName",'id')->toArray();
        $sales_user =new User();
        $sales_person=[""=>'--Select Sales Person--'];
        $sales_person += $sales_user->role('sales-person')->get()->pluck('fullName', 'id')->toArray();
        $colors = Color::active()->pluck('name', 'id')->all();
        $items = OrderItem::leftJoin('materials as mat', DB::raw("FIND_IN_SET(mat.id, order_items.roll_id)"), ">", DB::raw("'0'"))
            ->where('order_id','=',$order->id)
            ->with('item','color','purchase')
            ->select('order_items.*',DB::raw("group_concat(mat.article_no) as materials_name"))
            ->groupBy('order_items.roll_id')
            ->get();
        // $items=OrderItem::where('order_id','=',$order->id)->with('item','color','purchase')->get();
        $order_status = array_merge(['' => "Select Status"],config('constants.order_status'));
        $orderWarehouse = $order->warehouse()->first();
        // $items=$order->order_items;
        // echo "<pre>"; print_r($items); die();
        return view('order.edit',compact('order','users','sales_person','colors','items','order_status','orderWarehouse'));
    }

    public function ViewOrderDetails($id)
    {

        $users=[''=>'--Select Customer--'];
        $users += User::all()->pluck("fullName",'id')->toArray();
        $sales_user =new User();
        $sales_person=[""=>'--Select Sales Person--'];
        $sales_person += $sales_user->role('sales-person')->get()->pluck('fullName', 'id')->toArray();
        $colors = Color::active()->pluck('name', 'id')->all();
        $order=Order::where('id','=',$id)->first();

        $items = OrderItem::leftJoin('materials as mat', DB::raw("FIND_IN_SET(mat.id, order_items.roll_id)"), ">", DB::raw("'0'"))
            ->where('order_id','=',$id)
            ->with('item','color','purchase')
            ->select('order_items.*',DB::raw("group_concat(mat.article_no) as materials_name"))
            ->groupBy('order_items.roll_id')
            ->get();
        // $items=OrderItem::where('order_id','=',$id)->with('item','color','purchase')->get()->toArray();
        $order_status = array_merge(['' => "Select Status"],config('constants.order_status'));
        // $items=$order->order_items;
        // echo "<pre>"; print_r($items); die();
        return view('order.view',compact('order','users','sales_person','colors','items','order_status'));
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $items = [];
        $materials = $request->input('a_item_id');
        if (is_array($materials)) {
            foreach ($materials as $key => $value) {
                $items[] = array(
                                'item_id'         => $value,
                                'name'            => $request->input('a_name.' . $key),
                                'user_id'         => $request->input('user_id.' . $key),
                                'selse_person_id' => $request->input('selse_person_id.' . $key),
                                'barcode'         => $request->input('a_barcode.' . $key),
                                'type_of_sale'    => $request->input('a_type_of_sale.' . $key),
                                'price'           => $request->input('a_price.'.$key),
                                'meter'           => $request->input('a_meter.' . $key),
                                'yard'            => meter2yard($request->input('a_meter.' . $key))
                            );
            }
        }
        $payment_term = $request->input("payment_term");
        $data = [
                    "customer_id"=> $request->input('customer_id'),
                    "seller_id"  => $request->input('seller_id'),
                    "order_date" => $request->input('purchase_date'),
                    "note"       => $request->input("note"),
                    "remark"     => $request->input("remark"),
                    "role_cutter_name"     => $request->input("role_cutter_name"),
                    "payment_term" => $payment_term,
                    "price_vat"    => $request->input("price_vat"),
                    "credit_day" => isset($payment_term) && ($payment_term == 'cash') ? NULL : $request->input("credit_day"),
                    "entered_by" => $request->input("entered_by"),
                    "arranged_by" => $request->input("arranged_by"),
                    "inspected_by" => $request->input("inspected_by"),
                    "delivered_by" => $request->input("delivered_by"),
                    "delivered_date" => $request->input("delivered_date"),
                    "total_number_of_items" => $request->input("total_number_of_items"),
                    "approximate_weight" => $request->input("approximate_weight"),
                    "delivered_date" => $request->input("delivered_date"),
                    "status" => $request->input("status"),
                    'status_date' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
        $order->fill($data);
        $order->save();
        if($items)
        {
            foreach($items as $item)
            {
                $item_data= [
                                "order_id"     => $order->id,
                                "item_id"      => $item['item_id'],
                                "type_of_sale" => $item['type_of_sale'],
                                "meter"        => $item['meter'],
                                "price"        => $item['price'],
                            ];
                OrderItem::insert($item_data);


                $customerPrice = CustomerItemPrice::where('customer_id',$request->input('customer_id'))
                                ->where('material_id',$item['item_id'])
                                ->select('customer_id','material_id')
                                ->first();

                $salesType = $item['type_of_sale'];

                $customerPriceData =[
                                        'customer_id'     => $request->input('customer_id'),
                                        'material_id'     => $item['item_id'],
                                        'wholesale_price' => "0",
                                        'price'           => "0",
                                        'sample_price'    => "0",
                                    ];

                $price = $item['price'];

                if ($salesType == 'W') {
                    $customerPriceData['wholesale_price'] = $price;
                }elseif ($salesType == 'R') {
                    $customerPriceData['price'] = $price;
                }else{
                    $customerPriceData['sample_price'] = $price;
                }
                if ($customerPrice) {
                    CustomerItemPrice::where('customer_id',$request->input('customer_id'))
                    ->where('material_id',$item['item_id'])
                    ->update($customerPriceData);

                }else{
                    CustomerItemPrice::create($customerPriceData);
                }

            }
        }
        return redirect()->route('order.index')->with('success', 'Order Updated successfully');
    }

    public function updatenew(Request $request)
    {
        // echo "<pre>"; print_r($request->all());
        $order = Order::find($request->input('id'));
        $order->note = $request->note?$request->note:null;
        $order->remark = $request->remark?$request->remark:null;
        $order->update();
        return redirect()->route('order.index')->with('success', 'Order Detail Updated successfully');
    }

    public function updateOrderItem(Request $request)
    {

        $orderItem=OrderItem::findOrFail($request->input('orderItemId'));
        $item_data= [
                        "type_of_sale" => $request->input('type_of_sale'),
                        "meter"        => $request->input('meter'),
                        "price"        => $request->input('price'),
                    ];
        $orderItem->fill($item_data);
        $orderItem->save();

        $customerPrice = CustomerItemPrice::where('customer_id',$request->customer_id)
                        ->where('material_id',$orderItem->item_id)
                        ->select('customer_id','material_id')
                        ->first();

        $salesType = $request->input('type_of_sale');

        $customerPriceData =[
                                'customer_id'    => $request->customer_id,
                                'material_id'    => $orderItem->item_id,
                                'wholesale_price'=> "0",
                                'price'          => "0",
                                'sample_price'   => "0",
                            ];

        $price = $request->input('price');

        if ($salesType == 'W') {
            $customerPriceData['wholesale_price'] = $price;
        }elseif ($salesType == 'R') {
            $customerPriceData['price'] = $price;
        }else{
            $customerPriceData['sample_price'] = $price;
        }

        if ($customerPrice) {
            CustomerItemPrice::where('customer_id',$request->customer_id)
            ->where('material_id',$orderItem->item_id)
            ->update($customerPriceData);
        }else{
            CustomerItemPrice::create($customerPriceData);
        }
        return redirect()->route('order.edit', $orderItem->order_id)->with('success', 'order Item successfully updated.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('order.index')->with('success', 'Order successfully deleted.');
    }

    public function deleteOrderItem(OrderItem $orderItem,$customerId)
    {
        // CustomerItemPrice::where('customer_id',$customerId)->where('material_id',$orderItem->item_id)->delete();
        $orderItem->delete();
        return redirect()->route('order.edit', $orderItem->order_id)->with('success', 'Order Item successfully deleted.');
    }

    public function changeOrderStatus(Request $request)
    {
        $orders = Order::where('id',$request->order_id)->first();
        // echo "<pre>"; print_r($orders); die();
        if ($orders != '') 
        {
            if ($orders->status == 1) 
            {
                $order_status = 0;
            }
            else
            {
                $order_status = 1;
            }
            $order_remark = $request->remark ? $request->remark : '';
            Order::where('id',$request->order_id)->update(['status'=>$order_status,'remark'=>$order_remark]);
            return redirect()->route('order.index')->with('success', 'Order status update successfully.');
        }
        else
        {
            return redirect()->route('order.index')->with('errors', 'Order not found.');
        }
    }

    public function print_order_barcode($id)
    {

        $order_data = Order::where('id', $id)->with('customer','seller')->first();
        $order_items=OrderItem::where('order_id','=',$id)->with('item','color','purchase','purchase_items')->get();

        // role history 
        $role_data = OrderItem::leftjoin('purchase_items',DB::raw("FIND_IN_SET(order_items.roll_id, purchase_items.id)"), ">", DB::raw("'0'"))
        ->leftJoin('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
        ->select('order_items.*','purchases.pcs_no as pcs_no',)
        ->orderBy('order_items.id', 'ASC')
        ->groupBy('purchase_items.id')
        ->get();
        if (count($role_data) > 0) {
            $roles_data = $role_data->toArray();
            $role_id = explode(",",implode(",",array_column($roles_data,'roll_id')));
            $purchase_items = PurchaseItem::whereIn('id',$role_id)->get();
        }
        // echo "<pre>"; print_r($order_data); die();
        // echo "<pre>"; print_r($order_items->toArray()); die();
        // echo "<pre>"; print_r($purchase_items->toArray()); die();
        $data = [
            'order_data'         => $order_data, 
            'order_items'        => $order_items, 
            'purchase_items'     => $purchase_items, 
        ];
        return view('order.print-barcode', $data);
    }
    public function getCustomerOrders(Request $request)
    {
        // echo $request->user_id; die();
        // $order_data = Order::where("customer_id",$request->user_id);
        $response = [];
        $order_item_data = [];
        $orders     = new Order;
        $order_data = $orders->where("customer_id",$request->user_id)->orderBy('id','DESC')->get();
        if (count($order_data) > 0) 
        {
            foreach ($order_data as $key => $value) 
            {
                $order_items = OrderItem::where("order_id",$value->id)->orderBy('id','DESC')->get();
                if ($order_items != '') 
                {
                    foreach ($order_items as $key => $row) 
                    {
                        $order_item_data[] = [
                            'id' => $row->id,
                            'type_of_sale' => $row->type_of_sale,
                            'meter' => $row->meter,
                            'price' => $row->price,
                        ];
                    }
                }
                $response[] = [
                    'id' => $value->id,
                    // 'order_date' => date("d-m-Y",strtotime($value->order_date)),
                    'order_date' => $value->order_date,
                    'note' => $value->note?$value->note:'',
                    'order_item_data' => $order_item_data,
                ];
                unset($order_item_data);
            }
            return response()->json(["response" => $response,"status" => 200]);
        }
        else
        {
            return response()->json(["response" => $response,"status" => 201]);
        }
    }
    
    public function getOrderItems($id)
    {
        $orderItems = OrderItem::leftJoin('materials as mat', DB::raw("FIND_IN_SET(mat.id, order_items.roll_id)"), ">", DB::raw("'0'"))
                                ->where('order_id', $id)
                                ->with('item')
                                ->select('order_items.*',DB::raw("group_concat(mat.article_no) as materials_name"))
                                ->groupBy('order_items.roll_id')
                                ->get();
        
        foreach ($orderItems as $item) {
            $item->barcode_svg = DNS1D::getBarcodeSVG($item->item->barcode, 'C128', 2, 40);
        }
        return response()->json($orderItems);
    }

    public function getOrderItemById($id)
    {
        $orderItem = OrderItem::with('item', 'color', 'purchase')
                    ->where('id', $id)
                    ->first();

        if ($orderItem) {
            return response()->json(['data' => $orderItem]);
        } else {
            return response()->json(['message' => 'Order Item not found'], 404);
        }
    }

    public function deleteAjaxOrderItem($orderItemId)
    {
        $orderItem = OrderItem::where('id', $orderItemId)->first();

        if ($orderItem) {
            $orderItem->delete();
            return response()->json(['success' => true, 'message' => 'Order item deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Order item not found.'], 404);
        }
    }
}
