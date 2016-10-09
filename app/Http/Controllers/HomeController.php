<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Mongo\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use MongoDB\BSON\ObjectID;

use App\Http\Requests\RegistrationRequest;
use Hash;
use Illuminate\Support\Facades\DB;
use Session;
use Mail;
use Response;
use View;
use Auth;

class HomeController extends Controller
{
    public function getIndex()
    {
        return view('home.index', ['products' => Product::with('template', 'category')->get()->take(5)]);
    }

    public function getRegistration()
    {
        $user = new User(); //

        return Response::json([
            'status' => 'success',
            'html' => preg_replace("/\\r\\n|\\n/", "", View::make('home.registration', ['user' => $user])->render())
        ]);
    }

    public function getLogin()
    {
        $user = new User();

        return Response::json([
            'status' => 'success',
            'html' => preg_replace("/\\r\\n|\\n/", "", View::make('home.login', ['user' => $user])->render())
        ]);
    }

    public function postRegistration(RegistrationRequest $request)
    {
        $token = md5($request->input('name') . time());
        $href = url('cr', ['token' => $token]);
        $model = new User();

        $model->name = $request->input('name');
        $model->email = $request->input('email');
        $model->password = bcrypt($request->input('password'));
        $model->token = $token;

        $status = $model->save() && Mail::send('email.confirmation', ['user' => $model, 'url' => $href], function($message) use ($model) {
            $message->from('info@auction.dev', 'Auction');
            $message->to($model->email);
        });

        return Response::json(['status' => $status ? 'success' : 'error']);
    }

    public function confirmation($token = null){
        if(isset($token)) {
            $user = User::where('token', '=', $token)->firstOrFail();
            $user->token = '';
            $user->save();

            Auth::loginUsingId($user->id);

            if(Auth::check()) {
                return redirect('/');
            }
        }
    }

