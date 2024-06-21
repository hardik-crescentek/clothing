<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;


class PrintBarcodeController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $invoice_no = $request->input('invoice_no');
        $qty = $request->input('qty',1);
        $barcode = $request->input('printBarcode',false);
        $qrcode = $request->input('printQRCode',false);
        $printWithBatchNo = $request->input('printWithBatchNo',false);
        $printWithArticleNo = $request->input('printWithArticleNo',false);
        $printWithRollNo = $request->input('printWithRollNo',false);
        $printInvoice = $request->input('printInvoice',false);
        $printColor = $request->input('printColor',false);
        $printWidth = $request->input('printWidth',false);
        

        $purchase = Purchase::where('id', $id)->first(); // ->purchase_items()->get();
        $data = [
                    'invoice_no'         => $invoice_no, 
                    'qty'                => $qty, 
                    'purchase'           => $purchase, 
                    'printBarcode'       => $barcode,
                    'printQRCode'        => $qrcode,
                    'printWithBatchNo'   => $printWithBatchNo,
                    'printWithArticleNo' => $printWithArticleNo,
                    'printWithRollNo'    => $printWithRollNo,
                    'printInvoice'       => $printInvoice,
                    'printColor'         => $printColor,
                    'printWidth'         => $printWidth,
                ];
        return view('purchase.print-barcode', $data);
    }
}
