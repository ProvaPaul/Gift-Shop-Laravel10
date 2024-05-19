<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderEmail;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders=Order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        
        $orders=$orders->leftJoin('users','users.id','orders.user_id');

        if(!empty($request->get('keyword'))){
            $orders=$orders->where('users.name','like','%' .$request->keyword.'%');
            $orders=$orders->orWhere('users.email','like','%' .$request->keyword.'%');
            $orders=$orders->orWhere('orders.id','like','%' .$request->keyword.'%');
        }
        $orders=$orders->paginate(10);
        return view('admin.orders.list',[
            'orders' => $orders
        ]);
    }
    public function create(){
        return view('admin.category.create');
    }
    public function detail($orderId){
        $order=Order::select('orders.*','countries.name as countryName')
        ->where('orders.id',$orderId)
        ->leftJoin('countries','countries.id','orders.country_id')
        ->first();

        $orderItems = OrderItem::where('order_id',$orderId)->get();
        return view('admin.orders.detail',[
            'order'=>$order,
            'orderItems'=>$orderItems
        ]);

    }
    public function changeOrderStatus(Request $request,$orderId){
        $order=Order::find($orderId);
        $order->status=$request->status;
        if($order->status=='delivered'){
            $order->payment_status='paid';

        }
        $order->shipped_date=$request->shipped_date;
        $order->save();

        session()->flash('success','Order Status updated succcessfully');
        //flash diye session msg banaise
        return response()->json([
            'status' => true,
            'message' =>'Status changed succcessfully'
        ]);


    }
    public function sendInvoiceEmail(Request $request,$orderId){
        OrderEmail($orderId,$request->userType);
        

        session()->flash('success','Order Email sent succcessfully');
        //flash diye session msg banaise
        return response()->json([
            'status' => true,
            'message' =>'Status changed succcessfully'
        ]);


    }

    public function downloadPdf(Request $request){
        $orders=Order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        
        $orders=$orders->leftJoin('users','users.id','orders.user_id');

        if(!empty($request->get('keyword'))){
            $orders=$orders->where('users.name','like','%' .$request->keyword.'%');
            $orders=$orders->orWhere('users.email','like','%' .$request->keyword.'%');
            $orders=$orders->orWhere('orders.id','like','%' .$request->keyword.'%');
        }
        $orders=$orders->paginate(10);
    
        $pdf = PDF::loadView('pdf.order', ['orders' => $orders]);
        return $pdf->download('order.pdf'); 
    }


    

    public function new()
    {
        return view('admin.orders.new'); // Ensure the view exists as well
    }
    
   
}
