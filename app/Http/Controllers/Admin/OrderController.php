<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with('customer', 'items')
                            ->latest('orders.created_at')
                            ->select('orders.*');

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('customer_name', function($order) {
                    return $order->customer->name ?? 'N/A'; 
                })
                ->addColumn('num_items', function($order) {
                    return $order->items->sum('quantity'); 
                })
                ->addColumn('total_amount_formatted', function($order) {
                    return '$' . number_format($order->total_amount, 2);
                })
                ->editColumn('status', function($order) {
                    $badgeClass = [
                        'Pending' => 'bg-warning text-dark',
                        'Completed' => 'bg-success',
                        'Cancelled' => 'bg-danger'
                    ][$order->status] ?? 'bg-secondary';

                    return '<span class="badge '.$badgeClass.'">'.$order->status.'</span>';
                })
                ->editColumn('order_date', function($order) {
                    return $order->created_at->format('d-m-Y H:i');
                })
                ->addColumn('action', function($order) {
                    return '
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="'.route('admin.orders.show', $order->id).'" class="btn btn-info text-white" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-warning update-status-btn" data-id="'.$order->id.'" data-status="'.$order->status.'" title="Update Status">
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.orders.index');
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $products = Product::where('stock_quantity', '>', 0)->orderBy('name')->get();
        
        return view('admin.orders.create', compact('customers', 'products'));
    }

    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'total_amount' => $request->total_amount,
                'status' => 'Pending',
                'order_date' => now(),
            ]);

            $orderItems = [];
            $productUpdates = [];

            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']);

                if (!$product || $product->stock_quantity < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Product ' . $item['name'] . ' is out of stock or quantity is too high.')->withInput();
                }

                $orderItems[] = [
                    'product_id' => $item['product_id'],
                    'order_id' => $order->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ];
                
                $productUpdates[$product->id] = $product->stock_quantity - $item['quantity'];
            }

            $order->items()->createMany($orderItems);

            foreach ($productUpdates as $productId => $newStock) {
                Product::where('id', $productId)->update(['stock_quantity' => $newStock]);
            }

            DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Order #' . $order->id . ' created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Order creation failed: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load('customer', 'items.product');
        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Order status updated successfully.'], 200);
    }
}
