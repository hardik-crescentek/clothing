<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Util;
use App\User;
use Auth;
use Hash;
use App\Settings;

class ProfileController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $user = User::find($auth->id);
        $settings = Settings::where('id',1)->first();
        return view('users.profile', compact('user','settings'));
    }

    public function update(Request $request)
    {
        $auth = Auth::user();
        $user = User::find($auth->id);

        //Validate name, email and password fields
        $this->validateWithBag('profile', $request, [
            'firstname'    => 'required|max:120',
            'phone'   => 'required',
            'dob'     => 'required',
            'address' => 'required',
            'city'    => 'required',
            'state'   => 'required',
            'zip'     => 'required',
            'avatar'  => 'image|mimes:png,jpg,jpeg|max:' . config('constants.image_size_limit'),
        ]);

        $input = $request->only(['name', 'phone', 'dob', 'address', 'city', 'state', 'zip']); //Retreive the name, email and password fields

        if (!empty($request->file('avatar'))) {
            $image = Util::uploadFile($request, 'avatar', config('constants.user_img_path'));
            if($image) {

                Util::genrateThumb($image);
                $input['avatar'] = $image;
                Util::removeFile($user->avatar);
            }

        }
        
        $user->update($input);
        return redirect()->route('profile')->with('success', 'Profile successfully updated.');
    }

    public function changePassword(Request $request)
    {

        $auth = Auth::user();
        $user = User::find($auth->id);

        $this->validateWithBag('password', $request, [
            'current_pass'=> 'required',
            'newpassword' => 'required|min:6|confirmed',
        ]);

        if (Hash::check($request->current_pass, $user->password)) {
            // $user->password = bcrypt($request->newpassword);
            $user->password = Hash::make($request->newpassword);
            $user->save();
            return redirect()->route('profile')->with('success', 'Password successfully updated.');
        } else {
            return redirect()->route('profile')->with('error', "Current Password doesn't match");
        }
    }
}
