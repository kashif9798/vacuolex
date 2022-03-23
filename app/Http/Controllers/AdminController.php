<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the profile resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function profile(Admin $admin)
    {
        $breadcrumbs = [
            ['name' => "Profile"]
        ];
        return view('/pages/profile/index', ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     *  Update the profile resource in storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,'. auth()->user()->id],
            "image" => ["nullable", "image", "max:10000"],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        $this->validate($request, $rules);

        $updated = [
            "name" => $request->name,
            "email" => $request->email
        ];

        if(!empty($request->image)){
            $path = $request->file('image')->store('profile-pictures', "public");
            $updated["image"] = Storage::disk('public')->path($path);

        }

        if(!empty($request->password)){
            $updated["password"] = bcrypt($request->password);
        }

        auth()->user()->update($updated);

        Session::flush();

        $request->flash("user_updated", "User successfully updated. Kindly login again.");
        
        Auth::logout();

        return redirect()->route("login.form");
    }
}
