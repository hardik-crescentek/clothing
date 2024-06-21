<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class CommonController extends Controller
{
    function sendContactMail(Request $request)
    {

        $response = [
            'success' => false,
            'data'    => null,
            'message' => null,
        ];

        $validator = Validator::make($request->all(), [
            'name'     =>  'required',
            'email'  =>  'required|email',
            'message' =>  'required'
        ]);
        if ($validator->fails()) {

            $response['data'] = ['error' => $validator->errors()];
            $response['success'] = false;
            $response['message'] = implode(",", $validator->errors()->all());
            return response()->json($response, 200);
        }

        $data = array(
            'email'      =>  $request->email,
            'name'      =>  $request->name,
            'message'   =>   $request->message
        );

        try {
            $to = config('mail.to_admin');
            Mail::to($to)->send(new ContactMail($data));
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
        $response['success'] = true;
        $response['message'] = 'Thanks for contacting us!';
        return response()->json($response, 200); 
    }
}
