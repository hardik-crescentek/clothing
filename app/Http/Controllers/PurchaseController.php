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
use App\PurchaseArticle;
use App\PurchaseArticleColor;
use App\Order;
use App\OrderItem;
use App\WareHouse;
use App\Utils\Util;
use Auth;
use Excel;
use File;
use App\Imports\PurchaseImport;
use App\Imports\PurchaseItemsimport;
use App\Imports\StockIncoming;
use DB;

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
        $purchases = Purchase::leftJoin('purchase_items', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->with('supplier')->orderBy('id', 'DESC')
            ->select('purchases.*', 'purchase_items.available_qty as available_qty')
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
        $materials = Material::active()->orderBy('name', 'ASC')->get()->pluck('name', 'name');
        $materials2 = Material::active()->get();
        $colors = Color::active()->pluck('name', 'id')->all();
        $suppliers = Supplier::dropdown();
        $items = $request->session()->get('purchase_items', []);
        $invoiceNumbers = \DB::table('purchases')->pluck('invoice_no', 'invoice_no')->toArray();
        $articleNumbers = Material::active()->pluck('article_no', 'article_no')->toArray();
        $colorMaterial = Material::active()->pluck('color', 'color')->toArray();
        $wareHouse = WareHouse::pluck('name', 'id')->all();
        return view('purchase.create', compact('categories', 'colors', 'materials', 'materials2', 'suppliers', 'items', 'invoiceNumbers', 'articleNumbers', 'colorMaterial','wareHouse'));
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
            'invoice_no'           => 'required|unique:purchases,invoice_no',
            'supplier_id'          => 'required',
            'purchase_type'        => 'required',
            'currency_of_purchase' => 'required',
            'ex_rate'              => 'required',
            'total_meter'          => 'required',
            'total_yard'           => 'required',
            'import_tax'           => 'required',
            'transport_shipping_paid' => 'required',
            'transport_shippment_cost_per_meter' => 'required',
            'attach_documents.*'   => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048',
            'no_of_rolls'          => 'required',
            'no_of_bales'          => 'required',
            'warehouse_id'         => 'required',
        ]);

        $total_qty = 0;
        $items = [];
        $materials = $request->input('color');
        if (is_array($materials)) {
            foreach ($materials as $key => $value) {
                $items[] = array(
                    'material_id' => $value,
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
            "attachment"                 => json_encode($attachments),
            "no_of_rolls"             => $request->input("no_of_rolls"),
            "no_of_bales"             => $request->input("no_of_bales"),
            "warehouse_id"            => $request->input("warehouse_id"),
        ];

        $purchase = Purchase::create($data);

        // Process each article and its colors

        $articles = $request->input('articles', []);

        $transformedArticles = [];
    
        foreach ($articles as $article) {
            $articleData = [
                'article' => $article['article'] ?? null,
                'article_id' => $article['article_id'] ?? null,
                'colors' => [],
            ];
    
            if (isset($article['colors']) && is_array($article['colors'])) {
                foreach ($article['colors'] as $colorId) {
                    // Fetch color details
                    $colorDetails = $this->getColorDetails($colorId); // Custom method to fetch color details
    
                    $articleData['colors'][] = [
                        'color_id' => $colorDetails['id'] ?? null,
                        'name' => $colorDetails['name'] ?? null,
                        'color_no' => $colorDetails['color_no'] ?? null,
                    ];
                }
            }
    
            $transformedArticles[] = $articleData;
        }

        // Iterate over the transformed articles and save them
        foreach ($transformedArticles as $articleData) {
            $article = $articleData['article'];
            $articleId = $articleData['article_id'];
            $colors = $articleData['colors'] ?? [];
            $purchaseArticle = new PurchaseArticle([
                'purchase_id' => $purchase->id,
                'material_id' => $article, // Use the correct material ID
                'article' => $article,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $purchaseArticle->save();
            foreach ($colors as $color) {
                $purchaseArticleColor = new PurchaseArticleColor([
                    'purchase_id' => $purchase->id,
                    'purchase_article_id' => $purchaseArticle->id,
                    'material_id' => $color['color_id'], // Set the material ID for color
                    'color' => $color['name'], // Set color name,
                    'color_no' => $color['color_no'], // Set color name,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $purchaseArticleColor->save();
                // Find the PurchaseArticle by ID and update its material_id
                $purchaseArticle = PurchaseArticle::find($purchaseArticle->id);
                if ($purchaseArticle) {
                    $purchaseArticle->material_id = $color['color_id']; // Update material_id
                    $purchaseArticle->updated_at = now();
                    $purchaseArticle->save(); // Save the changes
                }
            }
        }

        return redirect()->route('purchase-item.create')->with('success', 'Purchase added successfully');
    }

    private function getColorDetails($colorId)
    {
        // Fetch color details from database
        $color = DB::table('materials')->where('id', $colorId)->first(['id', 'color','color_no']);

       // Check if a color was found
        if ($color) {
            return [
                'id' => $color->id,
                'name' => $color->color,
                'color_no' => $color->color_no,
            ];
        }

        // Return default values if no color was found
        return [
            'id' => null,
            'name' => 'Unknown',
            'color_no' => 'Unknown',
        ];
    }


    public function import()
    {
        $purchases = \Session::get('purchases');
        if (!empty($purchases)) {
            $suppliers = Supplier::dropdown();
            return view('purchase.import', compact('purchases', 'suppliers'));
        } else {
            $suppliers = Supplier::dropdown();
            return view('purchase.import', compact('purchases', 'suppliers'));
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
        $chk_files = PurchaseImportFiles::where('file_name', $file_org_name)->first();
        if (!empty($chk_files) && $chk_files != '') {
            return redirect()->route('purchase.importt')->with('error', 'This purchase file is already uploaded at : ' . date("d-m-Y h:i A", strtotime($chk_files->created_at)));
        }
        // echo "<pre>"; print_r($chk_files); die();

        $public_path = public_path('uploads/purchase_import_files/');
        if (!File::isDirectory($public_path)) {
            File::makeDirectory($public_path, 0777, true, true);
        }
        $path_org->move($public_path, $file_org_name);

        $purchaseImportFiles = new PurchaseImportFiles();
        $purchaseImportFiles->file_name = $file_org_name;
        $purchaseImportFiles->save();


        // echo "<pre>"; print_r($model_name->getRowCount()); die();
        $purchase_idss = implode(",", $model_name->getRowCount());
        $purchases_data = Purchase::whereIn('id', explode(",", $purchase_idss))->with('supplier')->orderBy('id', 'DESC')->get();
        $purchases = $purchases_data;
        return \Redirect::back()->with(['purchases' => $purchases]);
    }

    public function print_all(Request $request)
    {
        $purchase = array();
        if ($request->purchase_id != '') {
            $purchase_ids[] = "";
            foreach ($request->purchase_id as $key => $value) {
                $purchase_ids[] = $value;
            }
            $purchase = Purchase::with('purchase_items')->whereIn('id', $purchase_ids)->get();
        }
        // echo "<pre>"; print_r($purchase); die();
        return view('purchase.print-all-barcode', compact('purchase'));
    }

    public function supplier_details($id)
    {
        $supplier   = Supplier::select('name')->where('id', $id)->first();
        $purchases = Purchase::where('supplier_id', $id)->with('supplier')->orderBy('id', 'DESC')->get();
        return view('purchase.supplier_details', compact('purchases', 'supplier'));
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
    public function edit(Purchase $purchase, Request $request)
    {
        // Fetch necessary data for the view
        $categories = Category::active()->pluck('name', 'id')->all();
        $materials = Material::active()->orderBy('name', 'ASC')->get()->pluck('name', 'name');
        $materials2 = Material::active()->get();
        $colors = Color::active()->pluck('name', 'id')->toArray();
        $suppliers = Supplier::dropdown();
        $items = $purchase->purchase_items()->paginate(env('ITEMS_PER_PAGE'));
        $invoiceNumbers = \DB::table('purchases')->pluck('invoice_no', 'invoice_no')->toArray();
        $articleNumbers = Material::active()->pluck('article_no', 'article_no')->toArray();
        $colorMaterial = Material::active()->pluck('color', 'color')->toArray();

        // Fetch articles for the purchase
        $articles = PurchaseArticle::where('purchase_id', $purchase->id)->get();

        // Fetch and group colors by purchase_article_id
        $articleColors = DB::table('purchase_article_colors')
            ->leftJoin('purchase_articles', 'purchase_articles.id', '=', 'purchase_article_colors.purchase_article_id')
            ->where('purchase_article_colors.purchase_id', $purchase->id)
            ->select('purchase_article_colors.*', 'purchase_articles.article')
            ->get()
            ->groupBy('purchase_article_id');

        // Get all colors available for each article
        $allMaterials = DB::table('materials')
            ->select('article_no', 'color', 'id as color_id') // Ensure `color_id` is selected
            ->get()
            ->groupBy('article_no');

        $articlesWithColors = $articles->map(function ($article) use ($articleColors, $allMaterials) {
            $selectedColors = $articleColors->get($article->id, collect())->map(function ($color) {
                return [
                    'color_id' => $color->material_id ?? null,
                    'name' => $color->color ?? ''
                ];
            })->toArray(); // Ensure it's an array
            
                $allColors = $allMaterials[$article->article]->map(function ($color) {
                    return [
                        'color_id' => $color->material_id ?? null,
                        'name' => $color->color ?? ''
                    ];
                })->toArray(); // Ensure it's an array
            
                return [
                    'article_id' => $article->material_id,
                    'article_name' => $article->article,
                    'colors' => $selectedColors, // Ensure it's an array
                    'all_colors' => $allColors // Ensure it's an array
                ];
            });    
                        
        // Fetch orders
        $orders = OrderItem::leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('users as customer', 'customer.id', '=', 'orders.customer_id')
            ->leftJoin('users as seller', 'seller.id', '=', 'orders.seller_id')
            ->leftJoin('purchase_items', 'purchase_items.id', '=', 'order_items.item_id')
            ->leftJoin('materials', 'materials.id', '=', 'purchase_items.material_id')
            ->with('invoice')
            ->whereRaw('FIND_IN_SET("' . $purchase->id . '", roll_id)')
            ->select(
                'order_items.*',
                'orders.*',
                'customer.firstname as customer_firstname',
                'customer.lastname as customer_lastname',
                'seller.firstname as seller_firstname',
                'seller.lastname as seller_lastname',
                'purchase_items.*',
                'materials.name as material_name'
            )
            ->get();

        $wareHouse = WareHouse::pluck('name', 'id')->all();        

        // Return view with the necessary data
        return view('purchase.edit', compact(
            'purchase',
            'categories',
            'materials',
            'materials2',
            'suppliers',
            'items',
            'orders',
            'invoiceNumbers',
            'articleNumbers',
            'colorMaterial',
            'articlesWithColors',
            'colors',
            'articles',
            'articleColors',
            'wareHouse'
        ));
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
            'attach_documents.*'   => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048',
            'no_of_rolls'          => 'required',
            'no_of_bales'          => 'required',
            'warehouse_id'         => 'required',
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
            "no_of_rolls"                => $request->input("no_of_rolls"),
            "no_of_bales"                => $request->input("no_of_bales"),
            "warehouse_id"                => $request->input("warehouse_id"),
        ];

        $purchase->fill($data);
        $purchase->save();

        if ($items) {
            $QRCode = Util::generateID();
            foreach ($items as $item) {
                $color = Material::where('id', '=', $item['color'])->first();
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
                    "available_qty" => $qty,
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

        // Soft delete existing articles and their colors
        DB::table('purchase_articles')
            ->where('purchase_id', $purchase->id)
            ->update(['deleted_at' => now(),'updated_at' => now()]);

        DB::table('purchase_article_colors')
            ->where('purchase_id', $purchase->id)
            ->update(['deleted_at' => now(),'updated_at' => now()]);

        // Loop through articles and update or insert data
        foreach ($request->input('articles', []) as  $articleData) {
            $material = Material::where('article_no',$articleData['article'])->first();
            // Insert the article
            $articleId = DB::table('purchase_articles')->insertGetId([
                'purchase_id' => $purchase->id,
                'material_id' => $material->id ?? $articleData['article'], // Use a default value or handle accordingly
                'article'     => $articleData['article'],
                'created_at'  => now(),
                'updated_at'  => now()
            ]);

            // Insert new colors for this article
            foreach ($articleData['colors'] as $colorIndex => $colorId) {
                // Get color details (including name, color_no, etc.)
                $colorDetails = $this->getColorDetails($colorId);
                DB::table('purchase_article_colors')->insert([
                    'purchase_id'         => $purchase->id,
                    'purchase_article_id' => $articleId ?? $colorDetails['id'],
                    'material_id'         => $colorId,
                    'color'               => $colorDetails['name'], // Get the color name
                    'color_no'            => $colorDetails['color_no'], // Get the color number
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
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
        // dd(request()->all());
        $purchaseItemId = $request->input('purchaseItemId');
        $purchaseItem = PurchaseItem::findOrFail($purchaseItemId);

        $qty =  $request->input('qty');

        $purchase = $purchaseItem->purchase;
        $purchase->total_qty = ($purchase->total_qty - $purchaseItem->qty) + $qty;
        $purchase->save();
        $material = Material::find($request->input('mat_id'));
        $data = array(
            'material_id' => $material->id,
            // "color"       => $material->color_id,
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
        return InvoiceItemRoll::where('roll_id', '=', $request->input('item_id'))->with('invoice.customer', 'invoice_item')->get();
    }

    public function multipleDelete(Request $request)
    {

        $selectedItems = $request->selectedItems;

        $purchases = Purchase::whereIn('id', $selectedItems)->delete();

        if ($purchases) {

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

    public function getArticles()
    {
        // Fetch articles with their IDs or names as needed
        $articles = DB::table('materials')
            ->whereNull('deleted_at')
            ->select('article_no', 'article_no') // Assuming 'id' is the article_id
            ->distinct()
            ->pluck('article_no', 'article_no') // Pluck with ID as key and name as value
            ->toArray();

        return response()->json($articles);
    }

    public function getColors(Request $request)
    {
        $article = $request->input('article');

        // Fetch colors based on the selected article
        $colors = DB::table('materials')
            ->whereNull('deleted_at')
            ->where('article_no', $article)
            ->pluck('color', 'id') // Assuming 'id' is the color_id
            ->toArray();

        // Map color names to color_id and names
        $colorData = [];
        foreach ($colors as $id => $name) {
            $colorData[] = ['color_id' => $id, 'name' => $name];
        }

        return response()->json($colorData);
    }
}