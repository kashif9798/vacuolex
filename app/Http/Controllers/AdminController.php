<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->flash();
        
        $admins = Admin::withCount(['microbes'])
        ->with(["role"])
        ->when(!empty($request->search), function($q) use ($request){
            $q->where('name','LIKE', '%'.$request->search.'%')
               ->orWhere('email','LIKE', '%'.$request->search.'%')
               ->orWhereHas('role', function($query) use ($request){
                    $query->where('title','LIKE', '%'.$request->search.'%');
               });
        })
        ->orderBy('microbes_count', 'desc')
        ->paginate(10);

        $breadcrumbs = [
            ['name' => "Admins"],
            ['name' => "Admins"]
        ];

        return view('/pages/admins/index', [
            'breadcrumbs'   => $breadcrumbs,
            "admins"        => $admins,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['name' => "Admins"],
            ['name' => "Create"]
        ];

        $roles = Role::select("id","title")->get();

        return view('/pages/admins/create', [
            'breadcrumbs' => $breadcrumbs,
            "roles"  => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            "image" => ["nullable", "image", "max:10000"],
            'password' => ['required', 'string', 'min:8'],
            "role_id"   => ['required', "exists:roles,id"],
        ];
        $this->validate($request, $rules);


        $create = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role_id" => $request->role_id,
        ];

        if(!empty($request->image)){
            $path = $request->file('image')->store('profile-pictures', "public");
            $create["image"] = asset("storage") . "/" . $path ;

        }

        Admin::create($create);

        session()->flash("admin_alert", "User <strong>" . $request->name . "</strong> successfully created.");

        return redirect()->route("admins.index");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $breadcrumbs = [
            ['name' => "Admins"],
            ['name' => "Create"]
        ];

        $roles = Role::select("id","title")->get();

        return view('/pages/admins/edit', [
            'breadcrumbs' => $breadcrumbs,
            "roles"  => $roles,
            "admin"  => $admin,
        ]);
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,'. $admin->id],
            "image" => ["nullable", "image", "max:10000"],
            'password' => ['nullable', 'string', 'min:8'],
            "role_id"   => ['required', "exists:roles,id"],
        ];
        $this->validate($request, $rules);


        $updated = [
            "name" => $request->name,
            "email" => $request->email,
            "role_id" => $request->role_id,
        ];

        if(!empty($request->image)){
            $path = $request->file('image')->store('profile-pictures', "public");
            $updated["image"] = asset("storage") . "/" . $path ;
        }

        if(!empty($request->password)){
            $updated["password"] = bcrypt($request->password);
        }

        $admin->update($updated);

        session()->flash("admin_alert", "User <strong>" . $request->name . "</strong> successfully updated.");

        return redirect()->route("admins.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        session()->flash("admin_alert", "User <strong>" . $admin->name . "</strong> successfully deleted.");

        return redirect()->route("admins.index");
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
            $updated["image"] = asset("storage") . "/" . $path ;

        }

        if(!empty($request->password)){
            $updated["password"] = bcrypt($request->password);
        }

        auth()->user()->update($updated);

        Session::flush();

        session()->flash("user_updated", "Profile successfully updated. Kindly login again.");
        
        Auth::logout();

        return redirect()->route("login.form");
    }
}
