<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use Carbon\Carbon;
use PDF;

class OrderController extends Controller
{
    //PendingOrders
    public function PendingOrders(){
        $orders = Order::where('status','Pending')->orderBy('id','DESC')->get();
        return view('backend.orders.pending.pending_orders', compact('orders'));
    }

    public function PendingOrderDetails($order_id){
        $order = Order::with('user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    	return view('backend.orders.pending.pending_order_details',compact('order','orderItem'));
    }

    //ConfirmedOrders
    public function ConfirmedOrders(){
        $orders = Order::where('status','Confirm')->orderBy('id','DESC')->get();
        return view('backend.orders.confirmed.confirmed_orders', compact('orders'));
    }

    public function ConfirmedOrderDetails($order_id){
        $order = Order::with('user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    	return view('backend.orders.confirmed.confirmed_order_details',compact('order','orderItem'));
    }
    
    //ProcessingOrders
    public function ProcessingOrders(){
        $orders = Order::where('status','Processing')->orderBy('id','DESC')->get();
        return view('backend.orders.processing.processing_orders', compact('orders'));
    }

    public function ProcessingOrderDetails($order_id){
        $order = Order::with( 'user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    	return view('backend.orders.processing.processing_order_details',compact('order','orderItem'));
    }

    //PickedOrders
    public function PickedOrders(){
        $orders = Order::where('status','Picked')->orderBy('id','DESC')->get();
        return view('backend.orders.picked.picked_orders', compact('orders'));
    }

    public function PickedOrderDetails($order_id){
        $order = Order::with( 'user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    	return view('backend.orders.picked.picked_order_details',compact('order','orderItem'));
    }

    //ShippedOrders
    public function ShippedOrders(){
        $orders = Order::where('status','Shipped')->orderBy('id','DESC')->get();
        return view('backend.orders.shipped.shipped_orders', compact('orders'));
    }

    public function ShippedOrderDetails($order_id){
        $order = Order::with( 'user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    	return view('backend.orders.shipped.shipped_order_details',compact('order','orderItem'));
    }

    //DeliveredOrders
    public function DeliveredOrders(){
        $orders = Order::where('status','Delivered')->orderBy('id','DESC')->get();
        return view('backend.orders.delivered.delivered_orders', compact('orders'));
    }

    public function DeliveredOrderDetails($order_id){
        $order = Order::with( 'user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    	return view('backend.orders.delivered.delivered_order_details',compact('order','orderItem'));
    }

    //CancelOrders
    public function CancelOrders(){
        $orders = Order::where('status','CANCEL / UNSUCCESSFUL')->orderBy('id','DESC')->get();
        return view('backend.orders.cancel.cancel_orders', compact('orders'));
    }

    public function CancelOrderDetails($order_id){
        $order = Order::with( 'user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    	return view('backend.orders.cancel.cancel_order_details',compact('order','orderItem'));
    }


    //UPDATE STATUS
    public function PendingToConfirm($order_id){
        Order::findOrFail($order_id)->update(['status' => 'Confirm']);
        $notification = array(
            'message' => 'Order Confirmed Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('pending-orders')->with($notification);
    }

    public function ConfirmToProcessing($order_id){
        Order::findOrFail($order_id)->update(['status' => 'Processing']);
        $notification = array(
            'message' => 'Order Processed Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('confirmed-orders')->with($notification);
    }

    public function ProcessingToPicked($order_id){
        Order::findOrFail($order_id)->update(['status' => 'Picked']);
        $notification = array(
            'message' => 'Order Picked Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('processing-orders')->with($notification);
    }

    public function PickedToShipped($order_id){
        Order::findOrFail($order_id)->update(['status' => 'Shipped']);
        $notification = array(
            'message' => 'Order Shipped Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('picked-orders')->with($notification);
    }

    public function ShippedToDelivered($order_id){
        Order::findOrFail($order_id)->update(['status' => 'Delivered']);
        $notification = array(
            'message' => 'Order Delivered Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('shipped-orders')->with($notification);
    }

    public function InvoiceDownload($order_id){
        $order = Order::with( 'user')->where('id',$order_id)->first();
    	$orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    	// return view('frontend.user.order.order_invoice',compact('order','orderItem'));
		$pdf = PDF::loadView('backend.orders.order_invoice', compact('order','orderItem'))->setPaper('a4')->setOptions([ 'tempDir' => public_path(),'chroot' => public_path(), ]);
		return $pdf->download('invoice.pdf');
    }
}
