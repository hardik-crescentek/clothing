<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    use SendsPasswordResetEmails;

    public function me(Request $request)
    {
        $user = $request->user();
        $user->role_name = $user->roles->pluck('name')->first();
        $response = [
            'success' => true,
            'data'    => $user,
            'message' => null,
        ];
        return response()->json($response, 200);
    }

    public function userById(Request $request)
    {
        $id = $request->get('id');
        if (empty($id)) {
            $response['data'] = null;
            $response['success'] = false;
            $response['message'] = 'Customer not found';
            return response()->json($response, 200);
        }
        $user = User::find($id);
        
        return response()->json([
            'status' => true,
            'message' => 'Customer Info',
            'data' => $user
        ], 200);
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $response = [
            'success' => false,
            'data'    => null,
            'message' => null,
        ];
        
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required',
            // 'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {

            $response['data'] = ['error' => $validator->errors()];
            $response['success'] = false;
            $response['message'] = implode(",", $validator->errors()->all());
            return response()->json($response, 200);
        }
        

        $input = $request->only(['firstname','lastname', 'email', 'phone','phone2', 'address', 'city', 'state','country','zip','company_name','business_nature', 'business_nature_other', 'newsletter']);
        $input['password'] = Hash::make($request->password);
        $input['api_user'] = true;
        $user = User::create($input);

        $client_role = Role::where('name', 'client')->pluck('id')->first();
        $user->assignRole($client_role);

        $data['token'] =  $user->createToken('MyApp')->accessToken;
        $data['firstname'] =  $user->firstname;
        $data['lastname'] =  $user->lastname;
        $data['phone'] =  $user->phone;
        $data['email'] =  $user->email;
        
        $response['data'] = $data;
        $response['success'] = true;
        $response['message'] = 'User register successfully.';

        return response()->json($response, 200);
    }

    public function update(Request $request)
    {
        $response = [
            'success' => false,
            'data'    => null,
            'message' => null,
        ];
        
        
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],            
        ]);
        if ($validator->fails()) {

            $response['data'] = ['error' => $validator->errors()];
            $response['success'] = false;
            $response['message'] = implode(",", $validator->errors()->all());
            return response()->json($response, 200);
        }
        
        $input = $request->only(['firstname','lastname', 'email', 'phone','phone2', 'address', 'city', 'state','country','zip','company_name','business_nature', 'business_nature_other', 'newsletter']);
        if($request->password) {
            $input['password'] = Hash::make($request->password);
        }
        $user = $request->user();
        $user->update($input);        
        $response['data'] = null;
        $response['success'] = true;
        $response['message'] = 'User Updated successfully.';

        return response()->json($response, 200);
            
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $response = [
            'success' => false,
            'data'    => null,
            'message' => null,
        ];

        // Validate the input
        $request->validate([
            'email'       => 'required|email',
            'password'    => 'required',
            'device_token' => 'nullable|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            if ($user->hasRole('dispatcher')) {
                $user->update(['device_token' => $request->device_token]);

                $user->role_name = $user->roles->pluck('name')->first();
                $data = $user;
                $data['token'] = $user->createToken('MyApp')->accessToken;

                $response['data'] = $data;
                $response['success'] = true;
                $response['message'] = 'User login successfully.';
                return response()->json($response, 200);
            } else {
                // If no dispatcher roll, deny access
                $response['data'] = ['error' => 'You have not dispatcher a roll.'];
                $response['success'] = false;
                $response['message'] = 'You have not dispatcher a roll.';
                return response()->json($response, 403);
            }

        } else {
            // Invalid credentials
            $response['data'] = ['error' => 'Unauthorised'];
            $response['success'] = false;
            $response['message'] = 'Unauthorised.';
            return response()->json($response, 401);
        }
    }

    public function sendResetLinkEmail(Request $request)
    {

        $this->validateEmail($request);
        $checkEmail = User::where('email', $request->email)->first();

        if (!$checkEmail) {
            $response['data'] = [];
            $response['success'] = false;
            $response['message'] = 'Your account is does not exist.';
            return response()->json($response, 400);            
        } else {
            $email_response = $this->broker()->sendResetLink(
                $request->only('email')
            );
        }

        if($email_response == Password::RESET_LINK_SENT){
            $response['data'] = [];
            $response['success'] = true;
            $response['message'] = 'Reset link sent to your email';
            return response()->json($response, 200);
        }else{
            $response['data'] = [];
            $response['success'] = false;
            $response['message'] = 'Unable to send reset link';
            return response()->json($response, 400);
        }
    }
}
