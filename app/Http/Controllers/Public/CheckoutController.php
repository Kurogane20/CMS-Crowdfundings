<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Reward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Mail;
use App\Mail\BankTransferReceived;
use App\Jobs\SendBankTransferReceivedEmail;
use App\Jobs\SendWhatsAppNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class CheckoutController extends Controller
{

    /**
     * @param Request $request
     * @param int $reward_id
     * @return mixed
     */
    public function addToCart(Request $request, $reward_id = 0){
        if ($reward_id){
            //If checkout request come from reward
            session( ['cart' =>  ['cart_type' => 'reward', 'reward_id' => $reward_id] ] );

            $reward = Reward::find($reward_id);
            if($reward->campaign->is_ended()){
                $request->session()->forget('cart');
                return redirect()->back()->with('error', trans('app.invalid_request'));
            }
        }else{
            //Or if comes from donate button
            session( ['cart' =>  ['cart_type' => 'donation', 'campaign_id' => $request->campaign_id, 'amount' => $request->amount ] ] );
        }


        return redirect(route('checkout'));
    }

    /**
     * @return mixed
     *
     * Checkout page
     */
    public function checkout(){
       
        $title = trans('app.checkout');

        if ( ! session('cart')){
            return view('public.checkout.empty', compact('title'));
        }

        $reward = null;
        if(session('cart.cart_type') == 'reward'){
            $reward = Reward::find(session('cart.reward_id'));
            $campaign = Campaign::find($reward->campaign_id);
        }elseif (session('cart.cart_type') == 'donation'){
            $campaign = Campaign::find(session('cart.campaign_id'));
        }
        if (session('cart')){
            return view('public.checkout.index', compact('title', 'campaign', 'reward'));
        }
        return view('public.checkout.empty', compact('title'));
    }

    public function checkoutPost(Request $request){
        
        $title = trans('app.checkout');

        if ( ! session('cart')){
            return view('public.checkout.empty', compact('title'));
        }

        $cart = session('cart');
        $input = array_except($request->input(), '_token');
        session(['cart' => array_merge($cart, $input)]);
        $amount = 0;
        if(session('cart.cart_type') == 'reward'){
            $reward = Reward::find(session('cart.reward_id'));
            $campaign = Campaign::find($reward->campaign_id);
        }elseif (session('cart.cart_type') == 'donation'){
            $campaign = Campaign::find(session('cart.campaign_id'));
            $amount = session('cart.amount');
        }
        
        //dd(session('cart'));
        return view('public.checkout.payment', compact('title', 'campaign','amount'));
    }

    
    
    public function payment_success_html(){
        $html = ' <div class="payment-received">
                            <h1> <i class="fa fa-check-circle-o"></i> '.trans('app.payment_thank_you').'</h1>
                            <p>'.trans('app.payment_receive_successfully').'</p>
                            <a href="'.route('home').'" class="btn btn-filled">'.trans('app.home').'</a>
                        </div>';
        return $html;
    }
    
    public function paymentSuccess(Request $request, $transaction_id = null){
        if ($transaction_id){
            $payment = Payment::whereLocalTransactionId($transaction_id)->whereStatus('initial')->first();
            if ($payment){
                $payment->status = 'pending';
                $payment->save();
            }
        }

        $title = trans('app.payment_success');
        return view('public.checkout.success', compact('title'));
    }

    public function paymentBankTransferReceive(Request $request){
        $rules = [          
            // 'account_number'    => 'required',            
            // 'branch_name'       => 'required',
            'bukti_pembayaran' => 'required',
        ];
        $this->validate($request, $rules);

        //get Cart Item
        if ( ! session('cart')){
            $title = trans('app.checkout');
            return view('public.checkout.empty', compact('title'));
        }
        //Find the campaign
        $cart = session('cart');

        $amount = 0;
        if(session('cart.cart_type') == 'reward'){
            $reward = Reward::find(session('cart.reward_id'));
            $amount = $reward->amount;
            $campaign = Campaign::find($reward->campaign_id);
        }elseif (session('cart.cart_type') == 'donation'){
            $campaign = Campaign::find(session('cart.campaign_id'));
            $amount = $cart['amount'];
        }
        $currency = get_option('currency_sign');
        $user_id = null;
        if (Auth::check()){
            $user_id = Auth::user()->id;
        }
        //Create payment in database
        $transaction_id = 'tran_'.time().str_random(6);
        // get unique recharge transaction id
        while( ( Payment::whereLocalTransactionId($transaction_id)->count() ) > 0) {
            $transaction_id = 'reid'.time().str_random(5);
        }
        $transaction_id = strtoupper($transaction_id); 

        $image_name = '';
        if ($request->hasFile('bukti_pembayaran')){
            $image = $request->file('bukti_pembayaran');
            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
            }


            $upload_dir = './storage/uploads/bukti_pembayaran/';
            if ( ! file_exists($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }

            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $resized = Image::make($image)->resize(300, 200);

            $image_name = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
            $imageFileName = $upload_dir.$image_name;

            try{
                $resized->save($imageFileName);
            } catch (\Exception $e){
                return $e->getMessage();
            }
        }

        $payments_data = [
            'name' => session('cart.full_name'),
            'email' => session('cart.email'),
            'phone' => session('cart.phone'),
            

            'user_id'               => $user_id,
            'campaign_id'           => $campaign->id,
            'reward_id'             => session('cart.reward_id'),

            'amount'                => $amount,
            'payment_method'        => 'bank_transfer',
            'status'                => 'pending',
            'currency'              => $currency,
            'local_transaction_id'  => $transaction_id,

            'contributor_name_display'  => session('cart.contributor_name_display'),

            'bank_swift_code'   => $request->bank_swift_code,
            'account_number'    => $request->account_number,
            'branch_name'       => $request->branch_name,
            'branch_address'    => $request->branch_address,
            'account_name'      => $request->account_name,
            'iban'              => $request->iban,
            'bukti_pembayaran'  => $image_name            
        ];
        
         
        //Create payment and clear it from session
        $created_payment = Payment::create($payments_data);
        $request->session()->forget('cart');
        // dd($path);



        //send email notification        
        SendBankTransferReceivedEmail::dispatch($payments_data);
        // Kirim notifikasi WhatsApp
        $phone =$payments_data['phone'];
        $donasi = $campaign->title;
        $message = 'Assalamualaikum Warahmatullahi Wabarakatuh, Donasi anda untuk'. ' '.$donasi . ' Sebesar'. ' ' .'Rp'. number_format($amount, 0, ',', '.') . ' ' . ' telah kami terima.';
        SendWhatsAppNotification::dispatch($phone, $message);
        return ['success'=>1, 'msg'=> trans('app.payment_received_msg'), 'response' => $this->payment_success_html()];
        
    }

    public function mandiriPaymentPage(Request $request){
        // Lakukan logika yang diperlukan untuk halaman pembayaran Mandiri
        // Contoh: Mendapatkan data kampanye atau reward yang dibeli
        // Kemudian kirimkan data tersebut ke view untuk ditampilkan
        $title = trans('app.checkout');

        if ( ! session('cart')){
            return view('public.checkout.empty', compact('title'));
        }

        $cart = session('cart');
        $input = array_except($request->input(), '_token');
        session(['cart' => array_merge($cart, $input)]);
        $amount = 0;
        $name = '';
        if(session('cart.cart_type') == 'reward'){
            $reward = Reward::find(session('cart.reward_id'));
            $campaign = Campaign::find($reward->campaign_id);
        }elseif (session('cart.cart_type') == 'donation'){
            $campaign = Campaign::find(session('cart.campaign_id'));
            $amount = session('cart.amount');
            $name = session('cart.full_name');
        }
        return view('public.checkout.bsi', compact('title', 'campaign','amount', 'name'));
    }

    public function muamalatPaymentPage(Request $request){
        // Lakukan logika yang diperlukan untuk halaman pembayaran Muamalat
        // Contoh: Mendapatkan data kampanye atau reward yang dibeli
        // Kemudian kirimkan data tersebut ke view untuk ditampilkan
        $title = trans('app.checkout');

        if ( ! session('cart')){
            return view('public.checkout.empty', compact('title'));
        }

        $cart = session('cart');
        $input = array_except($request->input(), '_token');
        session(['cart' => array_merge($cart, $input)]);
        $amount = 0;
        $name ='';
        if(session('cart.cart_type') == 'reward'){
            $reward = Reward::find(session('cart.reward_id'));
            $campaign = Campaign::find($reward->campaign_id);
        }elseif (session('cart.cart_type') == 'donation'){
            $campaign = Campaign::find(session('cart.campaign_id'));
            $amount = session('cart.amount');
            $name = session('cart.full_name');
        }
        return view('public.checkout.muamalat', compact('title', 'campaign','amount','name'));
    }
}
