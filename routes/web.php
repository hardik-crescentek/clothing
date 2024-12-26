<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/api/doc', function () {
    return view('api');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard')->middleware('auth');

Route::group(['middleware' => ['auth']], function() {

    # For Users #
    Route::resource('users', UserController::class);

    #For Clients #
    Route::resource('clients', ClientController::class);
    Route::post('clients/update/{id}', [App\Http\Controllers\ClientController::class, 'update'])->name('client.update');
    Route::post('clients/pricebookdelte/{id}', [App\Http\Controllers\ClientController::class, 'deletePriceListData'])->name('client.delete');
    Route::get('clients/articles/{article_no}', 'ClientController@showArticles')->name('client.articles.show');
    // Route::post('clients/articles/save/{client_id}', 'ClientController@saveClientArticles')->name('client.articles.save');

    # For Category #
	Route::resource('category', CategoryController::class);

    # For Color #
    Route::resource('color', ColorController::class);

    # For Material #
    Route::get('materials/autocomplete', [App\Http\Controllers\MaterialController::class, 'autocomplete']);
    Route::get('materials/getItem/{id}', [App\Http\Controllers\MaterialController::class, 'getItem']);
    Route::post('materials/getPrice', [App\Http\Controllers\MaterialController::class, 'getSelectedPrice']);
    Route::resource('materials', MaterialController::class);

    # For Purchase #
    Route::resource('purchase', PurchaseController::class ,['only' => ['index', 'create', 'store','edit','update','destroy']]);
    Route::resource('purchase-item', PurchaseItemController::class ,['only' => ['index', 'create', 'store','edit','update','destroy']]);
    Route::delete('purchase_item/{purchaseItem}', [App\Http\Controllers\PurchaseController::class, 'deletePurchaseItem'])->name('purchase.deletePurchaseItem');
    Route::get('get-roll-history', [App\Http\Controllers\PurchaseController::class, 'rollHistory'])->name('purchase.roll-history');
    Route::patch('update-item/', [App\Http\Controllers\PurchaseController::class, 'updatePurchaseItem'])->name('purchase.update-item');

    Route::get('/get-articles', 'PurchaseController@getArticles')->name('get.articles');
    Route::get('/get-colors', 'PurchaseController@getColors')->name('get.colors');

    # For Purchase Item #
    Route::get('get_purchase_id', [App\Http\Controllers\PurchaseItemController::class, 'getPurchaseId'])->name('get.purchase.id');
    Route::get('purchase-items-by-invoice', [App\Http\Controllers\PurchaseItemController::class, 'getPurchaseItemsByInvoice'])->name('purchase.items.by.invoice');

    Route::get('purchase-by-invoice', [App\Http\Controllers\PurchaseItemController::class, 'getPurchaseByInvoice'])->name('purchase.by.invoice');

    Route::get('purchase/import', [App\Http\Controllers\PurchaseController::class, 'import'])->name('purchase.importt');
    Route::post('purchase-import-store', [App\Http\Controllers\PurchaseController::class, 'import_store'])->name('purchase.import');
    Route::get('purchase/supplier-details/{id}', [App\Http\Controllers\PurchaseController::class, 'supplier_details'])->name('purchase.supplier-details');
    Route::post('purchase/printall', [App\Http\Controllers\PurchaseController::class, 'print_all'])->name('purchase.printall');
    Route::post('purchase/multiple-delete', [App\Http\Controllers\PurchaseController::class, 'multipleDelete'])->name('purchase.multiple-delete');

    Route::get('fetch-material-price',[App\Http\Controllers\PurchaseItemController::class, 'fetchPrice'])->name('purchase.items.fetchPrice');

    Route::get('get-articles-by-invoice', 'PurchaseItemController@getArticlesByInvoice');

    # For Supplier #
    Route::resource('supplier', SupplierController::class);

    Route::get('get-suppliers',  [App\Http\Controllers\PurchaseController::class, 'getSuppliers'])->name('get.suppliers');

    # For Order #
    Route::resource('order', OrderController::class,['only' => ['index', 'create', 'store','edit','update','destroy']]);
    Route::get('pos', [App\Http\Controllers\OrderController::class, 'posCreate'])->name('pos');
    Route::delete('order_item/{orderItem}/customer/{customerItem}', [App\Http\Controllers\OrderController::class, 'deleteOrderItem'])->name('order.deleteOrderItem');
    Route::patch('update-order-item/', [App\Http\Controllers\OrderController::class, 'updateOrderItem'])->name('order.update-order-item');
    Route::get('order/getCustomerOrders', [App\Http\Controllers\OrderController::class, 'getCustomerOrders'])->name('get_customer_orders');
    Route::post('order/changeOrderStatus', [App\Http\Controllers\OrderController::class, 'changeOrderStatus'])->name('order.changeOrderStatus');
    Route::get('order/view/{id}', [App\Http\Controllers\OrderController::class, 'ViewOrderDetails'])->name('order.viewdetails');
    Route::post('order/updatenew', [App\Http\Controllers\OrderController::class, 'updatenew'])->name('order.updatenew');
    Route::get('printorderbarcode/{id}', [App\Http\Controllers\OrderController::class, 'print_order_barcode'])->name('printorderbarcode');

    #For Order Items #
    Route::get('orders/{id}/order-items', [App\Http\Controllers\OrderController::class, 'getOrderItems'])->name('getOrderItems');
    Route::get('order-items/{id}', [App\Http\Controllers\OrderController::class, 'getOrderItemById'])->name('getOrderItemById');
    Route::delete('delete-order-item/{id}', [App\Http\Controllers\OrderController::class, 'deleteAjaxOrderItem'])->name('deleteAjaxOrderItem');

    
    # For Booking #
    Route::get('bookings', [App\Http\Controllers\OrderController::class, 'getBookings'])->name('bookings');
    Route::get('audit', [App\Http\Controllers\AuditController::class, 'index'])->name('audit');

    # For Invoice #
    Route::get('invoice/create/{order}', [App\Http\Controllers\InvoiceController::class, 'create'])->name('invoice.create');
    Route::get('get-roll', [App\Http\Controllers\InvoiceController::class, 'getRollData'])->name('invoice.get-roll');
    Route::post('store', [App\Http\Controllers\InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('invoice/edit/{invoice}', [App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::patch('update/{invoice}', [App\Http\Controllers\InvoiceController::class, 'update'])->name('invoice.update');
    Route::get('invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice.index');
    Route::delete('invoice/{invoice}', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoice.destroy');
    Route::get('invoice/print_invoice/{invoice}', [App\Http\Controllers\InvoiceController::class, 'print'])->name('invoice.print');
    Route::get('invoice/add-payment/{invoice}', [App\Http\Controllers\InvoiceController::class, 'addPayment'])->name('invoice.add-payment');
    Route::get('invoice/add-payment/{invoice}', [App\Http\Controllers\InvoiceController::class, 'addPayment'])->name('invoice.add-payment');
    Route::post('invoice/save-payment', [App\Http\Controllers\InvoiceController::class, 'savePayment'])->name('invoice.save-payment');
    Route::get('invoice/create', [App\Http\Controllers\InvoiceController::class, 'create2'])->name('invoice.create2');
    Route::get('invoice/getMaterial', [App\Http\Controllers\InvoiceController::class, 'getMaterial'])->name('invoice.getMaterial');
    Route::get('invoice/getClientMaterial', [App\Http\Controllers\InvoiceController::class, 'getClientMaterial'])->name('invoice.getClientMaterial');
    Route::post('store-invoice', [App\Http\Controllers\InvoiceController::class, 'store2'])->name('invoice.store2');
    Route::get('invoice/last-invoice-info', [App\Http\Controllers\InvoiceController::class, 'getLastInvoiceInfo'])->name('invoice.last-invoice');
    Route::get('invoice/pricebook/{id}', [App\Http\Controllers\InvoiceController::class, 'customerPriceBook'])->name('invoice.pricebook');
    Route::post('invoice/getmaterialday', [App\Http\Controllers\InvoiceController::class, 'isCreditDaysExits'])->name('invoice.getdays');

    Route::get('invoice/getCustomerOrders', [App\Http\Controllers\InvoiceController::class, 'getCustomerInvoices'])->name('get_customer_invoices');


    # Check #
    Route::get('check-color', [App\Http\Controllers\ColorController::class, 'checkColor'])->name('color.check-color');
    Route::get('check-material', function () {
        return App\Material::with('category')->orderBy('id','DESC')->get();
    });
    Route::get('check-inventory',function(){
        return App\PurchaseItem::orderBy('id','DESC')->get();
    });

    # For Payment #
    Route::get('payments/pending-payments', [App\Http\Controllers\PaymentController::class, 'pendingPayments'])->name('payments.pending-payments');
    Route::get('payments/received-payments', [App\Http\Controllers\PaymentController::class, 'receivedPayments'])->name('payments.received-payments');

    # For Return #
    Route::get('return', [App\Http\Controllers\ReturnController::class, 'index'])->name('return');
    Route::post('return/store', [App\Http\Controllers\ReturnController::class, 'store'])->name('return.store');

    # For Inventory #
    Route::get('inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory');

    # For PrintBarcode #
    Route::get('printbarcode', [App\Http\Controllers\PrintBarcodeController::class, 'index'])->name('printbarcode');

    # For Generate Code #
    Route::get('genrate_code', [App\Http\Controllers\MaterialController::class, 'generateCode']);

    # For Profile#
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
	Route::put('profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/changepass', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.changePassword');

    # For Report #
    Route::get('report/stock-report', [App\Http\Controllers\ReportController::class, 'stockReport'])->name('report.stock');
    Route::get('report/sales-report', [App\Http\Controllers\ReportController::class, 'salesReport'])->name('report.sales');
    Route::get('report/purches-report', [App\Http\Controllers\ReportController::class, 'purchesReport'])->name('report.purches');
    Route::get('report/top-saled-material', [App\Http\Controllers\ReportController::class, 'topSalesMaterial']);
    Route::get('report/best-clients/{type}', [App\Http\Controllers\ReportController::class, 'bestClientByType']);
    Route::get('report/sended-material', [App\Http\Controllers\ReportController::class, 'materialSendedToClient'])->name('report.materialsended');

    # For Header Notifaction #
    Route::get('notifaction', [App\Http\Controllers\HomeController::class, 'headerNotifaction'])->name('header.notifaction');

    Route::post('notifaction', [App\Http\Controllers\SettingsController::class, 'update'])->name('setting.update');

    Route::get('storageLink', function () {
        try {
            // Execute the storage:link Artisan command
            Artisan::call('storage:link');
            return 'Storage link created successfully.';
        } catch (\Exception $e) {
            return 'Error creating storage link: ' . $e->getMessage();
        }
    });

    Route::get('clearRoutes', function () {
        try {
            // Execute the route:clear Artisan command
            Artisan::call('route:clear');
            return 'Route cache cleared successfully.';
        } catch (\Exception $e) {
            return 'Error clearing route cache: ' . $e->getMessage();
        }
    });

    Route::get('clearCache', function () {
        try {
            // Execute the cache:clear Artisan command
            Artisan::call('cache:clear');
            return 'Application cache cleared successfully.';
        } catch (\Exception $e) {
            return 'Error clearing application cache: ' . $e->getMessage();
        }
    });

    Route::get('optimize', function () {
        try {
            // Execute the optimize Artisan command
            Artisan::call('optimize');
            return 'Application optimized successfully.';
        } catch (\Exception $e) {
            return 'Error optimizing application: ' . $e->getMessage();
        }
    });

    # warehouse master #
    Route::resource('warehouse', WareHouseController::class);

    # stock location #
    Route::get('stockLocation', [App\Http\Controllers\WareHouseController::class, 'stockLocation'])->name('stockLocation.index');
    Route::get('stock-location/filter', 'WareHouseController@filterStockLocation')->name('stockLocation.filter');
    Route::put('stock-location/update/{id}', [App\Http\Controllers\WareHouseController::class, 'updateWarehouseLocation'])->name('stockLocation.update');
    Route::put('stock-locations/update-multiple','WareHouseController@updateMultipleWarehouseLocations')->name('stockLocation.updateMultiple');
    Route::get('warehouse-history/{id}','WareHouseController@getWarehouseHistory')->name('warehouse.history');

    Route::get('stock-article','InventoryController@stockArticle')->name('stockArticle');
});