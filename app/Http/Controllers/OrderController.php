<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Http\Requests\OrderClientStoreRequest;
use App\Jobs\ClientStoreFootballPitchJob;
use App\Jobs\SendMailWhenClientStoreFootballPitchJob;
use App\Jobs\SendMailWhenUpdateStatusOrderJob;
use App\Models\FootballPitch;
use App\Models\Order;
use App\Models\PeakHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class OrderController extends Controller
{
    public function showAll(Request $request)
    {
        $arrInTime = [
            getDateTimeLaravel($request->start),
            getDateTimeLaravel($request->end),
        ];
        $orders = Order::query()
            ->with('footballPitch')
            ->where('status', '<>', OrderStatusEnum::Cancel)
            ->whereBetween('start_at', $arrInTime)->get(
                [
                    'id',
                    'football_pitch_id',
                    'name',
                    'end_at',
                    'start_at',
                    'status',
                ]
            );
        $arr = [];
        $bg_color = [
            'wait' => '',
            'finish' => '#198754',
            'cancel' => '#dc3545',
            'running' => '#8fdf82'
        ];
        foreach ($orders as $order) {
            $color = '';
            $color = match ($order->status) {
                OrderStatusEnum::Wait => $bg_color['wait'],
                OrderStatusEnum::Finish => $bg_color['running'],
                OrderStatusEnum::Cancel => $bg_color['cancel'],
                OrderStatusEnum::Paid => $bg_color['finish'],
                default => $color,
            };
            $arr[] = [
                'id' => $order->id,
                'title' => $order->footballPitch->name . ' : ' . $order->name,
                'start' => $order->start_at,
                'end' => $order->end_at,
                'backgroundColor' => $color,
                'extendedProps' => [
                    'football_pitch_id' => $order->footballPitch->id,
                ]
            ];
        }
        return response()->json($arr);
    }
    public function index()
    {
        $order = Order::query()->with('footballPitch')->get();
        return response()->json([
            'data' => $order,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //kiểm tra đầu vào
        $validated = $request->validate([
            'start_at' => 'required',
            'end_at' => 'required',
            'football_pitch_id' => 'required|exists:football_pitches,id',
        ]);
        $football_pitch = FootballPitch::find($validated['football_pitch_id']);
        if ($football_pitch->is_maintenance) {
            return response()->json([
                'message' => 'Sân bóng đang bảo trì',
                'status' => 'error'
            ], Response::HTTP_BAD_REQUEST);
        }
        //lấy thời gian bắt đầu và kết thúc của ngày hôm nay
        $end_of_day = (new Carbon(getDateLaravel($validated['start_at'])))->endOfDay();
        $start_of_day = (new Carbon(getDateLaravel($validated['end_at'])))->startOfDay();
        $arrInTime = [
            getDateTimeLaravel($start_of_day),
            getDateTimeLaravel($end_of_day),
        ];
        //tìm những sân đang được đặt trong hôm nay
        $orders = Order::query()
            ->where('status', '<>', OrderStatusEnum::Cancel)
            ->where('football_pitch_id', $validated['football_pitch_id'])
            ->whereBetween('start_at', $arrInTime)->get(['start_at', 'end_at']);
        //nếu sân yêu cầu đã được đặt trong thời gian đó rồi thì trả về lỗi
        foreach ($orders as $item) {
            if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                return response()->json([
                    'message' => 'Thời gian đã tồn tại trong hệ thống',
                    'status' => 'error'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        //tìm xem sân đang yêu cầu có đang liên kết với sân nào không, có thì trả về lỗi
        if ($football_pitch->from_football_pitch_id && $football_pitch->to_football_pitch_id) {
            $order_with_football_pitch_links = Order::query()
                ->where('status', '<>', OrderStatusEnum::Cancel)
                ->where('football_pitch_id', $football_pitch->from_football_pitch_id)
                ->orWhere('football_pitch_id', $football_pitch->to_football_pitch_id)
                ->whereBetween('start_at', $arrInTime)->get(['start_at', 'end_at']);
            foreach ($order_with_football_pitch_links as $item) {
                if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                    return response()->json([
                        'message' => 'Sân liên kết đang trong thời gian hoạt động',
                        'status' => 'error'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $orders = Order::query()
                ->join('football_pitches', 'football_pitches.id', '=', 'orders.football_pitch_id')
                ->where('football_pitches.from_football_pitch_id', '=', $validated['football_pitch_id'])
                ->orWhere('football_pitches.to_football_pitch_id', '=', $validated['football_pitch_id'])
                ->get([
                    'start_at', 'end_at'
                ]);
            foreach ($orders as $item) {
                if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                    return response()->json([
                        'message' => 'Sân liên kết đang trong thời gian hoạt động',
                        'status' => 'error'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        }
        //kiểm tra xem thời gian đó có phải lúc sân đang mở không
        $time_start = getTimeLaravel($validated['start_at']);
        $time_end = getTimeLaravel($validated['end_at']);
        //dd($time_start, $time_end);
        if (!isOrderInTime(
            $time_start,
            $time_end,
            $football_pitch->time_start,
            $football_pitch->time_end
        )) {
            return response()->json([
                'message' => 'Thời gian bạn đặt sân chưa mở',
                'status' => 'error'
            ], Response::HTTP_BAD_REQUEST);
        }
        //nếu không có lỗi gì thì thêm yêu cầu mới vào
        $peak_hour = PeakHour::all()->firstOrFail();
        $total_price = getPriceOrder([
            'time_start' => $peak_hour->start_at,
            'time_end' => $peak_hour->end_at,
        ], [
            'start_at' => $validated['start_at'],
            'end_at' => $validated['end_at'],
        ], $football_pitch->price_per_hour, $football_pitch->price_per_peak_hour);
        //dd($total_price);
        $obj = Order::create([
            'start_at' => getDateTimeLaravel($validated['start_at']),
            'end_at' => getDateTimeLaravel($validated['end_at']),
            'user_id' => auth()->user()->id,
            'football_pitch_id' => $validated['football_pitch_id'],
            'total' => $total_price,
            'deposit' => $total_price * 0.4,
            'code' => strtoupper(Str::random(10)),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Yêu cầu đã được tạo hoàn tất',
            'data' => $obj,
        ], Response::HTTP_CREATED);
    }

    public function clientStore(OrderClientStoreRequest $request)
    {
        //kiểm tra đầu vào
        $validated = $request->validated();
        $football_pitch = FootballPitch::find($validated['football_pitch_id']);
        if ($football_pitch->is_maintenance) {
            return response()->json([
                'message' => 'Sân bóng đang bảo trì',
                'status' => 'error'
            ], Response::HTTP_BAD_REQUEST);
        }
        $nearStartAt = new Carbon($validated['start_at']);
        $nearEndAt = new Carbon($validated['end_at']);
        if ($nearEndAt <= $nearStartAt) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng chọn thời gian kết thúc lớn hơn thời gian bắt đầu',
            ], Response::HTTP_BAD_REQUEST);
        }
        //lấy thời gian bắt đầu và kết thúc của ngày hôm nay
        $end_of_day = (new Carbon(getDateLaravel($validated['start_at'])))->endOfDay();
        $start_of_day = (new Carbon(getDateLaravel($validated['end_at'])))->startOfDay();
        $arrInTime = [
            getDateTimeLaravel($start_of_day),
            getDateTimeLaravel($end_of_day),
        ];
        //tìm những sân đang được đặt trong hôm nay
        $orders = Order::query()
            ->where('status', '<>', OrderStatusEnum::Cancel)
            ->where('football_pitch_id', $validated['football_pitch_id'])
            ->whereBetween('start_at', $arrInTime)->get(['start_at', 'end_at']);
        //nếu sân yêu cầu đã được đặt trong thời gian đó rồi thì trả về lỗi
        foreach ($orders as $item) {
            if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                return response()->json([
                    'message' => 'Thời gian này đã có người đặt rồi',
                    'status' => 'error'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        //tìm xem sân đang yêu cầu có đang liên kết với sân nào không, có thì trả về lỗi
        if ($football_pitch->from_football_pitch_id && $football_pitch->to_football_pitch_id) {
            $order_with_football_pitch_links = Order::query()
                ->where('status', '<>', OrderStatusEnum::Cancel)
                ->where('football_pitch_id', $football_pitch->from_football_pitch_id)
                ->orWhere('football_pitch_id', $football_pitch->to_football_pitch_id)
                ->whereBetween('start_at', $arrInTime)->get(['start_at', 'end_at']);
            foreach ($order_with_football_pitch_links as $item) {
                if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                    return response()->json([
                        'message' => 'Sân liên kết đang trong thời gian hoạt động',
                        'status' => 'error'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $orders = Order::query()
                ->where('status', '<>', OrderStatusEnum::Cancel)
                ->join('football_pitches', 'football_pitches.id', '=', 'orders.football_pitch_id')
                ->where('football_pitches.from_football_pitch_id', '=', $validated['football_pitch_id'])
                ->orWhere('football_pitches.to_football_pitch_id', '=', $validated['football_pitch_id'])
                ->get([
                    'start_at', 'end_at'
                ]);
            foreach ($orders as $item) {
                if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                    return response()->json([
                        'message' => 'Sân liên kết đang trong thời gian hoạt động',
                        'status' => 'error'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        }
        //kiểm tra xem thời gian đó có phải lúc sân đang mở không
        $time_start = getTimeLaravel($validated['start_at']);
        $time_end = getTimeLaravel($validated['end_at']);
        //dd($time_start, $time_end);
        if (!isOrderInTime(
            $time_start,
            $time_end,
            $football_pitch->time_start,
            $football_pitch->time_end
        )) {
            return response()->json([
                'message' => 'Thời gian bạn đặt sân đang đóng',
                'status' => 'error'
            ], Response::HTTP_BAD_REQUEST);
        }
        //nếu không có lỗi gì thì thêm yêu cầu mới vào
        $peak_hour = PeakHour::all()->firstOrFail();
        $total_price = getPriceOrder([
            'time_start' => $peak_hour->start_at,
            'time_end' => $peak_hour->end_at,
        ], [
            'start_at' => $validated['start_at'],
            'end_at' => $validated['end_at'],
        ], $football_pitch->price_per_hour, $football_pitch->price_per_peak_hour);
        //dd($total_price);
        $arr = [
            'start_at' => getDateTimeLaravel($validated['start_at']),
            'end_at' => getDateTimeLaravel($validated['end_at']),
            'football_pitch_id' => $validated['football_pitch_id'],
            'total' => $total_price,
            'deposit' => $total_price * 0.4,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'code' => strtoupper(Str::random(10)),
        ];
        if (Auth::check()) {
            $arr['by_user_id'] = Auth::user()->id;
        }
        $obj = Order::create($arr);
        dispatch(new ClientStoreFootballPitchJob($obj));
        dispatch(new SendMailWhenClientStoreFootballPitchJob($obj));
        return response()->json([
            'status' => 'success',
            'message' => 'Sân bóng đã được đặt thành công, chuyển hướng sau 3 giây',
            'redirect' => route('client.checkout', $obj->id),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::query()->find($id);
        if ($order) {
            switch ($request->get('type')) {
                case 'update_time': //update khi keo order (time)
                    $validated = $request->validate([
                        'start_at' => 'nullable',
                        'end_at' => 'nullable',
                    ]);
                    //lấy thời gian bắt đầu và kết thúc của ngày hôm nay
                    $end_of_day = (new Carbon(getDateLaravel($validated['start_at'])))->endOfDay();
                    $start_of_day = (new Carbon(getDateLaravel($validated['end_at'])))->startOfDay();
                    $arrInTime = [
                        getDateTimeLaravel($start_of_day),
                        getDateTimeLaravel($end_of_day),
                    ];
                    //tìm những sân đang được đặt trong hôm nay
                    $orders = Order::query()
                        ->where('status', '<>', OrderStatusEnum::Cancel)
                        ->where('football_pitch_id', $order->football_pitch_id)
                        ->where('id', '!=', $order->id)
                        ->whereBetween('start_at', $arrInTime)->get(['start_at', 'end_at']);
                    //nếu sân yêu cầu đã được đặt trong thời gian đó rồi thì trả về lỗi
                    foreach ($orders as $item) {
                        if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                            return response()->json([
                                'message' => 'Thời gian đã tồn tại trong hệ thống',
                                'status' => 'error'
                            ], Response::HTTP_BAD_REQUEST);
                        }
                    }
                    //tìm xem sân đang yêu cầu có đang liên kết với sân nào không, có thì trả về lỗi
                    $football_pitch = FootballPitch::find($order->football_pitch_id);
                    if ($football_pitch->from_football_pitch_id && $football_pitch->to_football_pitch_id) {
                        $order_with_football_pitch_links = Order::query()->where('football_pitch_id', $football_pitch->from_football_pitch_id)
                            ->orWhere('football_pitch_id', $football_pitch->to_football_pitch_id)
                            ->where('id', '!=', $order->id)
                            ->whereBetween('start_at', $arrInTime)->get(['start_at', 'end_at']);
                        foreach ($order_with_football_pitch_links as $item) {
                            if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                                return response()->json([
                                    'message' => 'Sân liên kết đang trong thời gian hoạt động',
                                    'status' => 'error'
                                ], Response::HTTP_BAD_REQUEST);
                            }
                        }
                    } else {
                        $orders = Order::query()
                            ->where('status', '<>', OrderStatusEnum::Cancel)
                            ->join('football_pitches', 'football_pitches.id', '=', 'orders.football_pitch_id')
                            ->where('orders.id', '!=', $order->id)
                            ->where('football_pitches.from_football_pitch_id', '=', $order->football_pitch_id)
                            ->orWhere('football_pitches.to_football_pitch_id', '=', $order->football_pitch_id)
                            ->get([
                                'start_at', 'end_at'
                            ]);
                        foreach ($orders as $item) {
                            if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                                return response()->json([
                                    'message' => 'Sân liên kết đang trong thời gian hoạt động',
                                    'status' => 'error'
                                ], Response::HTTP_BAD_REQUEST);
                            }
                        }
                    }
                    //kiểm tra xem thời gian đó có phải lúc sân đang mở không
                    $time_start = explode(' ', getDateTimeLaravel($validated['start_at']))[1];
                    $time_end = explode(' ', getDateTimeLaravel($validated['end_at']))[1];
                    if (!isOrderInTime(
                        $time_start,
                        $time_end,
                        $football_pitch->time_start,
                        $football_pitch->time_end
                    )) {
                        return response()->json([
                            'message' => 'Thời gian bạn đặt sân chưa mở',
                            'status' => 'error'
                        ], Response::HTTP_BAD_REQUEST);
                    }

                    //cap nhat
                    $arr = [];
                    if ($request->has('start_at')) {
                        $arr['start_at'] = getDateTimeLaravel($validated['start_at']);
                    }
                    if ($request->has('end_at')) {
                        $arr['end_at'] = getDateTimeLaravel($validated['end_at']);
                    }
                    $order->update($arr);
                    $football_pitch = FootballPitch::find($order->football_pitch_id);
                    $peak_hour = PeakHour::all()->firstOrFail();
                    $total_price = getPriceOrder([
                        'time_start' => $peak_hour->start_at,
                        'time_end' => $peak_hour->end_at,
                    ], [
                        'start_at' => $order->start_at,
                        'end_at' => $order->end_at,
                    ], $football_pitch->price_per_hour, $football_pitch->price_per_peak_hour);
                    $order->update(['total' => $total_price]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Yêu cầu đã được cập nhật hoàn tất',
                    ]);
                    break;
                case 'update_info': //update khi click vao order
                    $validated = $request->validate([
                        'name' => 'required|string',
                        'phone' => 'required|numeric',
                        'email' => 'nullable|email',
                        'deposit' => 'required|numeric',
                        'note' => 'nullable|string',
                    ]);
                    $validated['status'] = OrderStatusEnum::Finish;
                    if (!$order->user_id) {
                        $validated['user_id'] = auth()->user()->id;
                    }
                    if ($order->status != OrderStatusEnum::Finish) {
                        dispatch(new SendMailWhenUpdateStatusOrderJob($order));
                    }
                    $order->update($validated);
                    $arr = [
                        'id' => $order->id,
                        'title' => $order->footballPitch->name . ' : ' . $order->name,
                        'start' => $order->start_at,
                        'end' => $order->end_at,
                        'extendedProps' => [
                            'football_pitch_id' => $order->footballPitch->id,
                        ]
                    ];
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Yêu cầu đã được cập nhật hoàn tất',
                        'data' => $arr
                    ]);
                    //return redirect()->back()->with('message', 'Yêu cầu đã được cập nhật hoàn tất');
                    break;
            }
        }
        return response()->json([
            'message' => 'Không thể tìm thấy yêu cầu'
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Yêu cầu đã được xóa'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Không thể tìm thấy yêu cầu',
        ], Response::HTTP_NOT_FOUND);
    }

    public function paid(string $id)
    {
        $obj = Order::find($id);
        $obj->status = OrderStatusEnum::Paid;
        $obj->save();
        dispatch(new SendMailWhenUpdateStatusOrderJob($obj));
        return redirect()->back()->with('message', 'Thanh toán thành công');
    }

    public function getOrderUnpaid()
    {
        $now = Carbon::now();
        $order = Order::query()
            ->where('start_at', '<=', getDateTimeLaravel($now))
            ->where('status', OrderStatusEnum::Finish)
            ->with('footballPitch')
            ->get();
        return response()->json([
            'data' => $order,
        ]);
    }

    public function check(Request $request)
    {
        //kiểm tra đầu vào
        $validated = $request->validate([
            'start_at' => 'required',
            'end_at' => 'required',
            'football_pitch_id' => 'required|exists:football_pitches,id',
        ]);
        $football_pitch = FootballPitch::find($validated['football_pitch_id']);
        if ($football_pitch->is_maintenance) {
            return response()->json([
                'message' => 'Sân bóng đang bảo trì',
                'status' => 'error'
            ], Response::HTTP_BAD_REQUEST);
        }
        $nearStartAt = new Carbon($validated['start_at']);
        $nearEndAt = new Carbon($validated['end_at']);
        if ($nearEndAt <= $nearStartAt) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng chọn thời gian kết thúc lớn hơn thời gian bắt đầu',
            ], Response::HTTP_BAD_REQUEST);
        }
        //lấy thời gian bắt đầu và kết thúc của ngày đặt sân
        $end_of_day = (new Carbon(getDateLaravel($validated['start_at'])))->endOfDay();
        $start_of_day = (new Carbon(getDateLaravel($validated['end_at'])))->startOfDay();
        $arrInTime = [
            getDateTimeLaravel($start_of_day),
            getDateTimeLaravel($end_of_day),
        ];
        //tìm những sân đang được đặt trong ngày đặt
        $orders = Order::query()
            ->where('status', '<>', OrderStatusEnum::Cancel)
            ->where('football_pitch_id', $validated['football_pitch_id'])
            ->whereBetween('start_at', $arrInTime)->get(['start_at', 'end_at']);
        //nếu sân yêu cầu đã được đặt trong thời gian đó rồi thì trả về lỗi
        foreach ($orders as $item) {
            if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                return response()->json([
                    'message' => 'Thời gian này đã có người đặt rồi',
                    'status' => 'error'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        //tìm xem sân đang yêu cầu có đang liên kết với sân nào không, có thì trả về lỗi
        if ($football_pitch->from_football_pitch_id && $football_pitch->to_football_pitch_id) {
            $order_with_football_pitch_links = Order::query()
                ->where('status', '<>', OrderStatusEnum::Cancel)
                ->where('football_pitch_id', $football_pitch->from_football_pitch_id)
                ->orWhere('football_pitch_id', $football_pitch->to_football_pitch_id)
                ->whereBetween('start_at', $arrInTime)->get(['start_at', 'end_at']);
            foreach ($order_with_football_pitch_links as $item) {
                if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                    return response()->json([
                        'message' => 'Sân bóng đang liên kết với sân này đã có người đặt rồi',
                        'status' => 'error'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $orders = Order::query()
                ->where('status', '<>', OrderStatusEnum::Cancel)
                ->join('football_pitches', 'football_pitches.id', '=', 'orders.football_pitch_id')
                ->where('football_pitches.from_football_pitch_id', '=', $validated['football_pitch_id'])
                ->orWhere('football_pitches.to_football_pitch_id', '=', $validated['football_pitch_id'])
                ->get([
                    'start_at', 'end_at'
                ]);
            foreach ($orders as $item) {
                if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at)) {
                    return response()->json([
                        'message' => 'Sân bóng đang liên kết với sân này đã có người đặt rồi',
                        'status' => 'error'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        }
        //kiểm tra xem thời gian đó có phải lúc sân đang mở không
        $time_start = getTimeLaravel($validated['start_at']);
        $time_end = getTimeLaravel($validated['end_at']);
        //dd($time_start, $time_end);
        if (!isOrderInTime(
            $time_start,
            $time_end,
            $football_pitch->time_start,
            $football_pitch->time_end
        )) {
            return response()->json([
                'message' => 'Thời gian bạn đặt sân chưa mở',
                'status' => 'error'
            ], Response::HTTP_BAD_REQUEST);
        }
        //nếu không có lỗi gì thì ok
        $peak_hour = PeakHour::all()->first();
        $total_price = getPriceOrder([
            'time_start' => $peak_hour->start_at,
            'time_end' => $peak_hour->end_at,
        ], [
            'start_at' => $validated['start_at'],
            'end_at' => $validated['end_at'],
        ], $football_pitch->price_per_hour, $football_pitch->price_per_peak_hour);
        return response()->json([
            'status' => 'success',
            'message' => 'Sân bóng có thể đặt',
            'total_price' => printMoney($total_price),
        ], Response::HTTP_CREATED);
    }

    public function findTimeAvailable(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required',
            'football_pitch_id' => 'required|exists:football_pitches,id',
        ]);

        $football_pitch = FootballPitch::find($validated['football_pitch_id']);
        if ($football_pitch->is_maintenance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sân bóng đang bảo trì rồi',
            ]);
        }

        $data = [];
        $date_time_start = new Carbon($validated['date'] . ' ' . $football_pitch->time_start);
        $date_time_end = new Carbon($validated['date'] . ' ' . $football_pitch->time_end);
        $orders = Order::query()
            ->where('status', '<>', OrderStatusEnum::Cancel)
            ->where('football_pitch_id', $validated['football_pitch_id'])
            ->whereBetween('start_at', [
                $date_time_start->toDateTimeString(),
                $date_time_end->toDateTimeString()
            ])
            ->orderBy('start_at')
            ->get();

        if ($orders->count() > 0) {
            foreach ($orders as $order) {
                $start_at = new Carbon($order->start_at);
                $end_at = new Carbon($order->end_at);
                $data[] = [
                    'start_at' => $start_at->toTimeString(),
                    'end_at' => $end_at->toTimeString(),
                ];
            }
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tất cả thời gian đều trống',
        ]);
    }

    public function findFootballPitchNotInOrderByDateTime(Request $request)
    {
        $validated = $request->validate([
            'start_at' => 'required|date',
            'end_at' => 'required|date',
        ]);
        $peak_hour = PeakHour::all()->first();
        $nearStartAt = new Carbon($validated['start_at']);
        $nearEndAt = new Carbon($validated['end_at']);
        //kiểm tra thời gian kết thúc <  bắt đầu
        if ($nearEndAt <= $nearStartAt) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng chọn thời gian kết thúc lớn hơn thời gian bắt đầu',
            ], Response::HTTP_BAD_REQUEST);
        }

        $startOfDay = new Carbon($validated['start_at']);
        $endOfDay = new Carbon($validated['end_at']);
        $startOfDay->startOfDay();
        $endOfDay->endOfDay();
        $orders = Order::query()
            ->where('status', '<>', OrderStatusEnum::Cancel)
            ->whereBetween('start_at', [
                $startOfDay->toDateTimeString(),
                $endOfDay->toDateTimeString()
            ])
            ->with('footballPitch')
            ->get();
        
        $arrFootballPitchId = [];
        foreach ($orders as $item) {
            if (isOrderInTime($validated['start_at'], $validated['end_at'], $item->start_at, $item->end_at))
            {
                if (!in_array($item->football_pitch_id, $arrFootballPitchId)) {
                    $arrFootballPitchId[] = $item->football_pitch_id;
                }
            }
            $fromFootballPitchId = $item->footballPitch->from_football_pitch_id;
            $toFootballPitchId = $item->footballPitch->to_football_pitch_id;
            if ($fromFootballPitchId && $toFootballPitchId) {
                if (!in_array($fromFootballPitchId, $arrFootballPitchId)) {
                    $arrFootballPitchId[] = $fromFootballPitchId;
                }
                if (!in_array($toFootballPitchId, $arrFootballPitchId)) {
                    $arrFootballPitchId[] = $toFootballPitchId;
                }
            }
        }
        $footballPitchs = FootballPitch::query()
            ->where('is_maintenance', 0)
            ->whereNotIn('id', $arrFootballPitchId)
            ->with('pitchType')
            ->get();

        $footballPitchsFinal = [];

        foreach ($footballPitchs as $football_pitch) {
            if ($football_pitch->from_football_pitch_id && $football_pitch->to_football_pitch_id) {
                $order_with_football_pitch_links = Order::query()
                    ->where('status', '<>', OrderStatusEnum::Cancel)
                    ->where(function ($query) use ($football_pitch) {
                        $query->where('football_pitch_id', $football_pitch->from_football_pitch_id)
                        ->orWhere('football_pitch_id', $football_pitch->to_football_pitch_id);
                    })
                    ->where(function ($query) use ($nearStartAt, $nearEndAt, $validated) {
                        $query->whereBetween('start_at', [
                            $validated['start_at'],
                            $nearEndAt->toDateTimeString(),
                        ])
                            ->orWhereBetween('end_at', [
                                $nearStartAt->toDateTimeString(),
                                $validated['end_at'],
                            ]);
                    })
                    ->groupBy('football_pitch_id')->get(['football_pitch_id']);
                if ($order_with_football_pitch_links->count() == 0) {
                    $footballPitchsFinal[] = $football_pitch;
                }
            } else {
                $footballPitchsFinal[] = $football_pitch;
            }
        }
        //dd($footballPitchsFinal);
        $data = [];
        foreach ($footballPitchsFinal as $item) {
            $total_price = getPriceOrder([
                'time_start' => $peak_hour->start_at,
                'time_end' => $peak_hour->end_at,
            ], [
                'start_at' => $validated['start_at'],
                'end_at' => $validated['end_at'],
            ], $item->price_per_hour, $item->price_per_peak_hour);
            $data[] = [
                'total_price' => printMoney($total_price),
                'name' => $item->name,
                'quantity' => $item->pitchType->quantity,
                'start_at' => $validated['start_at'],
                'end_at' => $validated['end_at'],
                'football_pitch_id' => $item->id,

            ];
        }
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function clearOrderNotUse()
    {
        $hours_not_use = 24;
        $date = new Carbon();
        $date->subHours($hours_not_use);

        $orders = Order::query()
            ->where('status', OrderStatusEnum::Wait)
            ->where('created_at', '<=', $date->toDateTimeString())
            ->get();

        $orders->each(function ($order) {
            $order->status = 0;
            $order->save();
        });

        return to_route('admin.orderTable')->with('message', 'Xóa các yêu cầu rác thành công');
    }

    public function cancelOrder(string $id)
    {
        $order = Order::find($id);
        $user = auth()->user();
        if ($order->by_user_id == $user->id) {
            $order->status = OrderStatusEnum::Cancel;
            $order->save();
        }

        return redirect()->back()->with('message', 'Hủy sân thành công');
    }
}
