<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use function Laravel\Prompts\alert;

class UserSettingsController extends Controller
{
    public function index()
    {
        return view('client.setup');
    }

    public function settings()
    {
        return view('client.settings');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'date' => 'required',
            'month' => 'required',
            'year' => 'required',
            'username' => 'required|min:6',
            'gender' => 'nullable',
            'pfp' => 'image|max:2048|nullable',
            'bio' => 'nullable|string',
            'website' => 'url:http,https|nullable'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $dob = Carbon::create($request->year, $request->month, $request->date);
        
        if ($request->hasFile('pfp')) {
            $get_file_image = $request->file('pfp');
            $get_image_name = $get_file_image->getClientOriginalName();
            $get_image_extension = $get_file_image->getClientOriginalExtension();
            $image_name = current(explode('.', $get_image_name));
            $imagePath = time() . "-" . $image_name . "." . $get_image_extension;
            $get_file_image->move('./client/image/pfp', $imagePath);
        } else {
            $imagePath = "placeholder.png";
        }

        UserSettings::create([
            'user_id' => $request->user()->user_id,
            'username' => $request->username,
            'dob' => $dob,
            'gender' => $request->gender,
            'pfp_url' => $imagePath,
            'bio' => $request->bio,
            'website' => $request->website
        ]);
        if ($request->expectsJson()){
            return response();
        } else {
            return redirect('/home');
        }
        
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'date' => 'required',
            'month' => 'required',
            'year' => 'required',
            'username' => 'required|min:6',
            'gender' => 'nullable',
            'pfp' => 'image|max:2048|nullable',
            'bio' => 'nullable|string',
            'website' => 'url:http,https|nullable'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $dob = Carbon::create($request->year, $request->month, $request->date);
        
        if ($request->hasFile('pfp')) {
            $get_file_image = $request->file('pfp');
            $get_image_name = $get_file_image->getClientOriginalName();
            $get_image_extension = $get_file_image->getClientOriginalExtension();
            $image_name = current(explode('.', $get_image_name));
            $imagePath = time() . "-" . $image_name . "." . $get_image_extension;
            $get_file_image->move('./client/image/pfp', $imagePath);
        } else {
            $imagePath = $request->user()->settings->pfp_url;
        }

        UserSettings::where('user_id', $request->user()->user_id)->update([
            'username' => $request->username,
            'dob' => $dob,
            'gender' => $request->gender,
            'pfp_url' => $imagePath,
            'bio' => $request->bio,
            'website' => $request->website
        ]);
        if ($request->expectsJson()){
            return response();
        } else {
            return redirect('/profile');
        }
    }
}
