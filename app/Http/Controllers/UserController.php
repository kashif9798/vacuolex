<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        
        $subscribers = User::withCount(['microbes_collected'])
        ->when(!empty($request->search), function($q) use ($request){
            $q->where('name','LIKE', '%'.$request->search.'%')
               ->orWhere('email','LIKE', '%'.$request->search.'%');
        })
        ->orderBy('microbes_collected_count', 'desc')
        ->paginate(10);

        $breadcrumbs = [
            ['name' => "Subscribers"],
            ['name' => "Subscribers"]
        ];

        return view('/pages/subscribers/index', [
            'breadcrumbs'   => $breadcrumbs,
            "subscribers"   => $subscribers,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        $user->microbes_collected()->detach();

        $user->delete();

        session()->flash("subscriber_alert", "Subscriber <strong>" . $user->name . "</strong> successfully deleted.");

        return redirect()->route("subscribers.index");
    }
}
