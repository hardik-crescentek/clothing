<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
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
use App\Audit;
use App\Utils\Util;
use Validator;
use Auth;
use Excel;
use File;
use Carbon\Carbon;
use App\Imports\PurchaseImport;

class PurchaseController extends Controller
{
    public function CheckPurchaseDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'barcode'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $PurchaseItem = PurchaseItem::where('barcode',$request->barcode)->first();
        if (!empty($PurchaseItem)) {
            return response()->json([
                'status' => true,
                'message' => 'Purchase Item Details',
                'data' => $PurchaseItem
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Purchase Item Details not found',
                'data' => []
            ], 202);
        }
    }

    public function getmyaudit(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $auditItems = [];
        $audit_data = Audit::where("user_id","=",$request->user_id)
                        ->get();

        if (!empty($audit_data)) {
            foreach($audit_data as $row){
                $auditItems[] = array(
                    'id' => $row->id,
                    'barcode' => $row->barcode,
                    'total_qty' => $row->total_qty,
                    'available_qty' => $row->available_qty,
                    'remark' => isset($row->remark) ? $row->remark : '',
                    'created_at' => date('Y-m-d h:i A',strtotime($row->created_at)),
                );
            }
            return response()->json([
                'status' => true,
                'message' => 'Audit Details get successfully',
                'data' => $auditItems
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => []
            ], 202);
        }
    }

    public function Addauditdetails(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'barcode'   => 'required',
            'total_qty'   => 'required',
            'available_qty'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $PurchaseItem = PurchaseItem::where('barcode',$request->barcode)->first();
        if (!empty($PurchaseItem)) {
            $todaydate = date('Y-m-d');
            $CheckAudit = Audit::where("user_id","=",$request->user_id)
                        ->where("barcode","=",$request->barcode)
                        ->where('created_at','LIKE',"%{$todaydate}%")
                        ->first();
            // echo "<pre>"; print_r($CheckAudit); die();
            if ($CheckAudit == '') {
                $audit = new Audit();
                $audit->user_id = $request->user_id;
                $audit->barcode = $request->barcode;
                $audit->total_qty = $request->total_qty;
                $audit->available_qty = $request->available_qty;
                $audit->remark = isset($request->remark) ? $request->remark : '';
                $audit->save();
                if (!empty($audit->id)) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Audit add successfully',
                        'id' => $audit->id
                    ], 200);
                }
            }
            else{
                $audit = Audit::find($CheckAudit->id);
                $audit->user_id = $request->user_id;
                $audit->barcode = $request->barcode;
                $audit->total_qty = $request->total_qty;
                $audit->available_qty = $request->available_qty;
                $audit->remark = isset($request->remark) ? $request->remark : '';
                $audit->save();
                if (!empty($audit->id)) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Audit update successfully',
                        'id' => $audit->id
                    ], 200);
                }
            }
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'Invalid barcode',
                'data' => []
            ], 202);
        }
    }
}
