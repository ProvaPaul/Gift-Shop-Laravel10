<?php

namespace App\Http\Controllers;
use App\Mail\ResetPasswordEmail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\wishlist;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');


    }
    public function register(){
        return view('front.account.register');
    }
    

    public function processRegister(Request $request){

        $validator=Validator::make($request->all(),[
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:5|confirmed',
        //5 length er pass

        ]);

        if ($validator->passes()){
            $user =new User();
                $user->name=$request->name;
                $user->email=$request->email;
                $user->phone=$request->phone;

                $user->password= Hash::make($request->password);

                $user->save();

                //  $request->session()->flash('success','Registered succcessfully');
                session()->flash('success','Registered succcessfully');


            return response()->json([
                'status' => true,
            ]);
            
        }
        else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
           
     }
     public function authenticate(Request $request){

        $validator=Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            //mane 5 length er pass
    
            ]);

            if ($validator->passes()){
                if(Auth::attempt(['email'=> $request->email,'password'=>$request->password],$request->get('remember'))){

                    if(session()->has('url.intended')){
                        return redirect(session()->get('url.intended'));

                        
                    }
                    return redirect()->route('account.profile');

                }
                else{
                    // session()->flash('error','Either Email/Password is incorrect!');
                    return redirect()->route('account.login')->withInput($request->only('email'))
                    ->with('error','Either Email/Password is incorrect!');
    
                }
                
            }
            else {
                return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
            }

     }
     public function profile(){
        return view('front.account.profile');

        
     }
     public function logout(){
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success','You successfully logged out');

        
     }
     public function orders(){
        $user=Auth::user();
        $orders=Order::where('user_id',$user->id)->orderBy('created_at','DESC')->get();
        $data['orders']=$orders;
        return view('front.account.order',$data);

        
     }
     public function orderDetail($id){
        $user=Auth::user();
        $data=[];
        $order=Order::where('user_id',$user->id)->where('id',$id)->first();
        $data['order']=$order;

        $orderItems=OrderItem::where('order_id',$id)->get();
        $data['orderItems']=$orderItems;

        $orderItemsCount=OrderItem::where('order_id',$id)->count();
        $data['orderItemsCount']=$orderItemsCount;
        return view('front.account.order-detail',$data);

        
     }

     public function wishlist(){
        $wishlists =Wishlist::where('user_id',Auth::user()->id)->get();
        $data=[];
        $data['wishlists']=$wishlists;
        return view('front.account.wishlist',$data);

     }

     public function removeProductFromWishList(Request $request){
        $wishlist =Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->first();
        if($wishlist==null){
            session()->flash('error','Product already removed');
            return response()->json([
                'status'=> true,
                // 'message'=> '<div class="alert alert-danger">Product not found</div>'
            ]);
        }
        else{
            Wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->delete();
            session()->flash('success','Product removed successfully.');
            return response()->json([
                'status'=> true,
                // 'message'=> '<div class="alert alert-danger">Product not found</div>'
            ]);

        }
        // $data=[];
        // $data['wishlists']=$wishlists;
        // return view('front.account.wishlist',$data);

     }


     public function showChangePasswordForm(){
        return view('front.account.change-password');

     }

     public function changePassword(Request $request){
        $validator=Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
    
            ]);


            if ($validator->passes()){
                $user =User::select('id','password')->where('id',Auth::user()->id)->first();

                if(!Hash::check($request->old_password,$user->password)){
                session()->flash('error','Your old password is incorrect, please try again');
    
                return response()->json([
                    'status' => false,
                ]);

                }
             

                User::where('id',$user->id)->update([
                    'password'=>Hash::make($request->new_password)
                ]);

                session()->flash('success','You have successfully changed the pasword');
    
                return response()->json([
                    'status' => true,
                ]);
                
            }
            else {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
     }

     public function forgotPassword(){
        return view('front.account.forgot-password');
     }

    //  public function processForgotPassword(Request $request){
    //     $validator=Validator::make($request->all(),[
    //         'email' => 'required|email|exists:users,email'
    //         ]);


    //         if ($validator->passes()){
                     
    //         }
    //         else {
    //             return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
    //         }

    //         $token=  Str::random(60);
    //         \DB::table('password_reset_tokens')->where('email',$request->email)->delete();
    //         \DB::table('password_reset_tokens')->insert([
    //             'email' => $request->email,
    //             'token' => $token,
    //             'created_at' => now()
                
    //         ]);

    //         //send email here
    //         $user=User::where('email',$request->email)->first();
    //         $formData=[
    //             'token' => $token,
    //             'user' => $user,
    //             'mail_subject' => 'You have requested to reset your password'
                

    //         ];
    //         Mail::to($request->email)->send(new ResetPasswordEmail($formData));

    //         return redirect()->route('front.forgotPassword')->with('success','Please click your inbox to reset your password');

    //  }
    public function processForgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }
    
        $token = Str::random(60);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);
    
        // Send email here
        $user = User::where('email', $request->email)->first();
        $formData = [
            'token' => $token,
            'user' => $user,
            'mail_subject' => 'You have requested to reset your password'
        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($formData));
    
        return redirect()->route('front.forgotPassword')->with('success', 'Please check your inbox to reset your password');
    }
    
     public function resetPassword($token){
        $tokenExist =DB::table('password_reset_tokens')->where('token',$token)->first();

        if($tokenExist==null){
            return redirect()->route('front.forgotPassword')->with('error','Invalid request');

        }
        return view('front.account.reset-password',[
            'token'=>$token
        ]);

     }

     public function processResetPassword(Request $request){
        $token =$request->token;
        $tokenExist =DB::table('password_reset_tokens')->where('token',$token)->first();

        if($tokenExist==null){
            return redirect()->route('front.forgotPassword')->with('error','Invalid request');

        }

        $user=User::where('email',$tokenExist->email)->first();


        $validator=Validator::make($request->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password'
            ]);


            if ($validator->passes()){
                     
            }
            else {
                return redirect()->route('front.resetPassword',$token)->withErrors($validator);
            }

            User::where('id',$user->id)->update([
                'password'=>Hash::make($request->new_password)
            ]);

            DB::table('password_reset_tokens')->where('email',$user->email)->delete();

            return redirect()->route('account.login')->with('success','You have successfully updated password');




     }

}
