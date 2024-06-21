<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseItem;
use App\Material;
use App\Purchase;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $invoice_no = $request->input('invoice_no', '');

        $article_no = ['' => "Select Article No"];
        $article_no += Material::active()
                        ->pluck('article_no', 'article_no')
                        ->all();

        $colors = ['' => "All Color"];
        $colors += Material::active()
                    ->get()
                    ->pluck('color_code','color_no')
                    ->all();

        $items = PurchaseItem::with([
                    'material',
                    'color'
                ])->orderBy('id','DESC');

        //search by material
        $material_id = $request->material_id;
        $query_param = [];
        if ($material_id) {
            $items = $items->where('material_id', $material_id);
            $query_param['material_id'] = $material_id;
        }

        //search by invoice_no
        if ($invoice_no) {
            $items->whereHas(
                'purchase',
                function ($q) use ($invoice_no) {
                        $q->Where('invoice_no', 'LIKE', "%{$invoice_no}%");
                }
            );
        } else {
            $items->has('purchase');
        }

        //search by batch number and barcode
        $search = $request->search;
        if ($search) {
            $items = $items->where(function ($query) use ($search) {
                return $query->orWhere('batch_no', 'LIKE', "%{$search}%")
                            ->orWhere('barcode', 'LIKE', "%{$search}%")
                            ->orWhere('qrcode', 'LIKE', "%{$search}%");
            });
            $query_param['search'] = $search;
        }

        //search by article_no
        $article=$request->search_article;
        if($article!=''){
            $items = $items->where('article_no', $article);
            $colors = ['' => "All Color"];
            $colors += Material::active()
                        ->where('article_no', $article)
                        ->get()
                        ->pluck('color_code', 'color_no')
                        ->all();
            $query_param['article_no'] = $article;
        }
        //color search
        $color=$request->color;
        if($color!=''){
            $items = $items->where('color_no', $color);
            $query_param['color'] = $color;

        }

        // dd($items->toSql());
        $items = $items->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        if (!empty($query_param)) {
            $items->appends($query_param);
        }


        $materials = ['' => "All"];
        $materials += Material::active()->pluck('name', 'id')->all();
        // $colors = Color::active()->pluck('name', 'id')->all();
        // $suppliers = Supplier::dropdown();
        $data = ['materials' => $materials, 'items' => $items, 'material_id' => $material_id, 'search' => $search, 'invoice_no' => $invoice_no,'article_no' => $article_no,'colors' => $colors,'article' => $article,'color' => $color];
        return view('inventory.index', $data);
    }
}