    public function getVKLogin(Request $request){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth.vk.com/access_token?client_id=5407110&client_secret='.env('VK_CLIENT_SECRET').'&redirect_uri='.url('/verify').'&code='.$request->code);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        if(curl_errno($ch) == '') {
            $response = json_decode($response, true);
            Session::put('access_token', $response['access_token']);

            $user = User::where('vk_id', '=', $response['user_id'])->first();

            if(count($user)) {
                Auth::loginUsingId($user->id);
            } else {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/users.get?fields=nickname&v=5.50&user_id='.$response['user_id']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $userInfo = json_decode(curl_exec($ch), true)['response'][0];

                $model = new User();
                $model->name = $userInfo['nickname'] != '' ? $userInfo['nickname'] : 'id'.$response['user_id'];
                $model->email = $response['email'];
                $model->password = Hash::make('123456');
                $model->vk_id = $response['user_id'];
                $model->save();

                Auth::loginUsingId($model->id);
            }

            if ($request->has('state') && $request->input('state') == 'profile') {
                return redirect('/profile/index');
            } elseif (Auth::check()) {
                Auth::user()->addNodeUser();
                return redirect(Session::pull('redirectUrl', '/'));
            }
        }
    }
    
    public function postLogin(Request $request)
    {
        $user = User::where('email', '=', $request->input('email'))->first();

        if($user->token != '') {
            return Response::json(['status' => 'verify']);
        } else {
            return Response::json([
                'status' => Auth::attempt([
                    'email' => $request->input('email'),
                    'password' => $request->input('password')
                ], $request->input('remember')) && Auth::user()->addNodeUser() ? 'success' : 'error',
                'url' => Session::pull('redirectUrl', '/')
            ]);
        }
    }

    public function logout()
    {
        Auth::user()->deleteNodeUser();
        Auth::logout();

        return redirect('/');
    }

    public function auction()
    {
        $products = Product::where('source', 'common')
                           ->orderBy('start_date', 'desc')
                           ->orderBy('status')
                           ->paginate(10);
        
        $filters = [
            'time' => [
                [
                    'id' => 1,
                    'name' => trans('homeIndex.soon_start'),
                    'checked' => 1
                ],
                [
                    'id' => 2,
                    'name' => trans('homeIndex.active'),
                    'checked' => 1
                    
                ],
                [
                    'id' => 3,
                    'name' => trans('homeIndex.finished'),
                    'checked' => 1
                ]
            ],
            'category' => Category::select('id', 'name', DB::raw('1 AS checked'))->get(),
            'type' => [
                [
                    'id' => 'beginning',
                    'name' => trans('adminProducts.products_type_beginning'),
                    'checked' => 1
                ],
                [
                    'id' => 'ticket',
                    'name' => trans('adminProducts.products_type_ticket'),
                    'checked' => 1
                ],
                [
                    'id' => 'common',
                    'name' => trans('adminProducts.products_type_common'),
                    'checked' => 1
                ]
            ]
        ];

        $closed = Product::where('source', 'common')
                         ->where('status', 3)
                         ->orderBy('start_date', 'desc')
                         ->paginate(5);
        
        return view(
            'home.auction',
            [
                'products' => $products,
                'closed' => $closed,
                'filters' => json_encode($filters)
            ]
        );
    }

    public function gameZone()
    {
        $products = Product::where('source', 'common')
                           ->orderBy('start_date', 'desc')
                           ->orderBy('status')
                           ->paginate(10);
        
        $filters = [
            'time' => [
                [
                    'id' => 1,
                    'name' => trans('homeIndex.soon_start'),
                    'checked' => 1
                ],
                [
                    'id' => 2,
                    'name' => trans('homeIndex.active'),
                    'checked' => 1
                    
                ],
                [
                    'id' => 3,
                    'name' => trans('homeIndex.finished'),
                    'checked' => 1
                ]
            ],
            'category' => Category::select('id', 'name', DB::raw('1 AS checked'))->get(),
            'type' => [
                [
                    'id' => 'beginning',
                    'name' => trans('adminProducts.products_type_beginning'),
                    'checked' => 1
                ],
                [
                    'id' => 'ticket',
                    'name' => trans('adminProducts.products_type_ticket'),
                    'checked' => 1
                ],
                [
                    'id' => 'common',
                    'name' => trans('adminProducts.products_type_common'),
                    'checked' => 1
                ]
            ]
        ];

        $closed = Product::where('source', 'common')
                         ->where('status', 3)
                         ->orderBy('start_date', 'desc')
                         ->paginate(5);
        
        return view(
            'home.gameZone',
            [
                'products' => $products,
                'closed' => $closed,
                'filters' => json_encode($filters)
            ]
        );
    }

    public function search(Request $request)
    {
        $status = [];
        $categories = [];
        $types = [];

        foreach ($request->input('filters')['time'] as $time) {
            if ($time['checked'] != 'false') {
                $status[] = $time['id'];
            }
        }

        foreach ($request->input('filters')['category'] as $category) {
            if ($category['checked'] != 'false') {
                $categories[] = $category['id'];
            }
        }

        foreach ($request->input('filters')['type'] as $type) {
            if ($type['checked'] != 'false') {
                $types[] = $type['id'];
            }
        }

        $products = Product::where('source', $request->input('type'))
                           ->whereIn('status', $status)
                           ->whereIn('category_id', $categories)
                           ->whereIn('type', $types)
                           ->paginate(10);
        $views = [];

        foreach ($products as $product){
            $views[] = preg_replace("/\\r\\n|\\n/", "", View::make('home.goodItem', ['product' => $product])->render());
        }

        return response()->json([
            'status' => empty($products) ? 'error' : 'success',
            'view' => implode('', $views),
            'total' => $products->total()
        ]);
    }
    
    public function myAuction ()
    {
        $recommend = Product::whereIn('status', [1, 2])
                            ->orderByRaw('RAND()')
                            ->first();

        $productIds = [
            'string' => [],
            'object_id' => []
        ];
        $bids = [];

        Bid::where('user_id', Auth::Id())
            ->groupBy('product_id')
            ->get()
            ->each(function($bid) use (&$productIds) {
                $productIds['object_id'][] = new ObjectID($bid->product_id);
                $productIds['string'][] = (string) $bid->product_id;
            });

        Bid::raw(function($collection) use ($productIds) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'product_id' => [
                            '$in' => $productIds['object_id']
                        ]
                    ]
                ],
                [
                    '$sort' => [
                        'date' => -1
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$product_id',
                        'date' => [
                            '$max' => '$date'
                        ],
                        'type' => [
                            '$first' => '$type'
                        ],
                        'user_id' => [
                            '$first' => '$user_id'
                        ],
                        'name' => [
                            '$first' => '$name'
                        ],
                        'price' => [
                            '$first' => '$price'
                        ]
                    ]
                ]
            ]);
        })
        ->each(function($bid) use (&$bids) {
            $bids[(string)$bid->_id] = $bid->toArray();
        });

        $products = Product::whereIn('mongo_id', $productIds['string'])->get();

        return view(
            'home.myAuction', 
            [
                'recommend' => $recommend,
                'bids' => $bids,
                'products' => $products
            ]
        );
    }
}
