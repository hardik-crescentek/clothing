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
use Google\Client as GoogleClient;


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
        \Log::info("login call");
        \Log::info(request()->all());
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

    // public function sendPushNotification(Request $request)
    // {
    //     // $fcmToken = "dJLe4InHQp-4uHewNwZEvf:APA91bEwyenJ0CaKesFizN0e6TuUe3L_WlfTZrhf_ZJKDtTu49vw50QZVzzVsr7RzKauZha_AJYeJRxhNJeGLE9dDE90HrVCV9m-UCAPlQUSi0IndwAqeUs";

    //     // $title = "Herll";
    
    //     // $body = "kjhn";
    
    //     // $message = ([
    
    //     //     'token' => $fcmToken,
    
    //     //     'notification' => [
    
    //     //         'title' => $title,
    
    //     //         'body' => $body
    
    //     //     ],
    
    //     // ]);
    
    //     // $this->notification->send($message);

    //     $credentialsFilePath = public_path('service-account.json');
  
    //     $client = new GoogleClient();

    //     $client->setAuthConfig($credentialsFilePath);

    //     $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    //     $client->refreshTokenWithAssertion();
    //     $token = $client->getAccessToken();

    //     $access_token = $token['access_token'];
    //     // Set up the HTTP headers
    //     $headers = [
    //         "Authorization: Bearer $access_token",
    //         'Content-Type: application/json'
    //     ];

    //     $data = [
    //         "message" => [
    //             "topic" => "shyam",
    //             "notification" => [
    //                 "title" => "New Notification",
    //                 "body" => "Notification Content",

    //             ],
    //             "apns" => [
    //                 "payload" => [
    //                     "aps" => [
    //                         "sound" => "default"
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ];
    //     $payload = json_encode($data);

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/clothings-99ac9/messages:send');
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    //     curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
    //     $response = curl_exec($ch);
    //     $err = curl_error($ch);
    //     curl_close($ch);

    // }

    public function sendPushNotification(Request $request)
    {
        $credentialsFilePath = public_path('service-account.json');
        
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        
        // Retrieve the access token
        $token = $client->getAccessToken();
        $access_token = $token['access_token'];
        
        // Set up the HTTP headers
        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];
        
        // Prepare the payload (updated to match the new structure)
        $data = [
            "message" => [
                "token" => "fNujMxHERWOTSOHT6QeHaw:APA91bGdew601C2eutI-zJLPVQUwtf5lYtvOSCrxFS0y_UVZWc1A9Hjdg83lFz9JI_N76huBy7WWDMk2JTa0meqLk4hzlGbopRoMARB4ASoPpd-CTO3Uc3I", // Example token
                "notification" => [
                    "body" => "This is an FCM notification message!", // Notification body
                    "title" => "FCM Message" // Notification title
                ]
            ]
        ];

        $payload = json_encode($data);

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/clothings-99ac9/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (useful for testing, but not recommended for production)
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        
        // Execute the cURL request and capture the response
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        // Check if there's any error during the request
        if ($err) {
            // Log error or return a response
            return response()->json(['error' => $err], 500);
        } else {
            // Return response from FCM
            return response()->json(['response' => json_decode($response)], 200);
        }
    }

}
