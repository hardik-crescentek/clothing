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

class PurchaseImport implements ToCollection
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
                if (!empty($purchase_data)) 
                {
                    $pc_no = $purchase_data->pcs_no + 1;
                }
                else
                {
                    $pc_no = 1;
                }
                if ($key != 0) 
                {   
                    if (isset($row[8])) 
                    {
                        $yyds = number_format($row[8] / 0.9144,2);
                    }
                    else
                    {
                        $yyds = "";
                    }

                    $cat_name =  isset($row[15]) ? $row[15] : '';
                    $category_id = 0;
                    if (isset($cat_name) && $cat_name != '') 
                    {
                        $cat_data = Category::where('name','LIKE',"%{$cat_name}%")->first();
                        if (isset($cat_data) && $cat_data != '') {
                            $category_id = $cat_data->id;
                        }
                        else
                        {
                            $category = new Category;
                            $category->name = $cat_name;
                            $category->slug = strtolower(preg_replace('/( )+/', '-', $cat_name));
                            $category->save();
                            $category_id = $category->id; 
                        }
                    }

                    $article_no = isset($row[5]) ? $row[5] : '';
                    $material_name = isset($row[14]) ? $row[14] : '';
                    $material_id = 0;
                    if (isset($article_no) && $article_no != '') 
                    {
                        $material_data = Material::where('article_no','LIKE',"%{$article_no}%")->where('name','LIKE',"%{$material_name}%")->first();
                        if (isset($material_data) && $material_data != '') {
                            $material_id = $material_data->id;
                        }
                        else
                        {
                            $new_code = Util::gen_new_barcode_id(isset($row[5]) ? $row[5] : 'ARTICLEC128');
                            $material = new Material;
                            $material->name = isset($row[14]) ? $row[14] : '';
                            $material->barcode = $new_code;
                            $material->category_id = $category_id;
                            $material->color = isset($row[6]) ? ucfirst($row[6]) : '';
                            $material->color_no = isset($row[7]) ? $row[7] : '';
                            $material->width = isset($row[12]) ? $row[12] : '';
                            $material->weight = isset($row[13]) ? $row[13] : '';
                            $material->article_no = isset($row[5]) ? $row[5] : '';
                            $material->batch_no = isset($row[11]) ? $row[11] : '';
                            $material->wholesale_price = isset($row[10]) ? $row[10] : '';
                            $material->retail_price = isset($row[10]) ? $row[10] : '';
                            $material->min_alert_qty = 10;
                            $material->save();
                            $material_id = $material->id;
                        }
                    }

                    $purchase = new Purchase;
                    $purchase->invoice_no = isset($row[0]) ? $row[0] : '';
                    $purchase->purchase_date = \Carbon\Carbon::now()->format("d/m/Y");
                    $purchase->user_id = 1;
                    $purchase->supplier_id = $this->supplier_id;
                    $purchase->total_qty = isset($row[8]) ? $row[8] : '';
                    $purchase->price = isset($row[10]) ? $row[10] : '';
                    $purchase->thb_ex_rate = 0;
                    $purchase->price_thb = 0;
                    $purchase->yards = $yyds;
                    $purchase->currency_of_purchase = 'THB';
                    $purchase->total_meter = isset($row[8]) ? $row[8] : '';
                    $purchase->gross_tax = 0;
                    $purchase->po = isset($row[1]) ? $row[1] : '';
                    $purchase->pcs_no = $pc_no;
                    $purchase->bale = isset($row[2]) ? $row[2] : '';
                    $purchase->totel_bales = isset($row[3]) ? $row[3] : '';
                    $purchase->totel_lot = isset($row[4]) ? $row[4] : '';
                    $purchase->save();

                    $this->data_array[] = $purchase->id;

                    $QRCode = Util::generateID();
                    $new_code = Util::gen_new_barcode_id(isset($row[5]) ? $row[5] : 'ARTICLEC128');
                    $purchaseItem = new PurchaseItem;
                    $purchaseItem->purchase_id = $purchase->id;
                    $purchaseItem->material_id = $material_id;
                    $purchaseItem->roll_no = isset($row[4]) ? $row[4] : '';
                    $purchaseItem->color = isset($row[6]) ? $row[6] : '';
                    $purchaseItem->color_no = isset($row[7]) ? $row[7] : '';
                    $purchaseItem->article_no = isset($row[5]) ? $row[5] : '';
                    $purchaseItem->batch_no = isset($row[11]) ? $row[11] : '';
                    $purchaseItem->barcode = $new_code;
                    $purchaseItem->qrcode = $QRCode;
                    $purchaseItem->width = isset($row[12]) ? $row[12] : '';
                    $purchaseItem->qty = isset($row[2]) ? $row[2] : '';
                    $purchaseItem->available_qty = isset($row[8]) ? $row[8] : '';
                    $purchaseItem->total_qty = isset($row[8]) ? $row[8] : '';
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
