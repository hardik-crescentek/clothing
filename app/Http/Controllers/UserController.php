<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;
use Carbon\Carbon;
use App\WareHouse;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selected_role = $request->role;
        $search = $request->search;
        $users = new User;
        if ($search) {
            $users = $users->where(function ($query) use ($search) {
                return $query->orWhere('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        $users = $users->where('id', '!=', 1)->orderBy('id','DESC');
        if($selected_role) {
            $users = $users->role($selected_role);
        }
        $users = $users->paginate(env('ITEMS_PER_PAGE'))->appends($request->query());
        $roles = array('' =>'All');
        $roles += Role::pluck('name', 'name')->all();

        $users->appends(['role' => $selected_role]);
        return view('users.index', compact('users','roles','selected_role'));
        // return view('users.index', compact('users','roles','selected_role','settings'));
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
        $roles = Role::orderBy('name', 'ASC')->pluck('name', 'name')->all();
        $business_nature = array_merge(['' => "Select Nature or Business"],config('constants.business_nature'));
        $wareHouse = WareHouse::pluck('name', 'id')->all();
        return view('users.create', compact('roles','redirect','business_nature','wareHouse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name, email and password fields
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
            'role'      => 'required',
            'zip'       => 'required',
        ]);
        $input = $request->only('firstname', 'lastname', 'email', 'phone', 'phone2', 'address', 'city', 'state', 'country', 'dob', 'joining_date', 'zip','salesman_commission','skype','facebook','pinterest','wechat','whatsapp','line','company_name','business_nature', 'business_nature_other', 'newsletter','warehouse_id');
        // $input['dob'] = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
        $input['password'] = Hash::make($request->password);
        $user = User::create($input); //Retrieving only the email and password data

        $user->assignRole($request->input('role'));
        if ($request->input('redirectTo')) {
            return redirect(base64_decode($request->input('redirectTo')))->with('success', 'User created successfully');
        }
        else{
            return redirect()->route('users.index')->with('success', 'User created successfully');
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
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $business_nature = array_merge(['' => "Select Nature or Business"],config('constants.business_nature'));
        $wareHouse = WareHouse::pluck('name', 'id')->all();        
        return view('users.edit', compact('user', 'roles', 'userRole','business_nature','wareHouse'));
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
        $this->validate($request, [
            'firstname'=> 'bail|required|min:2',
            'lastname' => 'bail|required|min:2',
            'email'    => 'required|email|unique:users,email,' . $id,
            'phone'    => 'required|digits:10',
            'dob'      => 'required',
            'address'  => 'required',
            'city'     => 'required',
            'state'    => 'required',
            'zip'      => 'required',
            'role'     => 'required'
        ]);

        // Get the user
        $user = User::findOrFail($id);
        $input = $request->only(['firstname','lastname', 'email', 'phone', 'address', 'city', 'state', 'dob', 'joining_date', 'zip','salesman_commission','skype','facebook','pinterest','wechat','whatsapp','line','company_name','business_nature', 'business_nature_other', 'newsletter','warehouse_id']);
        // $input['dob'] = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
        $user->fill($input);

        // check for password change
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $role = $request->input('role') ? $request->input('role') : [];
        $user->syncRoles($role);


        return redirect()->route('users.index')->with('success', 'User updated successfully');
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

        return redirect()->route('users.index')->with('success','User successfully deleted.');
    }
}
