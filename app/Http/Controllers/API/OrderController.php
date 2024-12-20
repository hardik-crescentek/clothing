<?php

namespace App\Http\Controllers\API;

use App\Order;
use App\OrderItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\User;
use App\Supplier;
use App\Material;
use App\Color;
use App\PurchaseItem;
use App\Invoice;
use App\InvoiceItem;
use App\InvoiceItemRoll;
use App\CustomerItemPrice;
use Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->role;
        $where = [];
        $where[] = ['status', '!=', 0 ];
        if ($user->role_name == 'sales-person') {
            $where[] = ['seller_id', $user->id];
        }
        if ($user->role_name == 'client') {
            $where[] = ['customer_id', $user->id];
        }
        $orders = Order::where($where)->with('customer', 'seller', 'invoice', 'order_items')->orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => true,
            'message' => 'Order List',
            'data' => $orders
        ], 200);
    }

    public function myOrders(Request $request)
    {
        $user = $request->user();
        $user->role_name = $user->roles->pluck('name')->first();
        $where = [];
        $where[] = ['status', '!=', 0 ];
        if ($user->role_name == 'sales-person') {
            $where[] = ['seller_id', $user->id];
        } else {
            $where[] = ['customer_id', $user->id];
        }
        $orders = Order::where($where)->with('customer', 'seller', 'invoice', 'order_items.item')->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'message' => 'Order List',
            'data' => $orders
        ], 200);
    }

    public function myBookings(Request $request)
    {
        $user = $request->user();
        $user->role_name = $user->roles->pluck('name')->first();
        $where = [];
        $where[] = ['status', 0 ];
        if ($user->role_name == 'sales-person') {
            $where[] = ['seller_id', $user->id];
        } else {
            $where[] = ['customer_id', $user->id];
        }
        $orders = Order::where($where)->with('customer', 'seller', 'invoice', 'order_items.item')->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'message' => 'Booking List',
            'data' => $orders
        ], 200);
    }

    public function getCustomerOrders(Request $request)
    {
        $id = $request->get('id');
        if (empty($id)) {
            $response['data'] = null;
            $response['success'] = false;
            $response['message'] = 'Customer not found';
            return response()->json($response, 200);
        }
        $where = [];
        $where[] = ['customer_id', $id ];
        $where[] = ['status', '!=', 0 ];
        $orders = Order::where($where)->with('customer', 'seller', 'invoice', 'order_items.item')->orderBy('id', 'DESC')->get();
        // dd($orders);

        return response()->json([
            'status' => true,
            'message' => 'Customer Orders ',
            'data' => $orders
        ], 200);
    }
    public function getCustomerBookings(Request $request)
    {
        $id = $request->get('id');
        if (empty($id)) {
            $response['data'] = null;
            $response['success'] = false;
            $response['message'] = 'Customer not found';
            return response()->json($response, 200);
        }
        $where = [];
        $where[] = ['customer_id', $id ];
        $where[] = ['status', 0 ];
        $orders = Order::where($where)->with('customer', 'seller', 'invoice', 'order_items.item')->orderBy('id', 'DESC')->get();
        // dd($orders);

        return response()->json([
            'status' => true,
            'message' => 'Customer Bookings ',
            'data' => $orders
        ], 200);
    }
    // public function getSellerOrders(Request $request)
    // {
    //     $id = $request->get('id');
    //     if (empty($id)) {
    //         $response['data'] = null;
    //         $response['success'] = false;
    //         $response['message'] = 'Seller not found';
    //         return response()->json($response, 200);
    //     }
    //     $orders = Order::where('seller_id', $id)->with('customer', 'seller', 'invoice', 'order_items')->orderBy('id', 'DESC')->get();
    //     // dd($orders);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Seller Orders ',
    //         'data' => $orders
    //     ], 200);
    // }

    public function getSellerOrderClients(Request $request)
    {
        $user = $request->user();
        $orders = Order::select('customer_id', 'seller_id')->where('seller_id', $user->id)->with('customer')->distinct('customer_id')->get();
        // dd($orders);

        return response()->json([
            'status' => true,
            'message' => 'Seller Clients ',
            'data' => $orders
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = [
            'success' => false,
            'data'    => null,
            'message' => null,
        ];

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'items' => 'required',
        ]);
        if ($validator->fails()) {

            $response['data'] = ['error' => $validator->errors()];
            $response['success'] = false;
            $response['message'] = implode(",", $validator->errors()->all());
            return response()->json($response, 200);
        }


        $total_qty = 0;
        $orderItems = [];
        $items = $request->get('items');
        if (is_array($items)) {
            foreach ($items as $item) {
                $material = Material::find($item['item_id']);
                if (!$material) {
                    continue;
                }
                $orderItems[] = array(
                    'item_id' => $material->id,
                    'name' => $material->name,
                    'type_of_sale' => $request->type_of_sale ?? 'R',
                    'price' => 0,
                    'meter' => $item['meter'],
                    'yard' => meter2yard($item['meter'])
                );
                $total_qty += $item['meter'];
            }
        }
        $data = [
            "customer_id" => $request->customer_id,
            "seller_id" => $request->seller_id ?? 0,
            "order_date" => '',
            "name" => $request->name,
            "mobile" => $request->mobile,
            "address" => $request->address,
            "note" => $request->note,
            "booking_days" => $request->booking_days,
            "status" => $request->booking ? 0 : 1,
            "is_api" => 1,
        ];



        if ($orderItems) {
            $order = Order::create($data);
            foreach ($orderItems as $item) {
                $item_data = $item;
                $item_data["order_id"] = $order->id;
                OrderItem::create($item_data);
            }
            return response()->json([
                'status' => true,
                'message' => 'Order Successfully Created',
                'data' => Order::with(['customer', 'seller', 'order_items'])->find($order->id)
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid Request data',
            'data' => null
        ], 404);
    }

    public function single(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'Invalid request',
            'data' => null
        ];

        $id = $request->get('id');
        if (empty($id)) {
            $response['message'] = 'Please provide Order Id';
            return response()->json($response, 200);
        }

        $order = Order::with('customer', 'seller', 'invoice', 'order_items.item')->find($id);
        // dd($orders);
        if ($order) {
            $response['status'] = true;
            $response['message'] = 'Order Info';
            $response['data'] = $order;
            $response['order_status'] = config('constants.order_status');
            return response()->json($response, 200);
        }

        $response['data'] = null;
        $response['status'] = true;
        $response['message'] = 'Order not found';
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {

        $users = ['' => '--Select Customer--'];
        $users += User::all()->pluck("fullName", 'id')->toArray();
        $sales_user = new User();
        $sales_person = ["" => '--Select Sales Person--'];
        $sales_person += $sales_user->role('sales-person')->get()->pluck('fullName', 'id')->toArray();
        $colors = Color::active()->pluck('name', 'id')->all();
        $items = OrderItem::where('order_id', '=', $order->id)->with('item', 'color')->get();
        // $items=$order->order_items;
        return view('order.edit', compact('order', 'users', 'sales_person', 'colors', 'items'));
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
                    'item_id' => $value,
                    'name' => $request->input('a_name.' . $key),
                    'user_id' => $request->input('user_id.' . $key),
                    'selse_person_id' => $request->input('selse_person_id.' . $key),
                    'barcode' => $request->input('a_barcode.' . $key),
                    'type_of_sale' => $request->input('a_type_of_sale.' . $key),
                    'price' => $request->input('a_price.' . $key),
                    'meter' => $request->input('a_meter.' . $key),
                    'yard' => meter2yard($request->input('a_meter.' . $key))
                );
            }
        }
        $data = [
            "customer_id" => $request->input('customer_id'),
            "seller_id" => $request->input('seller_id'),
            "order_date" => $request->input('purchase_date'),
            "note" => $request->input("note"),
        ];
        $order->fill($data);
        $order->save();
        if ($items) {
            foreach ($items as $item) {
                $item_data = [
                    "order_id" => $order->id,
                    "item_id" => $item['item_id'],
                    "type_of_sale" => $item['type_of_sale'],
                    "meter" => $item['meter'],
                    "price" => $item['price'],
                ];
                OrderItem::insert($item_data);
            }
        }
        return redirect()->route('order.index')->with('success', 'Order Updated successfully');
    }

    public function updateOrderItem(Request $request)
    {
        $orderItem = OrderItem::findOrFail($request->input('orderItemId'));
        $item_data = [
            "type_of_sale" => $request->input('type_of_sale'),
            "meter" => $request->input('meter'),
            "price" => $request->input('price'),
        ];
        $orderItem->fill($item_data);
        $orderItem->save();
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

    public function deleteOrderItem(OrderItem $orderItem)
    {
        $orderItem->delete();
        return redirect()->route('order.edit', $orderItem->order_id)->with('success', 'Order Item successfully deleted.');
    }
    // public function generateInvoice(Order $order)
    // {

    //     $users = User::pluck('firstname', 'id');
    //     $sales_user = new User();
    //     $sales_person = $sales_user->role('sales-person')->pluck('firstname', 'id');
    //     $items=OrderItem::where('order_id','=',$order->id)->with('item','color')->get();
    //     $rolls=PurchaseItem::pluck('roll_no','id');
    //     return view('order.generateInvoice',compact('order','users','sales_person','items','rolls'));
    // }
    // public function getRollData(Request $request)
    // {
    //     $response=[];
    //         $roll=PurchaseItem::where('material_id','=',$request->input('material_id'))->get();
    //         $response=[
    //             'status'=>'success',
    //             'roll'=>$roll,
    //         ];
    //     return response()->json($response,200);
    // }
    // public function storeInvoice(Request $request)
    // {
    //     dd($request->all());
    //     die;
    //     $order=Order::where('id','=',$request->input('order_id'))->first();
    //     $invoice_data=[
    //         "invoice_no"=>$request->input('invoice_no'),
    //         "order_id"=>$order->id,
    //         "customer_id"=>$order->customer_id,
    //         "seller_id"=>$order->seller_id,
    //         "sub_total"=>$request->input('sub_total'),
    //         "tax"=>$request->input('tax'),
    //         "discount"=>$request->input('discount'),
    //         "grand_total"=>$request->input('grand_total'),
    //         "invoice_date"=>$request->input('generate_date'),
    //         "note"=>$order->note,
    //     ];
    //     $invoice=Invoice::create($invoice_data);
    //     User::where('id','=',$order->customer_id)->update(["last_invoice"=>$order->customer->last_invoice+1]);
    //     $order_items=OrderItem::where('order_id','=',$order->id)->get();
    //     $total_roll=0;
    //     $invoice_item;

    //     foreach($order_items as $order_item){
    //         foreach($request->input('item_roll') as $key => $value)
    //         {
    //             if($key==$order_item->id){
    //                 foreach($value as $k => $v){
    //                     $total_roll++;
    //                 }
    //                 break;
    //             }

    //         }
    //         $invoice_item_data=[
    //             "invoice_id"=>$invoice->id,
    //             "order_id"=>$order->id,
    //             "item_id"=>$order_item->item_id,
    //             "color_id"=>$order_item->color_id,
    //             "total_meter"=>$order_item->meter,
    //             "total_rolls"=>$total_roll,
    //             "price"=>$request->input('price'),
    //         ];
    //         $total_roll=0;
    //         $invoice_item=InvoiceItem::create($invoice_item_data);

    //         foreach($request->input('item_roll') as $item_id => $value)
    //         {
    //             if($item_id==$order_item->id){
    //                 foreach($value as $roll_id => $meter){
    //                     // echo " item id = ".$item_id." roll id = ".$roll_id." meter = ".$meter."<br />";
    //                     $roll_item=PurchaseItem::where('id','=',$roll_id)->first();
    //                     $invoice_item_roll_data=[
    //                         "invoice_item_id"=>$invoice_item->id,
    //                         "invoice_id"=>$invoice->id,
    //                         "roll_id"=>$roll_id,
    //                         "roll_no"=>$roll_item->roll_no,
    //                         "meter"=>$meter,
    //                     ];
    //                     InvoiceItemRoll::create($invoice_item_roll_data);

    //                 }
    //                 break;
    //             }
    //         }
    //     }
    //     return redirect()->route('order.index')->with('success', 'Invoice Created successfully');
    // }

    // public function search(Request $request)
    // {
    //     $q = $request->get('query');
    //     $data = new Material();
    //     if (is_numeric($q)) {
    //         $data = $data->where('barcode', 'LIKE', "%{$q}%")->get();
    //     } else {
    //         $data = $data->where('name', 'LIKE', "%{$q}%")->get();
    //     }

    //     $output = '<ul class="form-control" id="search-droplist" style="display:block; position:relative">';
    //     foreach ($data as $row) {
    //         $output .= '<li><h4><a id="' . $row->id . '" class="search-item" style="cursor:pointer;corsor:hand;">' . $row->name . ' ' . $row->barcode . '</a></h4></li>';
    //     }
    //     $output .= '</ul>';
    //     // return response()->json($data,200);
    //     echo $output;
    // }

    public function dashboardInfo(Request $request)
    {
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if (!$user->warehouse_id) {
            return response()->json(['error' => 'User does not have a warehouse'], 400);
        }

        $warehouseId = $user->warehouse_id;

        $orderStatuses = Order::where('warehouse_id', $warehouseId)
                                ->selectRaw("
                                    COUNT(*) as total_orders,
                                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as new_orders,
                                    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_orders,
                                    SUM(CASE WHEN status = 'Not Enough' THEN 1 ELSE 0 END) as not_enough_orders,
                                    SUM(CASE WHEN status = 'Out of Stock' THEN 1 ELSE 0 END) as out_of_stock_orders,
                                    SUM(CASE WHEN status = 'Damaged' THEN 1 ELSE 0 END) as damaged_orders")
                                ->first();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard information retrieved successfully',
            'data' => [
                'total_orders' => $orderStatuses->total_orders ?? 0,
                'new_orders' => $orderStatuses->new_orders ?? 0,
                'completed_orders' => $orderStatuses->completed_orders ?? 0,
                'not_enough_orders' => $orderStatuses->not_enough_orders ?? 0,
                'out_of_stock_orders' => $orderStatuses->out_of_stock_orders ?? 0,
                'damaged_orders' => $orderStatuses->damaged_orders ?? 0,
            ],
        ], 200);
    }

    public function orderList(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            if (!$user->warehouse_id) {
                return response()->json(['error' => 'User does not have a warehouse.'], 400);
            }

            $warehouseId = $user->warehouse_id;
            $orders = Order::where('warehouse_id', $warehouseId)->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No orders found.',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully.',
                'data' => $orders,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving orders.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:Pending,Completed,Not Enough,Out of Stock,Damaged',
                'status_date' => 'nullable|date_format:Y-m-d',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        
            // Find the order
            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 404);
            }

            $order->status = $validated['status'];
            $order->status_date = now(); 

            // Handle image upload
            if ($request->hasFile('image')) {
                $originalName = $request->file('image')->getClientOriginalName();
                $imagePath = $request->file('image')->storeAs('uploads/orders', $originalName, 'public'); // Store with the original name
                $order->image = $imagePath; // Save the image path to the order
            }

            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'data' => $order,
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the order status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
