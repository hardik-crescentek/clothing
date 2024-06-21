<?php

namespace App\Imports;

use App\Purchase;
use App\PurchaseItem;
use App\Utils\Util;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StockIncoming implements ToCollection
{
    private $data_array = [];
    private $supplier_id; 

    public function __construct($supplier_id = "")
    {
        $this->supplier_id = $supplier_id; 

    }

    public function collection(Collection $rows)    
    {
        $totalColumns = count($rows->first());
        $qtyTotal = $totalColumns - 5;

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

                $date = intval($row[0]);
                $timestamp = Date::excelToTimestamp($date);
                $formattedDate = date('d-m-Y', $timestamp);

                if ($key != 0) {

                    $purchase = new Purchase;
                    $purchase->invoice_no = isset($row[1]) ? $row[1] : '';
                    $purchase->purchase_date = date("d/m/Y", $timestamp);
                    $purchase->user_id = 1;
                    $purchase->supplier_id = $this->supplier_id;
                    $purchase->pcs_no = $pc_no;
                    $purchase->price = isset($row[3]) ? $row[3] : '';
                    // $purchase->thb_ex_rate = isset($row[7]) ? $row[7] : 0;
                    // $purchase->price_thb = isset($row[6]) ? $row[6] : 0;
                    // $purchase->yards = $yyds;
                    // $purchase->currency_of_purchase = 'THB';
                    // $purchase->total_meter = isset($row[3]) ? $row[3] : '';
                    // $purchase->gross_tax = 0;
                    // $purchase->po = '';
                    // $purchase->bale = isset($row[2]) ? $row[2] : '';
                    $purchase->save();

                    $this->data_array[] = $purchase->id;

                    $totalQty = 0;
                    for($i = 5; $i <= $totalColumns; $i++) {

                        if(isset($row[$i]) && $row[$i] > 0) {

                            $QRCode = Util::generateID();
                            $invNumbers = preg_replace('/[^0-9]/', '', $row[1]);
                            $year = strrev(date('Y', $timestamp));
                            $month = strrev(date('m', $timestamp));
                            $day = strrev(date('d', $timestamp));
                            $qrData = $year.''.$month.''.$day.'-'.$invNumbers.'-'.($i-4).'-'.$qtyTotal;
                            $new_code = Util::gen_new_barcode_id($qrData);

                            $purchaseItem = new PurchaseItem;
                            $purchaseItem->purchase_id = $purchase->id;
                            $purchaseItem->color = '';
                            $purchaseItem->color_no = isset($row[4]) ? $row[4] : '';
                            $purchaseItem->barcode = $new_code;
                            $purchaseItem->qrcode = $QRCode;
                            $purchaseItem->qty = 1;
                            $purchaseItem->roll_no = ($i-4);
                            $purchaseItem->qty = isset($row[$i]) ? $row[$i] : '';
                            $purchaseItem->available_qty = isset($row[$i]) ? $row[$i] : '';
                            $purchaseItem->save();

                            $totalQty = $totalQty + $row[$i];
                        }
                    }

                    $puchaseUpate = Purchase::where('id', $purchase->id)->update([
                        'total_qty' => $totalQty,
                        'yards' => number_format($totalQty / 0.9144,2)
                    ]);
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
