<?php

namespace App\Http\Controllers\University;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth','university']); 
    }
    public function index()
    {
        $employee_data = User::with(['getRole'])
            ->whereHas('roles', function ($q) {
                $q->where('name', 'employee');
            })->get()->count();
       
        return view('university.dashboard.index')->with(
            array(
                'employee_data' => $employee_data,
            )
        );
    }
    
    

   
}
