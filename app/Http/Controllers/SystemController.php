<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Roles;
use App\Models\UserInterfaceTranslate;
use Cookie;
use Auth;
use Mail;
use Session;
use Redis;
use Lang;
use Response;
use Route;
use Validator;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function getRules(Request $request)
    {
        if ($request->ajax()) {
            $forms = $request->input('forms');
            $rules = [];

            foreach ($forms as $form) {
                $modelName = str_replace('DM_', '', $form);

                switch ($modelName) {
                    case 'registration':
                        $rules[$form] = [
                            'email' => 'required|email|unique:users',
                            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}$/|min:8',
                            'name' => 'required|min:4|unique:users',
                            'toa' => 'required',
                            'pda' => 'required'
                            
                        ];
                        break;
                    case 'login':
                        $rules[$form] = [
                            'email' => 'required|email',
                            'password' => 'required',                            
                        ];
                        break;
                    case 'profile':
                        $rules[$form] = [
                            'name' => 'required|max:16'
                        ];
                        break;
                    case 'profile_data':
                        $rules[$form] = [
                            'firstname' => 'alpha|max:40',
                            'lastname' => 'alpha|max:60',
                            'gender' => 'in:male,female',
                            'age' => 'date_format:d/m/Y'
                        ];
                        break;
                    case 'password_change':
                        $rules[$form] = [
                            'oldPassword' => 'required|password_check',
                            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}$/|min:8|confirmed'
                        ];
                        break;
                    case 'names':
                        $rules[$form] = [
                            'name' => 'required|unique:users,name|unique:names,name',
                        ];
                        break;
                    case 'categories':
                        $rules[$form] = [
                            'name' => 'required|unique:categories,name',
                        ];
                        break;
                    case 'templates':
                        $rules[$form] = [
                            'name' => 'required',
                            'description' => 'required'
                        ];
                        break;

                    default:
                        $rules[$form] = [];
                        break;
                }
            }

            if (!empty($rules)) {
                return Response::json([
                    'status' => 'success',
                    'rules' => $rules,
                    'messages' => Lang::get('validation')
                ]);
            } else {
                return Response::json(['status' => 'error',]);
            }
        }
    }

    public function validateFiled(Request $request)
    {
        $name = $request->input('name');
        $value = $request->input('value');
        $option = $request->input('option');

        $data[$name] = $value;
        $rule[$name] = $option;

        $validate = Validator::make($data, $rule);

        return Response::json(['status' => 'success', 'validation' => $validate->passes()]);
    }

    public function getRoutes(Request $request)
    {
        $routes = Route::getRoutes()->getRoutes();
        $data = [];

        foreach ($routes as $route) {
            if(strpos($route->getPath(), '_debugbar') === false) {
                $data[] = ['url' => $route->getPath(), 'as' => $route->getName()];
            }
        }

        if ((!Session::has('node') || !Session::has('device')) && Auth::check()) {
            Session::put('node', Auth::user()->node_token);
            Session::put('device', md5(date('Y-m-d H:i:s') . uniqid()));
        }
        
        return Response::json([
            'status' => 'success',
            'routes' => $data,
            'cookies' => [
                'node' => Session::get('node'),
                'device' => Session::get('device')
            ],
            'auth' => Auth::check(),
            'user' => Auth::check() ? [
                'name' => Auth::user()->name
            ] : []
        ]);
    }

    public function getUser()
    {
        $count = (Redis::get('count') ? Redis::get('count') : 0) + 1;
        Redis::set('count', $count);
        return Response::json(['status' => 'success', 'users' => (new User())->getUsers()]);
    }

    public function getTranslation()
    {
        $data = [];
        $data = array_merge($data, Lang::get('main'));
        $data = array_merge($data, Lang::get('homeIndex'));
        $data = array_merge($data, Lang::get('adminUsers'));
        $data = array_merge($data, Lang::get('adminNames'));
        $data = array_merge($data, Lang::get('adminCategories'));
        $data = array_merge($data, Lang::get('adminTemplates'));
        $data = array_merge($data, Lang::get('adminProducts'));
        $data = array_merge($data, Lang::get('profile'));
        $data = array_merge($data, Lang::get('validation'));

        return Response::json(['status' => 'success', 'translation' => $data]);
    }

    public function setRedirectUrl(Request $request)
    {
        Session::put('redirectUrl', $request->input('url'));

        return response()->json(['status' => 'success']);
    }

    public function registerAuction(Request $request)
    {
        $product = Product::where('mongo_id', $request->input('product_id'))->first();
        
        if (empty(Auth::user()->products()->where('product_id', $request->input('product_id'))->first())) {
            Auth::user()->products()->attach($product->id);

            $status = Mail::send('email.registerAuction', ['product' => $product], function($message) {
                $message->from('info@auction.dev', 'Auction');
                $message->to(Auth::user()->email);
            });

            return Response::json(['status' => $status ? 'success' : 'error']);
        }
    }
}
