<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Enums\UserRole;
use App\Models\FootballPitch;
use App\Models\FootballPitchDetail;
use App\Models\Order;
use App\Models\PitchType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //Trang chu
    public function dashboard(Request $request)
    {
        $title = 'Dashboard';

        $order = [];

        $order['sale'] = [
            'filter' => $request->get('order_filter', 'today'),
            'value' => 0,
            'percent' => 0,
            'grow' => 'increase',
            'date' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ],
            'dateCompare' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ]
        ];
        $order['revenue'] = [
            'filter' => $request->get('order_revenue_filter', 'this_month'),
            'value' => 0,
            'percent' => 0,
            'grow' => 'increase',
            'date' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ],
            'dateCompare' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ]
        ];
        $order['new_customer'] = [
            'filter' => $request->get('new_customer_filter', 'this_month'),
            'value' => 0,
            'percent' => 0,
            'grow' => 'increase',
            'date' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ],
            'dateCompare' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ]
        ];
        $order['top_order'] = [
            'filter' => $request->get('top_order_filter', 'this_month'),
            'value' => [],
            'date' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ],
            'dateCompare' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ]
        ];
        $order['top_customer'] = [
            'filter' => 'this_month',
            'value' => [],
            'date' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ],
            'dateCompare' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ]
        ];
        $order['order_wait_today'] = [
            'filter' => 'today',
            'value' => 0,
            'date' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ],
            'dateCompare' => [
                'start' => new Carbon(),
                'end' => new Carbon(),
            ]
        ];

        $order['sale'] = setDateFilter($order['sale']);
        $order['revenue'] = setDateFilter($order['revenue']);
        $order['new_customer'] = setDateFilter($order['new_customer']);
        $order['top_order'] = setDateFilter($order['top_order']);
        $order['top_customer'] = setDateFilter($order['top_customer']);
        $order['order_wait_today'] = setDateFilter($order['order_wait_today']);

        //sale
        $orderFilters = Order::query()
            ->where('status', '>', OrderStatusEnum::Wait)
            ->whereBetween('updated_at', [$order['sale']['date']['start'], $order['sale']['date']['end']])
            ->get();
        $orderFilterCompares = Order::query()
            ->where('status', '>', OrderStatusEnum::Wait)
            ->whereBetween('updated_at', [$order['sale']['dateCompare']['start'], $order['sale']['dateCompare']['end']])
            ->get();

        $ordersCount = ($orderFilters->count() == 0) ? 1 : $orderFilters->count();
        $orderCompareCount = ($orderFilterCompares->count() == 0) ? 1 : $orderFilterCompares->count();
        $perCent = 0;

        if ($orderCompareCount > $ordersCount) {
            $order['sale']['grow'] = 'decrease';
            $perCent = ($orderCompareCount / $ordersCount) * 100;
        } else {
            $perCent = ($ordersCount / $orderCompareCount) * 100;
        }

        $perCent = (int)$perCent;
        $order['sale']['percent'] = $perCent;
        $order['sale']['value'] = $orderFilters->count();
        //
        //revenue
        $orderRevenueFilters = Order::query()
            ->where('status', '>', OrderStatusEnum::Wait)
            ->whereBetween('updated_at', [$order['revenue']['date']['start'], $order['revenue']['date']['end']])
            ->get();
        $orderRevenueFilterCompares = Order::query()
            ->where('status', '>', OrderStatusEnum::Wait)
            ->whereBetween('updated_at', [$order['revenue']['dateCompare']['start'], $order['revenue']['dateCompare']['end']])
            ->get();
        //dd($order['revenue']);

        $ordersCount = ($orderRevenueFilters->sum('total') == 0) ? 1 : $orderRevenueFilters->sum('total');
        $orderCompareCount = ($orderRevenueFilterCompares->sum('total') == 0) ? 1 : $orderRevenueFilterCompares->sum('total');
        $perCent = 0;

        if ($orderCompareCount > $ordersCount) {
            $order['revenue']['grow'] = 'decrease';
            $perCent = ($orderCompareCount / $ordersCount) * 100;
        } else {
            $perCent = ($ordersCount / $orderCompareCount) * 100;
        }

        $perCent = (int)$perCent;
        $order['revenue']['percent'] = $perCent;
        $order['revenue']['value'] = printMoney($orderRevenueFilters->sum('total'));
        //
        //new customer
        $newCustomerFilters = User::query()
            ->where('role', UserRole::Client)
            ->whereBetween('created_at', [$order['new_customer']['date']['start'], $order['new_customer']['date']['end']])
            ->get();
        $newCustomerFilterCompares = User::query()
            ->where('role', UserRole::Client)
            ->whereBetween('created_at', [$order['new_customer']['dateCompare']['start'], $order['new_customer']['dateCompare']['end']])
            ->get();

        $newCustomerCount = $newCustomerFilters->count();
        $newCustomerCpmpareCount = $newCustomerFilterCompares->count();

        if ($newCustomerCpmpareCount > $newCustomerCount) {
            $order['new_customer']['grow'] = 'decrease';
            if ($newCustomerCount != 0) {
                $perCent = ($newCustomerCpmpareCount / $newCustomerCount) * 100;
            }
            else{
                $perCent = $newCustomerCpmpareCount * 100;
            }
        } else if ($newCustomerCount > $newCustomerCpmpareCount) {
            if ($newCustomerCpmpareCount != 0) {
                $perCent = ($newCustomerCount / $newCustomerCpmpareCount) * 100;
            }
            else {
                $perCent = $newCustomerCount * 100;
            }
        }
        else {
            $perCent = 0;
        }

        $perCent = (int)$perCent;
        $order['new_customer']['percent'] = $perCent;
        $order['new_customer']['value'] = $newCustomerFilters->count();
        //
        //top order
        $topOrderFilters = Order::query()
            ->where('status', '>', OrderStatusEnum::Wait)
            ->whereBetween('updated_at', [$order['top_order']['date']['start'], $order['top_order']['date']['end']])
            ->groupBy('football_pitch_id')
            ->with('footballPitch')
            ->selectRaw('football_pitch_id, count(football_pitch_id) as "count"')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        $order['top_order']['value'] = $topOrderFilters;
        //
        $orderRecentUpdated = Order::query()->orderByDesc('updated_at')->limit(5)->get();
        //top customer month
        $topCustomerFilters = Order::query()
            ->where('status', '>', OrderStatusEnum::Wait)
            ->whereBetween('updated_at', [$order['top_customer']['date']['start'], $order['top_customer']['date']['end']])
            ->groupBy('user_id')
            ->selectRaw('user_id, sum(total) as "total"')
            ->orderBy('total', 'desc')
            ->with('user')
            ->limit(5)
            ->get();
        $topCustomerFilterCompares = Order::query()
            ->where('status', '>', OrderStatusEnum::Wait)
            ->whereBetween('updated_at', [$order['top_customer']['dateCompare']['start'], $order['top_customer']['dateCompare']['end']])
            ->groupBy('user_id')
            ->selectRaw('user_id, sum(total) as "total"')
            ->orderBy('total', 'desc')
            ->with('user')
            ->limit(5)
            ->get();

        $arr = [];
        foreach ($topCustomerFilters as $item) {
            $newArr = [];

            $last_total = $topCustomerFilterCompares->where('user_id', $item->user_id)->first()->total ?? 0;

            if ($last_total > $item->total) {
                $newArr['grow'] = 'decrease';

                if ($item->total == 0) {
                    $newArr['percent'] = $last_total * 100;
                }
                else {
                    $newArr['percent'] = ($last_total / $item->total) * 100;
                }
            }
            else if ($last_total < $item->total) {

                if ($last_total == 0) {
                    $newArr['percent'] = $item->total * 100;
                }
                else {
                    $newArr['percent'] = ($item->total / $last_total) * 100;
                }
            }
            else {
                $newArr['percent'] = 0;
            }

            $newArr['total'] = $item->total();
            $newArr['percent'] = (int)$newArr['percent'];
            $newArr['grow'] = 'increase';
            $newArr['user'] = $item->user;
            $arr[] = $newArr;
        }
        //
        $orderWaitToday = Order::query()
            ->where('status', '=', OrderStatusEnum::Wait)
            ->whereBetween('updated_at', [$order['order_wait_today']['date']['start'], $order['order_wait_today']['date']['end']])
            ->get();
        $order['order_wait_today']['value'] = $orderWaitToday->count();

        return view('admin.dashboard.index', [
            'title' => $title,
            'order' => $order,
            'orderRecentUpdated' => $orderRecentUpdated,
            'topCustomers' => $arr,
        ]);
    }
    //The loai san bong
    public function pitchType()
    {
        $title = 'Pitch Type';
        $pitchTypes = PitchType::query()->orderByDesc('id')->get();
        return view('admin.pitch_type.index', [
            'title' => $title,
            'pitchTypes' => $pitchTypes,
        ]);
    }
    //San bong
    public function footballPitch()
    {
        $title = 'Football Pitch';
        $footballPitches = FootballPitch::query()->orderByDesc('id')->with('pitchType')->get();
        $pitchTypes = PitchType::all();
        return view('admin.football_pitch.index', [
            'title' => $title,
            'footballPitches' => $footballPitches,
            'pitchTypes' => $pitchTypes,
        ]);
    }
    //Chi tiet san bong
    public function footballPitchDetail(string $id)
    {
        $title = 'Football Pitch Detail';
        $footballPitchDetails = FootballPitchDetail::query()->where('football_pitch_id', '=', $id)->get();
        $footballPitch = FootballPitch::query()->with('pitchType')
            ->with('toFootballPitch')
            ->with('fromFootballPitch')->find($id);
        $pitchTypes = PitchType::all();
        $footballPitches = FootballPitch::query()->orderByDesc('id')->get([
            'id',
            'name',
            'to_football_pitch_id',
            'from_football_pitch_id',
        ]);
        return view('admin.football_pitch.detail', [
            'title' => $title,
            'footballPitchDetails' => $footballPitchDetails,
            'footballPitch' => $footballPitch,
            'pitchTypes' => $pitchTypes,
            'footballPitches' => $footballPitches,
        ]);
    }
    //yeu cau lich
    public function orderCalendar()
    {
        $title = 'Order';
        $footballPitches = FootballPitch::query()->where('is_maintenance', 0)->with('pitchType')->get([
            'id',
            'name',
            'pitch_type_id',
        ]);
        return view('admin.order.calendar', [
            'title' => $title,
            'footballPitches' => $footballPitches,
        ]);
    }
    //yeu cau bang
    public function orderTable()
    {
        $title = 'Order';
        return view('admin.order.table', [
            'title' => $title,
        ]);
    }
    //thanh toan
    public function checkout(string $id)
    {
        $order = Order::query()->with('footballPitch')->find($id);
        if (!$order) {
            abort(404);
        }
        $arr = [];
        $isCheckout = ($order->status == OrderStatusEnum::Paid) ? true : false;
        if ($isCheckout) {
            $arr['status'] = 'success';
            $arr['message'] = 'Đã thanh toán';
        } else {
            $arr['status'] = 'danger';
            $arr['message'] = 'Chưa thanh toán';
        }
        //dd($order->status);
        $title = "Checkout";
        return view('admin.order.checkout', [
            'title' => $title,
            'order' => $order,
            'arr' => $arr,
            'isCheckout' => $isCheckout,
        ]);
    }
    //thong tin ngan hang
    public function bankInformation()
    {
        $title = 'Bank information';
        return view('admin.bank_info.index', [
            'title' => $title,
        ]);
    }
    //nhan vien
    public function employe()
    {
        $title = 'Employe';
        return view('admin.employe.index', [
            'title' => $title,
        ]);
    }
}
