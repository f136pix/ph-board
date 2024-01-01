<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index()
    {
        // load orderItems foreign items
        return response()->json(OrderResource::collection(Order::with('orderItems')->paginate()), Response::HTTP_OK);
    }

    public function show($id)
    {
        return response()->json(new OrderResource(Order::with('orderItems')->find($id)));
    }

    public function export()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=orders.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        $callback = function () {

            // file onde csv sera inserted
            $csvFile = fopen('php://output', 'w');

            // csv headers
            fputcsv($csvFile, ['ID', 'NAME', 'EMAIL', 'PRODUCT', 'PRICE', 'AMOUNT']);

            $orders = Order::all();
            foreach ($orders as $order) {
                fputcsv($csvFile, [$order->id, $order->name, $order->email, '', '', '']);

                foreach ($order->orderItems as $orderItems) {
                    fputcsv($csvFile, ['', '', '', $orderItems->product_title, $orderItems->price, $orderItems->quantity]);
                }
            }

            fclose($csvFile);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function chart()
    {
        /* select date_format(o.created_at, '%Y-%m-%d') as date, SUM(oi.price * oi.quantity) as sum
        from orders o
        join order_items oi on o.id = oi.order_id
        group by date; */

        $orders = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw("DATE_FORMAT(orders.created_at, '%Y-%m-%d') as date, SUM(order_items.price * order_items.quantity) as sum")
            ->groupBy('date')
            ->get();

        return $orders;
    }
}
