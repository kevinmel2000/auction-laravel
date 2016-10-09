<?php

namespace App\Http\Controllers;

use App\Libraries\VK\VKClass;
use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileDataRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\AddressRequest;
use Storage;
use Validator;
use View;
use Response;
use Auth;
use Hash;

class ProfileController extends Controller
{
    public function index() {
        return view(
            'profile.index', 
            [
                'user' => Auth::user(), 
                'countries' => Country::all()
            ]
        );
    }
    
    public function createUpload ()
    {
        return Response::json([
            'status' => 'success',
            'html' => preg_replace("/\\r\\n|\\n/", "", View::make('profile.upload')->render())
        ]);
    }

    public function storeUpload (Request $request)
    {
        $name = md5(time().uniqid()) . '.jpg';
        $user = User::where('id', '=', Auth::user()->id)->first();

        if($user->avatar && Storage::disk('s3')->exists('avatars/' . $user->avatar)) {
            Storage::disk('s3')->delete('avatars/' . $user->avatar);
        }
        
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('image')));
        $result = Storage::disk('s3')->put('avatars/' . $name, $image, 'public') && $user->update(['avatar' => $name]);
        return Response::json([
            'status' => $result ? 'success' : 'error',
            'avatar' => Storage::cloud()->url('avatars/' . $name)
        ]);
    }

    public function destroyUpload (Request $request)
    {
        $user = Auth::user();
        
        if ($user->avatar) {
            $result = true;
            
            if(Storage::disk('s3')->exists('avatars/' . $user->avatar)) {
                $result = Storage::disk('s3')->delete('avatars/' . $user->avatar);
            } 
            
            return Response::json([
                'status' => $result && $user->update(['avatar' => '']) ? 'success' : 'error',
                'avatar' => asset('assets/img/avatar.png')
            ]);
        }
    }
    
    public function storeVKAvatar ()
    {
        $vk = new VKClass();
        $avatar = $vk->getProfileAvatar(Auth::user()->vk_id);

        return Response::json([
            'status' => Auth::user()->update(['avatar' => $avatar]) ? 'success' : 'error',
            'avatar' => $avatar
        ]);
    }
    
    public function updateProfile(ProfileRequest $request)
    {
        return Response::json([
            'status' => Auth::user()->update(['name' => $request->input('name')]) ? 'success' : 'error'
        ]);
    }

    public function updateData (ProfileDataRequest $request)
    {
        return Response::json([
            'status' => Auth::user()->update([
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'gender' => $request->input('gender'),
                'age' => \Carbon\Carbon::parse($request->input('age'))
            ]) ? 'success' : 'error'
        ]);
    }
    
    public function updatePassword (PasswordRequest $request)
    {
        return Response::json([
            'status' => Auth::user()->update([
                'password' => Hash::make($request->input('password'))
            ]) ? 'success' : 'error'
        ]);
    }
    
    public function updateAddress (AddressRequest $request)
    {
        $address = Auth::user()->address ?: new Address();

        $address->user_id = Auth::id();
        $address->name = $request->input('name');
        $address->post = $request->input('post');
        $address->country_id = $request->input('country');
        $address->city = $request->input('city');
        $address->address = $request->input('address');
        $address->phone = $request->input('phone');
        $address->details = $request->input('details');

        return Response::json([
            'status' => $address->save() ? 'success' : 'error'
        ]);
    }

    public function getBetBuy(){
        return view(
            'profile.buy-bet',
            [
                'user' => Auth::user(),
                'countries' => Country::all()
            ]
        );
    }

    public function postBuyBet(Request $request){
        $mrh_login = "auctiontest1";      // your login here
        $mrh_pass1 = "Az-123456789";   // merchant pass1 here

        $inv_id    = 5;        // shop's invoice number
        // (unique for shop's lifetime)
        $inv_desc  = "desc";   // invoice desc
        $out_summ  = "5.12";   // invoice summ

        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

        $url = "https://auth.robokassa.ru/Merchant/Index.aspx?isTest=1&MrchLogin=$mrh_login&".
            "OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

//        $url1 = 'https://auth.robokassa.ru/Merchant/Index.aspx?isTest=1&MerchantLogin=auctiontest1&InvId=1659075480&OutSum=100.00&SignatureValue=8b7409273b4e9958c76c8857110bb12b&Culture=ru';
        return redirect($url);

    }
    public function resultUrl(Request $request){
        dd($request);
    }

    public function robokassaSuccess(Request $request){
        dd($request);
    }

    public function robokassaFailed(Request $request){
        dd($request);
    }
}
