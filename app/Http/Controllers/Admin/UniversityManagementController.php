<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserRoleRelation;
use Yajra\Datatables\Datatables;
use App\Notifications\UniversityRegitration;
use Validator;
use App\User;
use Crypt;

class UniversityManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->admin = User::whereHas('roles', function($q){
            $q->where('name', 'admin');
        })->first();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        return view('admin.university.index', $data);
    }

    public function universityData()
    {
        $result = User::with(['getRole'])
            ->whereHas('roles', function ($q) {
                $q->where('name', 'university');
            })->get();
        return Datatables::of($result)
            ->addColumn('action', function ($result) {
                return '<a href ="' . url('admin/university-management') . '/' . Crypt::encrypt($result->id) . '/edit"  class="btn btn-xs btn-warning edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
            <a data-id =' . Crypt::encrypt($result->id) . ' class="btn btn-xs btn-danger delete" style="color:#fff"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        return view('admin.university.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:255|min:2|unique:users',
            'email'         => 'required|email|max:255|unique:users',
            'logo'         =>  'required'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {

            $universityData =  User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                "password"  => bcrypt($request->password),
                "website"   =>$request->website,
            ]);
            if ($request->hasFile('logo')) {
                $user = User::find($universityData->id);
                $file = $request->file('logo');
                $filename = 'university-logo-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move('public/uploads/university_logo/', $filename);
                $user->logo = $filename;
                $user->save();
            }
            $roleArray = array(
                'user_id' => $universityData->id,
                'role_id' => 2, // comapny
            );
            UserRoleRelation::insert($roleArray);
            $user = User::where('id', $universityData->id)
                ->first();
                if ($universityData) {
                    $notificationData = [
                        "adminName" => $user[0]['name'],
                        "username" => $request->name,
                        "useremail" => $user[0]['email'],
                        'userPhone' => $user[0]['phone'],
                        "message" => "University register",
                    ];
                    //dd($notificationData);
                    $this->admin->notify(new UniversityRegitration($notificationData));
                 }

            return redirect('/admin/university-management')->with(array('status' => 'success', 'message' => 'New Company Successfully created!'));
        } catch (\Exception $e) {
            return back()->with(array('status' => 'danger', 'message' =>  $e->getMessage()));
            return back()->with(array('status' => 'danger', 'message' =>  'Something went wrong. Please try again later.'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $universityData = User::find(\Crypt::decrypt($id));
            if ($universityData) {
                $data['universityData'] = $universityData;
                return view('admin.university.edit', $data);
            }
        } catch (\Exception $e) {
            return back()->with(array('status' => 'danger','message' => $e->getMessage()));
        }
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
        // dd($request->all());
        $validator = Validator::make($request->all(), array(
            'name'          => 'required|max:255|min:2|unique:users,name,' . \Crypt::decrypt($id),
            'email'         => 'required|min:2|unique:users,email,' . \Crypt::decrypt($id),
            // 'logo'          => 'required',

        ));
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $universityData = User::find(\Crypt::decrypt($id));
            $updateData = array(
                "name" => $request->has('name') ? $request->name : "",
                "email" => $request->has('email') ? $request->email : "",

            );
            $universityData->update($updateData);
            if ($request->logo != "") {
                $uni_save = User::find(\Crypt::decrypt($id));
                if ($uni_save->logo != "") {
                    if (file_exists(public_path('/uploads/university_logo/' . $uni_save->logo))) {
                        $del_previous_pic = unlink(public_path('/uploads/university_logo/' . $uni_save->logo));
                    }
                }
                $file = $request->file('logo');
                $filename = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move('public/uploads/university_logo', $filename);
                $uni_save->logo = $filename;
                $uni_save->save();
            }
            return redirect('/admin/university-management')->with(array('status' => 'success', 'message' => 'Update record successfully.'));
        } catch (\exception $e) {
            return back()->with(array('status' => 'danger', 'message' =>  $e->getMessage()));
            return back()->with(array('status' => 'danger', 'message' => 'Some thing went wrong! Please try again later.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $User, $id)
    {
        User::find(Crypt::decrypt($id))->delete();
    }
}
