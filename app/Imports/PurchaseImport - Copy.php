<?php

namespace App\Imports;

use App\Purchase;
use App\PurchaseItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Utils\Util;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PurchaseImport implements ToCollection
{
    private $data_array = [];

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $rows)
    // {
    //     foreach ($rows as $row)
    //     {
    //     }
    //     echo "<pre>"; print_r($rows); die();
    //     return new Purchase([
    //         'invoice_no' => isset($row[0]) ? $row[0] : '',
    //         // 'purchase_date' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s'),
    //         // 'purchase_date' => '2022-06-22 00:00:00',
    //         'user_id' => 1,
    //         'total_qty' => isset($row[3]) ? $row[3] : '',
    //         'price' => isset($row[9]) ? $row[9] : '',
    //         'thb_ex_rate' => 0,
    //         'price_thb' => 0,
    //         'currency_of_purchase' => 'THB',
    //         'total_meter' => isset($row[7]) ? $row[7] : '',
    //         'gross_tax' => 0
    //     ]);

    //     // echo "<pre>"; print_r($purchase); die();
    //     // $purchase_id = $purchase;

    //     // $QRCode = Util::generateID();
    //     // $new_code = Util::gen_new_barcode_id(isset($row[5]) ? $row[5] : 'INR123');
    //     // return new PurchaseItem([
    //     //     'purchase_id' => $purchase_id,
    //     //     'material_id' => isset($row[6]) ? $row[6] : '',
    //     //     'roll_no' => isset($row[2]) ? $row[2] : '',
    //     //     'color' => isset($row[6]) ? $row[6] : '',
    //     //     'color_no' => isset($row[6]) ? $row[6] : '',
    //     //     'article_no' => isset($row[5]) ? $row[5] : '',
    //     //     'batch_no' => 0,
    //     //     'barcode' => $new_code,
    //     //     'qrcode' => $QRCode,
    //     //     'width' => 0,
    //     //     'qty' => isset($row[3]) ? $row[3] : '',
    //     //     'available_qty' => 0
    //     // ]);

    // }

    public function collection(Collection $rows,$supplier_id)    
    {      
        echo $supplier_id; die();
        // echo "<pre>"; print_r($supplier_id); die();  
        foreach ($rows as $key => $row)
        {
            if ($key != 0) 
            {   

                // echo "123"; die();
                $purchase = new Purchase;
                $purchase->invoice_no = isset($row[0]) ? $row[0] : '';
                $purchase->purchase_date = \Carbon\Carbon::now()->format("d/m/Y");
                $purchase->user_id = 1;
                $purchase->supplier_id = $supplier_id;
                $purchase->total_qty = isset($row[3]) ? $row[3] : '';
                $purchase->price = isset($row[9]) ? $row[9] : '';
                $purchase->thb_ex_rate = 0;
                $purchase->price_thb = 0;
                $purchase->currency_of_purchase = 'THB';
                $purchase->total_meter = isset($row[7]) ? $row[7] : '';
                $purchase->gross_tax = 0;
                $purchase->save();

                $this->data_array[] = $purchase->id;

                $QRCode = Util::generateID();
                $new_code = Util::gen_new_barcode_id(isset($row[5]) ? $row[5] : 'INR123');
                $purchaseItem = new PurchaseItem;
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->material_id = 0;
                $purchaseItem->roll_no = isset($row[2]) ? $row[2] : '';
                $purchaseItem->color = isset($row[6]) ? $row[6] : '';
                $purchaseItem->color_no = isset($row[6]) ? $row[6] : '';
                $purchaseItem->article_no = isset($row[5]) ? $row[5] : '';
                $purchaseItem->batch_no = 0;
                $purchaseItem->barcode = $new_code;
                $purchaseItem->qrcode = $QRCode;
                $purchaseItem->width = 0;
                $purchaseItem->qty = isset($row[3]) ? $row[3] : '';
                $purchaseItem->available_qty = 0;
                $purchaseItem->save();
            }
        // return '123';
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
