<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserRoleRelation;
use Yajra\Datatables\Datatables;
use Validator;
use App\User;
use Crypt;

class StudentManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        return view('admin.student.index', $data);
    }

    public function studentData()
    {
        $result = User::with(['getRole'])
            ->whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })->get();
         //   dd($result);
        return Datatables::of($result)
            ->addColumn('action', function ($result) {
                return '<a href ="' . url('admin/student-management') . '/' . Crypt::encrypt($result->id) . '/edit"  class="btn btn-xs btn-warning edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
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
        $university = User::with(['getRole'])
            ->whereHas('roles', function ($q) {
                $q->where('name', 'university');
            })->get();
        $data['university'] = $university;
        return view('admin.student.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName'         =>  'required',
            'lastName'          =>  'required',
            'name'              => 'required|max:255|min:2|unique:users',
            'email'             => 'required|email|max:255|unique:users',
            'phone'             =>  'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {

            $studentData =  User::create([
                'firstName'      => $request->firstName,
                'lastName'       => $request->lastName,
                'name'           => $request->name,
                'email'          => $request->email,
                "password"       => bcrypt($request->password),
                'phone'          => $request->phone,
                'university_id'     => $request->university_id,
            ]);

            $roleArray = array(
                'user_id' => $studentData->id,
                'role_id' => 3, // student
            );
            UserRoleRelation::insert($roleArray);
            $user = User::where('id', $studentData->id)
                ->first();

            return redirect('/admin/student-management')->with(array('status' => 'success', 'message' => 'New Student Successfully created!'));
        } catch (\Exception $e) {
            //return back()->with(array('status' => 'danger', 'message' =>  $e->getMessage()));
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
            $studentData = User::find(\Crypt::decrypt($id));
            if ($studentData) {
                $data['studentData'] = $studentData;
                $university = User::with(['getRole'])
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'university');
                })->get();
            $data['university'] = $university;
                return view('admin.student.edit', $data);
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
            'firstName'     =>'required',
            'lastName'      =>'required',
            'name'          => 'required|max:255|min:2|unique:users,name,' . \Crypt::decrypt($id),
            'email'         => 'required|min:2|unique:users,email,' . \Crypt::decrypt($id),
            'phone'         =>'required',
            'university_id' =>'required',
            

        ));
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $companyData = User::find(\Crypt::decrypt($id));
            $updateData = array(
                "firstName"     =>$request->has('firstName') ? $request->firstName : "",
                "lastName"      =>$request->has('lastName') ? $request->lastName : "",
                "name"          => $request->has('name') ? $request->name : "",
                "email"         => $request->has('email') ? $request->email : "",
                "phone"         =>$request->has('phone') ? $request->phone : "",
                "university_id" =>$request->has('university_id') ? $request->university_id : "",
            );
            $companyData->update($updateData);
            return redirect('/admin/student-management')->with(array('status' => 'success', 'message' => 'Update record successfully.'));
        } catch (\exception $e) {
            //return back()->with(array('status' => 'danger', 'message' =>  $e->getMessage()));
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
