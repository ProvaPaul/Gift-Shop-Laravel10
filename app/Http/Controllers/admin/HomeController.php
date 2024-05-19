<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index(){

        $totalOrders= Order::where('status','!=','cancelled')->count();
        $totalProducts= Product::count();
        $totalCustomers= User::where('role',1)->count();
        $totalRevenue= Order::where('status','!=','cancelled')->sum('grand_total');
//this month
        $startdate=Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentdate=Carbon::now()->format('Y-m-d');
        $monthRevenue= Order::where('status','!=','cancelled')
        ->whereDate('created_at','>=',$startdate)
        ->whereDate('created_at','<=',$currentdate)
        ->sum('grand_total');
//last month

$startdate1=Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
$currentdate1=Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
$month=Carbon::now()->subMonth()->startOfMonth()->format('M');

$monthRevenue1= Order::where('status','!=','cancelled')
->whereDate('created_at','>=',$startdate1)
->whereDate('created_at','<=',$currentdate1)
->sum('grand_total');
//last 30 days

$startdate2=Carbon::now()->subDays()->startOfMonth()->format('Y-m-d');
$monthRevenue2= Order::where('status','!=','cancelled')
->whereDate('created_at','>=',$startdate2)
->whereDate('created_at','<=',$currentdate)
->sum('grand_total');


        return view('admin.dashboard',[
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'monthRevenue' => $monthRevenue,
            'monthRevenue1' => $monthRevenue1,
            'monthRevenue2' => $monthRevenue2,
            'month' => $month,
        ]);
        // $admin = Auth::guard('admin')->user();

        // echo 'Welcome'.$admin->name.'<a href="'.route('admin.logout').'">Logout</a>';
    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
     }
}

