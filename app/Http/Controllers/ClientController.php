<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Hash;
use App\CustomerItemPrice;
use App\Material;
use App\ClientArticle;
use App\ClientImage;
use DB;
use Illuminate\Support\Str;
use File;
use App\Utils\Util;
use Illuminate\Support\Facades\Storage;

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

        $clients = $clients->role('client')->orderBy('id','DESC')->with('images');
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
                                ->select('id', 'article_no', 'color', 'color_no', 'roll', 'roll_per_mtr', 'cut_wholesale', 'cut_wholesale_per_mtr', 'retail', 'retail_per_mtr', DB::raw('MAX(id) as max_id'))
                                ->groupBy('article_no')
                                ->orderBy('max_id', 'desc');
        
          // Query to fetch the latest records for each article_no
        $articles = DB::table('materials')
                    ->joinSub($latestRecordsSubquery, 'latest_records', function ($join) {
                        $join->on('materials.id', '=', 'latest_records.id');
                    })
                    ->select('materials.article_no', 'materials.color', 'materials.color_no', 'latest_records.roll' , 'latest_records.roll_per_mtr', 'latest_records.cut_wholesale','latest_records.cut_wholesale_per_mtr', 'latest_records.retail','latest_records.retail_per_mtr')
                    ->get();
        return view('clients.create', compact('redirect','business_nature','articles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate name, email and password fields
        $this->validate($request, [
            'firstname' => 'required|max:120',
            'lastname'  => 'required|max:120',
            'client_mark' => 'nullable|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
            'phone'     => 'required',
            'dob'       => 'required',
            'address'   => 'required',
            'city'      => 'required',
            'state'     => 'required',
            'zip'       => 'required',
            // 'images.*'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // each file must be an image and up to 2MB
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation rules for images
            'captured_image' => 'nullable|string', // Optional base64 image
        ]);

        $input = $request->only(
            'firstname', 'lastname','client_mark', 'email', 'phone', 'address', 
            'city', 'state', 'country', 'dob', 'zip', 'company_name', 
            'business_nature', 'business_nature_other', 'newsletter', 'skype','facebook','pinterest','wechat','whatsapp','line'
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
                'roll_per_mtr'  => $request->roll_per_mtr[$index],
                'cut_wholesale' => $request->cut_wholesale[$index],
                'cut_wholesale_per_mtr' => $request->cut_wholesale_per_mtr[$index],
                'retail'        => $request->retail[$index],
                'retail_per_mtr'        => $request->retail_per_mtr[$index],
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }
        ClientArticle::insert($articlesData);

        $imagePath = public_path('uploads/') . config('constants.client_img_path');

        if(isset($request->image_binary) && !empty($request->image_binary)){
            // Get the old image if it exists
            $imageName = 'captured_' .Str::random() . ".jpg";
            $baseFromJavascript = $request->image_binary;
            $base_to_php = explode(',', $baseFromJavascript);
            $originalImage = base64_decode($base_to_php[1]);
            File::put(public_path('uploads/').config('constants.client_img_path') .'/'. $imageName, $originalImage);
            $data['image'] = config('constants.client_img_path') .'/'. $imageName;
            Util::genrateThumb($data['image']);
            ClientImage::create([
                'client_id' => $user->id,
                'name' => $imageName
            ]);
        }

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $image->getClientOriginalName(); // Get the image name
                $image->storeAs('clients', $imageName); // Store the image
                $user->images()->create(['name' => $imageName]); // Save only image name
            }
        }

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
    public function edit(Request $request, $id)
    {
        // Fetch all materials and group them by article_no
        $materials = DB::table('materials')
            ->select('id', 'article_no', 'color', 'color_no', 'roll','roll_per_mtr', 'cut_wholesale','cut_wholesale_per_mtr', 'retail','retail_per_mtr')
            ->get()
            ->groupBy('article_no')
            ->map(function ($group) {
                // Return the first record from each group
                return $group->first();
            });

        // Fetch the user with related pricelist and clientArticles
        $userQuery = User::with('pricelist.material', 'clientArticles');
        
        if ($request->ajax()) {
            $article_no = $request->get('Article', '');

            // Conditionally filter by article_no if provided
            if (!empty($article_no)) {
                $userQuery->with(['pricelist' => function ($q) use ($article_no) {
                    $q->whereHas('material', function ($query) use ($article_no) {
                        $query->where('article_no', $article_no);
                    });
                }]);
            }

            $user = $userQuery->find($id);
            return response()->json($user);
        }

        // Fetch the user and their related client articles
        $user = $userQuery->find($id);
        $clientArticles = ClientArticle::where('client_id', $user->id)->get()->keyBy('article_no');

        // Combine material data with client articles data
        $matchedData = $materials->map(function ($material) use ($clientArticles) {
            // Get the client article for the current material
            $clientArticle = $clientArticles->get($material->article_no);

            // Update material fields if client article exists
            if ($clientArticle) {
                $material->roll = $clientArticle->roll ?? $material->roll;
                $material->roll_per_mtr = $clientArticle->roll_per_mtr ?? $material->roll_per_mtr;
                $material->cut_wholesale = $clientArticle->cut_wholesale ?? $material->cut_wholesale;
                $material->cut_wholesale_per_mtr = $clientArticle->cut_wholesale_per_mtr ?? $material->cut_wholesale_per_mtr;
                $material->retail = $clientArticle->retail ?? $material->retail;
                $material->retail_per_mtr = $clientArticle->retail_per_mtr ?? $material->retail_per_mtr;
            }

            return $material;
        });

        // Attach matched data to user
        $user->clientArticles = $matchedData;

        // Fetch business nature options
        $business_nature = array_merge(['' => "Select Nature or Business"], config('constants.business_nature'));

        // Render the view with the required data
        return view('clients.edit', compact('user', 'business_nature'));
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
                'client_mark' => 'nullable|unique:users,client_mark'. $id,
                'email'     => 'required|email|unique:users,email,' . $id,
                'phone'     => 'required',
                'dob'       => 'required',
                'address'   => 'required',
                'city'      => 'required',
                'state'     => 'required',
                'zip'       => 'required',
                // 'images'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation rules for images
                'captured_image' => 'nullable|string', // Optional base64 image
            ]);

            // Get the user
            $user = User::findOrFail($id);
            $input = $request->only(['firstname','lastname','client_mark', 'email', 'phone', 'address', 'city', 'state', 'dob', 'joining_date', 'zip','salesman_commission','skype','facebook','pinterest','wechat','whatsapp','line','company_name','business_nature', 'business_nature_other', 'newsletter']); //Retreive the name, email and password fields
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
                        [
                            'client_id' => $user->id, // Add client_id to make the condition unique
                            'article_no' => $article_no,
                        ],
                        [
                            'roll' => $request->roll[$key],
                            'roll_per_mtr' => $request->roll_per_mtr[$key],
                            'cut_wholesale' => $request->cut_wholesale[$key],
                            'cut_wholesale_per_mtr' => $request->cut_wholesale_per_mtr[$key],
                            'retail' => $request->retail[$key],
                            'retail_per_mtr' => $request->retail_per_mtr[$key],
                        ]
                    );
                }
            }
                       
            if(isset($request->image_binary) && !empty($request->image_binary)){
                // Get the old image if it exists
                $imageName = 'captured_' .Str::random() . ".jpg";
                $baseFromJavascript = $request->image_binary;
                $base_to_php = explode(',', $baseFromJavascript);
                $originalImage = base64_decode($base_to_php[1]);
                File::put(public_path('uploads/').config('constants.client_img_path') .'/'. $imageName, $originalImage);
                $data['image'] = config('constants.client_img_path') .'/'. $imageName;
                Util::genrateThumb($data['image']);
                ClientImage::create([
                    'client_id' => $user->id,
                    'name' => $imageName
                ]);
            }

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = $image->getClientOriginalName(); // Get the image name
                    $image->storeAs('clients', $imageName); // Store the image
                    $user->images()->create(['name' => $imageName]); // Save only image name
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
