<?php

namespace App\Mail;

use App\CentralLogics\SMS_module;
use App\Model\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order_id = $this->order_id;

        $order = Order::find($order_id);

        $message = 'Your order has been placed successfully with order id: #' . $order_id . '. You will receive the order receipt via email soon. Thank you for being with us.';

        SMS_module::send_sms($order->customer->phone, $message);

        return $this->view('email-templates.customer-order-placed', ['order' => $order]);
    }
}
