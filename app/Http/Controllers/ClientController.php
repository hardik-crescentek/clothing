<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Hash;
use App\CustomerItemPrice;
use App\Material;
use App\ClientArticle;
use DB;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $clients = new User;
        if ($search) {
            $clients = $clients->where(function ($query) use ($search) {
                return $query->orWhere('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $clients = $clients->role('client')->orderBy('id','DESC');
        $clients = $clients->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //Get all roles and pass it to the view
        $redirect = $request->input('redirect');
        $business_nature = array_merge(['' => "Select Nature or Business"],config('constants.business_nature'));

        $latestRecordsSubquery = DB::table('materials')
                                ->select('id', 'article_no', 'color', 'color_no', 'roll', 'cut_wholesale', 'retail', DB::raw('MAX(id) as max_id'))
                                ->groupBy('article_no')
                                ->orderBy('max_id', 'desc');
        
          // Query to fetch the latest records for each article_no
        $articles = DB::table('materials')
                    ->joinSub($latestRecordsSubquery, 'latest_records', function ($join) {
                        $join->on('materials.id', '=', 'latest_records.id');
                    })
                    ->select('materials.article_no', 'materials.color', 'materials.color_no', 'latest_records.roll', 'latest_records.cut_wholesale', 'latest_records.retail')
                    ->get();
        return view('clients.create', compact('redirect','business_nature','articles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //Validate name, email and password fields
    //     $this->validate($request, [
    //         'firstname' => 'required|max:120',
    //         'lastname'  => 'required|max:120',
    //         'email'     => 'required|email|unique:users',
    //         'password'  => 'required|min:6|confirmed',
    //         'phone'     => 'required|digits:10',
    //         'dob'       => 'required',
    //         'address'   => 'required',
    //         'city'      => 'required',
    //         'state'     => 'required',
    //         'zip'       => 'required',
    //     ]);
    //     $input = $request->only('firstname', 'lastname', 'email', 'phone', 'phone2', 'address', 'city', 'state', 'country', 'dob', 'zip','company_name','business_nature', 'business_nature_other', 'newsletter');
    //     // $input['dob'] = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
    //     $input['password'] = Hash::make($request->password);
    //     $user = User::create($input); //Retrieving only the email and password data

    //     $user->assignRole('client');
    //     if ($request->input('redirectTo')) {
    //         return redirect(base64_decode($request->input('redirectTo')))->with('success', 'Client created successfully');
    //     }
    //     else{
    //         return redirect()->route('clients.index')->with('success', 'Client created successfully');
    //     }

    // }
    public function store(Request $request)
    {
        // Validate name, email and password fields
        $this->validate($request, [
            'firstname' => 'required|max:120',
            'lastname'  => 'required|max:120',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
            'phone'     => 'required|digits:10',
            'dob'       => 'required',
            'address'   => 'required',
            'city'      => 'required',
            'state'     => 'required',
            'zip'       => 'required',
        ]);

        $input = $request->only(
            'firstname', 'lastname', 'email', 'phone', 'address', 
            'city', 'state', 'country', 'dob', 'zip', 'company_name', 
            'business_nature', 'business_nature_other', 'newsletter'
        );

        $input['password'] = Hash::make($request->password);
        $user = User::create($input);
        $user->assignRole('client');

        // Save client articles
        $articlesData = [];
        foreach ($request->article_no as $index => $articleNo) {
            $articlesData[] = [
                'client_id'     => $user->id,
                'article_no'    => $articleNo,
                'roll'          => $request->roll[$index],
                'cut_wholesale' => $request->cut_wholesale[$index],
                'retail'        => $request->retail[$index],
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }
        ClientArticle::insert($articlesData);

        if ($request->input('redirectTo')) {
            return redirect(base64_decode($request->input('redirectTo')))->with('success', 'Client created successfully');
        } else {
            return redirect()->route('clients.index')->with('success', 'Client created successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('clients.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $user = User::with('pricelist.material','clientArticles');
        if($request->ajax()){
            $article_no = $request->Article;
            if($article_no == ''){
                $user = $user->with('pricelist.material');
            }else{
                $user = $user->with(['pricelist'=> function($q) use ($article_no) {
                            $q->whereHas('material',function($query) use($article_no){
                                $query->where('article_no',$article_no);
                            });
                        }]);

            }
            $user = $user->find($id);
            return response()->json($user);
        }

        $user = $user->find($id);
        $business_nature = array_merge(['' => "Select Nature or Business"],config('constants.business_nature'));
        return view('clients.edit', compact('user','business_nature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $materialId = $request->materialid;
            $prices = [
                'price'                 => $request->retailprice,
                'retail_credit_days'    => $request->r_credit_day,
                'wholesale_price'       => $request->wholeprice,
                'wholesale_credit_days' => $request->w_credit_day,
                'sample_price'          => $request->sampleprice,
                'sample_credit_days'    => $request->s_credit_day,
                'remark_note'           => $request->note
            ];
            $UpdatePrice = CustomerItemPrice::where('customer_id',$id)
                            ->where('material_id',$materialId)
                            ->update($prices);
            $data= array('msg' => "Item price update successfully.");
            if ($UpdatePrice) {
                return response()->json($data, 200);
            }
        }else{
            $this->validate($request, [
                'firstname' => 'bail|required|min:2',
                'lastname'  => 'bail|required|min:2',
                'email'     => 'required|email|unique:users,email,' . $id,
                'phone'     => 'required|digits:10',
                'dob'       => 'required',
                'address'   => 'required',
                'city'      => 'required',
                'state'     => 'required',
                'zip'       => 'required'
            ]);

            // Get the user
            $user = User::findOrFail($id);
            $input = $request->only(['firstname','lastname', 'email', 'phone', 'address', 'city', 'state', 'dob', 'joining_date', 'zip','salesman_commission','skype','facebook','pinterest','wechat','whatsapp','line','company_name','business_nature', 'business_nature_other', 'newsletter']); //Retreive the name, email and password fields
            // $input['dob'] = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
            $user->fill($input);

            // check for password change
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Update client articles
            if ($request->has('article_no')) {
                // Update or create client articles
                foreach ($request->article_no as $key => $article_no) {
                    $clientArticle = ClientArticle::updateOrCreate(
                        ['article_no' => $article_no],
                        [
                            'roll' => $request->roll[$key],
                            'cut_wholesale' => $request->cut_wholesale[$key],
                            'retail' => $request->retail[$key],
                            // Add more fields as necessary
                        ]
                    );
                }
            } 

            return redirect()->route('clients.index')->with('success', 'User updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Find a user with a given id and delete
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('clients.index')->with('success','User successfully deleted.');
    }

    public function deletePriceListData(Request $request,$id)
    {
        $materialId = $request->materialid;
        $DeletePriceList = CustomerItemPrice::where('customer_id',$id)
                            ->where('material_id',$materialId)
                            ->delete();
        $data= array('msg' => "Item price deleted successfully.");
        if ($DeletePriceList) {
            return response()->json($data, 200);
        }
    }

    public function showArticles($article_no)
    {
        $articles = Material::where('article_no', $article_no)->get();
        return view('clients.article', compact('article_no', 'articles'));
    }

    public function saveClientArticles(Request $request, $client_id)
    {
        $data = $request->all();
        foreach ($data['article_no'] as $key => $article_no) {
            ClientArticle::updateOrCreate(
                ['client_id' => $client_id, 'article_no' => $article_no, 'color_no' => $data['color_no'][$key]],
                ['role' => $data['role'][$key], 'cut_wholesale' => $data['cut_wholesale'][$key], 'retail' => $data['retail'][$key]]
            );
        }
        return redirect()->route('clients.show', $client_id)->with('success', 'Articles updated successfully');
    }
}
