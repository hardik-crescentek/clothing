<?php
namespace App\Http\Controllers;

use App\Purchase;
use App\PurchaseItem;
use Illuminate\Http\Request;
use App\Material;
use App\Category;
use App\Color;
use App\Supplier;
use App\InvoiceItemRoll;
use App\PurchaseImportFiles;
use App\Order;
use App\OrderItem;
use App\Utils\Util;
use Validator;
use Auth;
use Excel;
use File;
use App\Imports\PurchaseImport;
use App\Imports\PurchaseItemsimport;
use App\Imports\StockIncoming;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $purchases = Purchase::with('supplier')->orderBy('id','DESC')->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        $purchases = Purchase::leftJoin('purchase_items','purchase_items.purchase_id','=','purchases.id')
        ->with('supplier')->orderBy('id','DESC')
        ->select('purchases.*','purchase_items.available_qty as available_qty')
        ->get();
        // echo "<pre>"; print_r($purchases); die();
        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::active()->pluck('name', 'id')->all();
        $materials = Material::active()->orderBy('name','ASC')->get()->pluck('name', 'name');
        $materials2 = Material::active()->get();
        $colors = Color::active()->pluck('name', 'id')->all();
        $suppliers = Supplier::dropdown();
        $items = $request->session()->get('purchase_items', []);
        $invoiceNumbers = \DB::table('purchases')->pluck('invoice_no', 'invoice_no')->toArray();
        $articleNumbers = Material::active()->pluck('article_no', 'article_no')->toArray();
        $colorMaterial = Material::active()->pluck('color', 'color')->toArray();
        return view('purchase.create', compact('categories', 'colors', 'materials','materials2', 'suppliers', 'items','invoiceNumbers','articleNumbers','colorMaterial'));
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
            'purchase_date'        => 'required',
            'invoice_no'           => 'required',
            'supplier_id'          => 'required',
            'purchase_type'        => 'required',
            'currency_of_purchase' => 'required',
            'ex_rate'              => 'required',
            'total_meter'          => 'required',
            'total_yard'           => 'required',
            'import_tax'           => 'required',
            'transport_shipping_paid' => 'required',
            'transport_shippment_cost_per_meter' => 'required',
            // Add validation for file attachments
            'attach_documents.*'   => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048'
            // 'currency_type' => 'required',
            // 'price'                => 'required',
        ]);
       
        $total_qty = 0;
        $items = [];
        $materials = $request->input('color');
        if (is_array($materials)) {
            foreach ($materials as $key => $value) {
                $items[] = array(
                                'material_id'=> $value,
                                'color'      => $request->input('color.' . $key),
                                'article_no' => $request->input('article_no.' . $key),
                                'color_no'   => $request->input('color_no.' . $key),
                                'batch_no'   => $request->input('batch_no.' . $key),
                                'roll_no'    => $request->input('roll_no.' . $key),
                                'width'      => $request->input('width.' . $key),
                                'meter'      => $request->input('meter.' . $key),
                                'yard'       => meter2yard($request->input('meter.' . $key)),
                                'piece_no'   => $request->input('piece_no.' . $key),
                            );
                $total_qty +=  $request->input('meter.' . $key);
            }
        }
        $request->session()->flash('purchase_items', $items);

        $collection = collect($items);
        $items = $collection->sortBy('meter');
        
        $user = Auth::user();

        // $tax_per_meter = $request->input("total_tax") / $total_qty;
        // $shipping_cost_per_meter = $request->input("shipping_cost");
        // $usd_price_per_meter = $request->input("price");
        // $thb_price_per_meter = $request->input("price_thb");
        // $thb_ex_rate = $request->input("thb_ex_rate");
        // $attachment = Util::uploadFile($request, 'attach_document', config('constants.purchase_attachment'));

        // Handle multiple file uploads
        $attachments = [];
        if ($request->hasFile('attach_documents')) {
            foreach ($request->file('attach_documents') as $file) {
                $filename = $file->store('purchase_attachments', 'public'); // Store file in public storage
                $attachments[] = $filename; // Collect the file paths
            }
        }


        $data = [
            "invoice_no"              => $request->input("invoice_no"),
            "purchase_date"           => $request->input("purchase_date"),
            "user_id"                 => $user->id,
            "supplier_id"             => $request->input("supplier_id"),
            "purchase_type"           => $request->input("purchase_type"),
            "currency_of_purchase"    => $request->input("currency_of_purchase"),
            "ex_rate"                 => $request->input("ex_rate"),
            "total_meter"             => $request->input("total_meter"),
            "total_yard"              => $request->input("total_yard"),
            "import_tax"              => $request->input("import_tax"),
            "transport_shipping_paid"  => $request->input("transport_shipping_paid"),
            "discount"                => $request->input("discount"),
            "transport_shippment_cost_per_meter"  => $request->input("transport_shippment_cost_per_meter"),
            "note"                    => $request->input("note"),
            // "attachment"                 => $attachment,
            "attachment"                 => json_encode($attachments),

            // "total_qty"               => $total_qty,
            // "total_tax"               => $request->input("total_tax"),
            // "shipping_cost_per_meter" => $request->input("shipping_cost"),
            // "price"                   => $usd_price_per_meter,
            // "thb_ex_rate"             => $thb_ex_rate,
            // "price_thb"               => $thb_price_per_meter,
            // "payment_terms"           => $request->input("payment_terms"),
            // "shipping_paid"           => $request->input("shipping_paid"),
            // "transportation"          => $request->input("transportation"),
            // "gross_tax"               => $request->input("gross_tax"),
            // "shippment_cost_shipper"  => $request->input("shippment_cost_shipper"),
            // "shippment_cost_destination" => $request->input("shippment_cost_destination"),
        ];

        $purchase = Purchase::create($data);

        // if ($items) {
        //     $QRCode = Util::generateID();
        //     $sort_order = 1;

        //     foreach ($items as $item) {
        //         $color=Material::where('id','=',$item['color'])->first();
        //         $barcode = Util::generateID();
        //         $new_code = Util::gen_new_barcode_id($item["article_no"]);
        //         $qty = $item["meter"];
        //         $item_data = [
        //             "purchase_id"=> $purchase->id,
        //             "material_id"=> $item["color"],
        //             "article_no" => $item["article_no"],
        //             "color"      => $color->color,
        //             "color_no"   => $item["color_no"],
        //             "batch_no"   => $item["batch_no"],
        //             "roll_no"    => $sort_order,
        //             "barcode"    => $new_code,
        //             // "barcode"    => $barcode,
        //             "qrcode"     => $QRCode,
        //             "width"      => $item["width"],
        //             "qty"        => $qty,
        //             "available_qty"=> $qty,
        //             'sort_order' => $sort_order,
        //             // "price_usd" => $qty * $usd_price_per_meter,
        //             // "thb_ex_rate" => $thb_ex_rate,
        //             // "price_thb" => $qty * $thb_price_per_meter,
        //             // "total_tax" => $tax_per_meter * $qty,
        //             // "shipping_cost" => $shipping_cost_per_meter * $qty,
        //             // "discount" => $request->input("discount"),
        //             'attach_documents.*'   => 'mimes:jpeg,jpg,png,pdf,doc,docx', // Validate each file
        //             "piece_no" => $item["piece_no"]
        //         ];
        //         PurchaseItem::create($item_data);
        //         $sort_order++;
        //     }
        // }

        return redirect()->route('purchase-item.create')->with('success', 'Purchase added successfully');
    }


     public function import()
    {   
        $purchases = \Session::get('purchases');
        if (!empty($purchases)) 
        {
            $suppliers = Supplier::dropdown();
            return view('purchase.import', compact('purchases','suppliers'));
        }
        else
        {
            $suppliers = Supplier::dropdown();
            return view('purchase.import', compact('purchases','suppliers'));
        }
    }


    public function import_store(Request $request)
    {
        $this->validate($request, [
            'purchase_file'        => 'required|mimes:xlsx, csv, xls'
        ]);

        // $path = $request->file('purchase_file')->getRealPath();
        $path = $request->file('purchase_file');
        // $model_name = new PurchaseImport($request->supplier_id);
        // $model_name = new PurchaseItemsimport($request->supplier_id);
        $model_name = new StockIncoming($request->supplier_id);
        $data = Excel::import($model_name, $path);

        $path_org = $request->file('purchase_file');
        $file_org_name = $request->file('purchase_file')->getClientOriginalName();
        $chk_files = PurchaseImportFiles::where('file_name',$file_org_name)->first();
        if (!empty($chk_files) && $chk_files != '') 
        {
            return redirect()->route('purchase.importt')->with('error', 'This purchase file is already uploaded at : '.date("d-m-Y h:i A",strtotime($chk_files->created_at)));
        }
        // echo "<pre>"; print_r($chk_files); die();

        $public_path = public_path('uploads/purchase_import_files/');
        if(!File::isDirectory($public_path)){
            File::makeDirectory($public_path, 0777, true, true);
        }
        $path_org->move($public_path, $file_org_name);

        $purchaseImportFiles = new PurchaseImportFiles();
        $purchaseImportFiles->file_name = $file_org_name;
        $purchaseImportFiles->save();

        
        // echo "<pre>"; print_r($model_name->getRowCount()); die();
        $purchase_idss = implode(",", $model_name->getRowCount());
        $purchases_data = Purchase::whereIn('id',explode(",",$purchase_idss))->with('supplier')->orderBy('id','DESC')->get();
        $purchases = $purchases_data;
        return \Redirect::back()->with( ['purchases' => $purchases]);
    }

    public function print_all(Request $request)
    {
        $purchase = array();
        if ($request->purchase_id != '') 
        {
            $purchase_ids[] = "";
            foreach ($request->purchase_id as $key => $value) 
            {
                $purchase_ids[] = $value;
            }
            $purchase = Purchase::with('purchase_items')->whereIn('id', $purchase_ids)->get();
        }
        // echo "<pre>"; print_r($purchase); die();
        return view('purchase.print-all-barcode', compact('purchase'));
    }
    
    public function supplier_details($id)
    {
        $supplier   = Supplier::select('name')->where('id',$id)->first();
        $purchases = Purchase::where('supplier_id',$id)->with('supplier')->orderBy('id','DESC')->get();
        return view('purchase.supplier_details', compact('purchases','supplier'));
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        // dd($purchase);
        $categories = Category::active()->pluck('name', 'id')->all();
        $materials = Material::active()->orderBy('name','ASC')->get()->pluck('name', 'name');
        $materials2 = Material::active()->get();
        $colors = Color::active()->pluck('name', 'id')->all();
        $suppliers = Supplier::dropdown();
        $items = $purchase->purchase_items()->paginate(env('ITEMS_PER_PAGE'));
        $invoiceNumbers = \DB::table('purchases')->pluck('invoice_no', 'invoice_no')->toArray();
        $articleNumbers = Material::active()->pluck('article_no', 'article_no')->toArray();
        $colorMaterial = Material::active()->pluck('color', 'color')->toArray();
        $orders = OrderItem::leftJoin('orders','orders.id','=','order_items.order_id')
        ->leftJoin('users as customer','customer.id','=','orders.customer_id')
        ->leftJoin('users as seller','seller.id','=','orders.seller_id')
        ->leftJoin('purchase_items','purchase_items.id','=','order_items.item_id')
        ->leftJoin('materials','materials.id','=','purchase_items.material_id')
        ->with('invoice')
        ->whereRaw('FIND_IN_SET("'.$purchase->id.'", roll_id)')
        ->select('order_items.*','orders.*','customer.firstname as customer_firstname','customer.lastname as customer_lastname','seller.firstname as seller_firstname','seller.lastname as seller_lastname','purchase_items.*','materials.name as material_name')
        ->get();
        return view('purchase.edit', compact('purchase', 'categories', 'colors', 'materials','materials2', 'suppliers', 'items','orders','invoiceNumbers','articleNumbers','colorMaterial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        $this->validate($request, [
            'purchase_date'        => 'required',
            'invoice_no'           => 'required',
            'supplier_id'          => 'required',
            'purchase_type'        => 'required',
            'currency_of_purchase' => 'required',
            'ex_rate'              => 'required',
            'total_meter'          => 'required',
            'total_yard'           => 'required',
            'import_tax'           => 'required',
            'transport_shipping_paid' => 'required',
            'transport_shippment_cost_per_meter' => 'required',
            'attach_documents.*'   => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048'
        ]);
       
        $total_qty = $purchase->total_qty;
        $items = [];
        $materials = $request->input('color');
        
        if (is_array($materials)) {
            foreach ($materials as $key => $value) {
                $items[] = array(
                    'material_id' => $value,
                    'color'       => $request->input('color.' . $key),
                    'article_no'  => $request->input('article_no.' . $key),
                    'color_no'    => $request->input('color_no.' . $key),
                    'batch_no'    => $request->input('batch_no.' . $key),
                    'roll_no'     => $request->input('roll_no.' . $key),
                    'width'       => $request->input('width.' . $key),
                    'meter'       => $request->input('meter.' . $key),
                    'yard'        => meter2yard($request->input('meter.' . $key)),
                    'piece_no'   => $request->input('piece_no.' . $key),
                );
                $total_qty +=  $request->input('meter.' . $key);
            }
        }

        // $attachment = Util::uploadFile($request, 'attach_document', config('constants.purchase_attachment'));
        $usd_price_per_meter = $request->input("price");
        $thb_price_per_meter = $request->input("price_thb");
        $thb_ex_rate = $request->input("thb_ex_rate");

         // Handle multiple file uploads
        if ($request->hasFile('attach_documents')) {
            $attachments = [];
            foreach ($request->file('attach_documents') as $file) {
                $filename = $file->store('purchase_attachments', 'public'); // Store file in public storage
                $attachments[] = $filename; // Collect the file paths
            }
        } else {
                $attachments = json_decode($purchase->attachment, true);
        }
 
        $user = Auth::user();

        $data = [
                    "invoice_no"                 => $request->input("invoice_no"),
                    "purchase_date"              => $request->input("purchase_date"),
                    "user_id"                    => $user->id,
                    "supplier_id"                => $request->input("supplier_id"),
                    "purchase_type"              => $request->input("purchase_type"),
                    "currency_of_purchase"       => $request->input("currency_of_purchase"),
                    "ex_rate"                    => $request->input("ex_rate"),
                    "total_meter"                => $request->input("total_meter"),
                    "total_yard"                 => $request->input("total_yard"),
                    "import_tax"                 => $request->input("import_tax"),
                    "transport_shipping_paid"    => $request->input("transport_shipping_paid"),
                    "discount"                   => $request->input("discount"),
                    "transport_shippment_cost_per_meter"  => $request->input("transport_shippment_cost_per_meter"),
                    "note"                       => $request->input("note"),
                    "attachment"                 => json_encode($attachments),


                    // "total_qty"                  => $total_qty,
                    // "total_tax"                  => $request->input("total_tax"),
                    // "shipping_cost_per_meter"    => $request->input("shipping_cost_per_meter"),
                    // "price"                      => $usd_price_per_meter,
                    // "thb_ex_rate"                => $thb_ex_rate,
                    // "price_thb"                  => $thb_price_per_meter,
                    // "payment_terms"              => $request->input("payment_terms"),
                    // "shipping_paid"              => $request->input("shipping_paid"),
                    // "transportation"             => $request->input("transportation"),
                    // "gross_tax"                  => $request->input("gross_tax"),
                    // "shippment_cost_shipper"     => $request->input("shippment_cost_shipper"),
                    // "shippment_cost_destination" => $request->input("shippment_cost_destination"),
                ];

        $purchase->fill($data);
        $purchase->save();

        if ($items) {
            $QRCode = Util::generateID();
            foreach ($items as $item) {
                $color=Material::where('id','=',$item['color'])->first();
                $barcode = Util::generateID();
                $qty = $item["meter"];
                $item_data = [
                                "purchase_id" => $purchase->id,
                                "material_id" => $item["color"],
                                "color"       => $color->color,
                                "color_no"    => $item["color_no"],
                                "article_no"  => $item["article_no"],
                                "batch_no"    => $item["batch_no"],
                                "roll_no"     => $item["roll_no"],
                                "barcode"     => $barcode,
                                "qrcode"      => $QRCode,
                                "width"       => $item["width"],
                                "qty"         => $qty,
                                "available_qty"=> $qty,
                                "piece_no"    => $item["piece_no"]
                                // "price_usd" => $qty * $usd_price_per_meter,
                                // "thb_ex_rate" => $thb_ex_rate,
                                // "price_thb" => $qty * $thb_price_per_meter,
                                // "total_tax" => $tax_per_meter * $qty,
                                // "shipping_cost" => $shipping_cost_per_meter * $qty,
                                // "discount" => $request->input("discount"),
                            ];
                PurchaseItem::create($item_data);
            }
        }

        return redirect()->route('purchase.index')->with('success', 'Purchase items updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchase.index')->with('success', 'Purchase successfully deleted.');
    }

    public function deletePurchaseItem(PurchaseItem $purchaseItem)
    {
        $purchase = $purchaseItem->purchase;
        $purchase->total_qty = $purchase->total_qty - $purchaseItem->available_qty;
        $purchase->save();
        $purchaseItem->delete();
        return redirect()->route('purchase.edit', $purchaseItem->purchase_id)->with('success', 'Purchase Item successfully deleted.');
    }

    public function updatePurchaseItem(Request $request)
    {
        
        $purchaseItemId = $request->input('purchaseItemId');
        $purchaseItem = PurchaseItem::findOrFail($purchaseItemId);

        $qty =  $request->input('qty');

        $purchase = $purchaseItem->purchase;
        $purchase->total_qty = ($purchase->total_qty - $purchaseItem->qty) + $qty;
        $purchase->save();
        $material=Material::find($request->input('color_id'));
        $data = array(
                    'material_id' => $material->id,
                    "color"       => $material->color,
                    "color_no"    => $request->input("color_no"),
                    'article_no'  => $request->input('article_no'),
                    'batch_no'    => $request->input('batch_no'),
                    'roll_no'     => $request->input('roll_no'),
                    // 'width'   => $request->input('width'),
                    "qty"         => $qty,
                    "available_qty" => $qty - ($purchaseItem->qty - $purchaseItem->available_qty),
                );
        
        $purchaseItem->fill($data);
        $purchaseItem->save();
        return redirect()->route('purchase.edit', $purchaseItem->purchase_id)->with('success', 'Purchase Item successfully updated.');

    }
    public function rollHistory(Request $request)
    {
        return InvoiceItemRoll::where('roll_id','=',$request->input('item_id'))->with('invoice.customer','invoice_item')->get();
    }

    public function multipleDelete(Request $request) {

        $selectedItems = $request->selectedItems;

        $purchases = Purchase::whereIn('id', $selectedItems)->delete();

        if($purchases) {
            
            $purchasesItems = PurchaseItem::whereIn('purchase_id', $selectedItems)->delete();
            return response()->json(['success' => true, 'message' => 'Selected Purchase deleted successfully.'], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'Something went wrong.'], 201);
        }
    }

    
    public function getSuppliers(Request $request)
    {
        // Implement logic to fetch purchase type and currency type based on $supplierId
        $supplierId = $request->input('supplier_id');
        $supplier = Supplier::find($supplierId);

        // Initialize variables for purchase_type and currency_type
        $purchaseType = null;
        $currencyType = null;

        if ($supplier) {
            $purchaseType = $supplier->supplier_type; // Adjust according to your database structure
            $currencyType = $supplier->currency_type; // Adjust according to your database structure
        }

        return response()->json([
            'purchase_type' => $purchaseType,
            'currency_type' => $currencyType
        ]);
    }

}
