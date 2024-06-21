<?php

namespace App\Imports;

use App\Purchase;
use App\PurchaseItem;
use App\Material;
use App\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Utils\Util;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PurchaseItemsimport implements ToCollection
{
    private $data_array = [];
    private $supplier_id; 

    public function __construct($supplier_id = "")
    {
        $this->supplier_id = $supplier_id; 

    }

    public function collection(Collection $rows)    
    {      
        // $test_name = "Hello WORLD To the magic world.   dvdfvf fdfdb";
        // echo strtolower(preg_replace('/( )+/', '-', $test_name)); die();
        // echo "<pre>"; print_r($rows); die();
        foreach ($rows as $key => $row)
        {   
            if (isset($row[0]) && !empty($row[0]) && $row[0] != '') 
            {
                $purchase_data = Purchase::orderBy('id', 'desc')->first();
                if (!empty($purchase_data))  {
                    $pc_no = $purchase_data->pcs_no + 1;

                } else {

                    $pc_no = 1;
                }

                if ($key != 0) {

                    if (isset($row[3])) {
                        $yyds = number_format($row[3] / 0.9144,2);
                    } else {
                        $yyds = "";
                    }

                    $purchase = new Purchase;
                    $purchase->invoice_no = isset($row[0]) ? $row[0] : '';
                    $purchase->purchase_date = date("d/m/Y", strtotime($row[4]));
                    $purchase->user_id = 1;
                    $purchase->supplier_id = $this->supplier_id;
                    $purchase->total_qty = isset($row[3]) ? $row[3] : '';
                    $purchase->price = isset($row[5]) ? $row[5] : '';
                    $purchase->thb_ex_rate = isset($row[7]) ? $row[7] : 0;
                    $purchase->price_thb = isset($row[6]) ? $row[6] : 0;
                    $purchase->yards = $yyds;
                    $purchase->currency_of_purchase = 'THB';
                    $purchase->total_meter = isset($row[3]) ? $row[3] : '';
                    $purchase->gross_tax = 0;
                    $purchase->po = '';
                    $purchase->pcs_no = $pc_no;
                    $purchase->bale = isset($row[2]) ? $row[2] : '';
                    $purchase->save();

                    $this->data_array[] = $purchase->id;

                    $QRCode = Util::generateID();
                    $new_code = Util::gen_new_barcode_id(isset($row[5]) ? $row[5] : 'ARTICLEC128');
                    $purchaseItem = new PurchaseItem;
                    $purchaseItem->purchase_id = $purchase->id;
                    $purchaseItem->color = '';
                    $purchaseItem->color_no = isset($row[1]) ? $row[1] : '';
                    $purchaseItem->barcode = $new_code;
                    $purchaseItem->qrcode = $QRCode;
                    $purchaseItem->qty = isset($row[2]) ? $row[2] : '';
                    $purchaseItem->available_qty = isset($row[3]) ? $row[3] : '';
                    $purchaseItem->total_qty = isset($row[3]) ? $row[3] : '';
                    $purchaseItem->save();
                }
            }
        }
    }

    public function getRowCount(): array
    {
        return $this->data_array;
    }

    public function startRow(): int
    {
        return 2;
    }
}
