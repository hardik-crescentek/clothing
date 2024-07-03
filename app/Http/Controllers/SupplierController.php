<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $suppliers = new Supplier;
        $search = $request->search;
        if ($search) {
            $suppliers = $suppliers->where(function ($query) use ($search) {
                return $query->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('mobile_no', 'LIKE', "%{$search}%");
            });
        }
        $res_suppliers = $suppliers->orderBy('id','DESC')->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        return view('supplier.index', ['suppliers' => $res_suppliers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $redirect = $request->input('redirect');
        return view('supplier.create', compact('redirect'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->name = preg_replace('/\s+/', ' ', $request->name);
        $this->validate($request, [
            'name' => ['required'],
            'supplier_type' => ['required'],
            'currency_type' => ['required'],
            // 'contact_person' => ['required'],
            // 'email' => ['required', 'email'],
            // 'mobile_no' => ['required'],
            // 'office_no' => ['required'],
            // 'address' => ['required'],
            // 'city' => ['required'],
            // 'gst_no' => ['required'],
            // 'transport_name' => ['required'],
        ]);
        if ($request->mobile_no) {
            $this->validate($request, [
                'mobile_no'    => 'required|digits:10',
            ]);
        }
        if ( $request->alternate_no) {
            $this->validate($request, [
                'alternate_no' => 'required|digits:10',
            ]);
        }
        if ($request->office_no) {
            $this->validate($request, [
                'office_no'    => 'required|digits:10'
            ]);
        }
        $data = [
                    'name'           => $request->name,
                    'contact_person' => $request->contact_person ?? '',
                    'email'          => $request->email ?? '',
                    'mobile_no'      => $request->mobile_no ?? '',
                    'alternate_no'   => $request->alternate_no ?? '',
                    'office_no'      => $request->office_no ?? '',
                    'address'        => $request->address ?? '',
                    'city'           => $request->city ?? '',
                    'gst_no'         => $request->gst_no ?? '',
                    'transport_name' => $request->transport_name ?? '',
                    'supplier_type' => $request->supplier_type ?? '',
                    'currency_type' => $request->currency_type ?? '',
                    'info'           => $request->info,

                ];
        Supplier::create($data);
        if ($request->input('redirectTo')) {
            return redirect(base64_decode($request->input('redirectTo')))->with('success', 'Supplier created successfully');
        }
        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return redirect()->route('supplier.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', ['supplier' => $supplier]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->name = preg_replace('/\s+/', ' ', $request->name);
        $this->validate($request, [
            'name' => ['required'],
            // 'contact_person' => ['required'],
            // 'email' => ['required', 'email'],
            // 'mobile_no' => ['required'],
            //'office_no' => ['required'],
            // 'address' => ['required'],
            // 'city' => ['required'],
            // 'gst_no' => ['required'],
            // 'transport_name' => ['required'],
            'supplier_type' => ['required'],
            'currency_type' => ['required'],
        ]);
        if ($request->mobile_no) {
            $this->validate($request, [
                'mobile_no'    => 'required|digits:10',
            ]);
        }
        if ( $request->alternate_no) {
            $this->validate($request, [
                'alternate_no' => 'required|digits:10',
            ]);
        }
        if ($request->office_no) {
            $this->validate($request, [
                'office_no'    => 'required|digits:10'
            ]);
        }
        $data = [
                    'name'           => $request->name,
                    'contact_person' => $request->contact_person ?? '',
                    'email'          => $request->email ?? '',
                    'mobile_no'      => $request->mobile_no ?? '',
                    'alternate_no'   => $request->alternate_no ?? '',
                    'office_no'      => $request->office_no ?? '',
                    'address'        => $request->address ?? '',
                    'city'           => $request->city ?? '',
                    'gst_no'         => $request->gst_no ?? '',
                    'transport_name' => $request->transport_name ?? '',
                    'info'           => $request->info,
                    'supplier_type' => $request->supplier_type ?? '',
                    'currency_type' => $request->currency_type ?? '',
                ];
        $supplier->update($data);
        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Color successfully deleted.');
    }
}
