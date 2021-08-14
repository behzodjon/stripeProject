<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Events\StripePaymentFailed;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe;
use Stripe\Event;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(3);

        return view('products-list', compact('products'));
    }

    public function buy(Product $product)
    {
        $stripe = \Stripe::make();
        $session = $stripe->checkout->sessions->create([
            'customer_email' => auth()->user()->email ?? 'test@gmail.com',
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [
                0 => [
                    "price_data" => [
                        "currency" => "USD",
                        "product_data" => [
                            "name" => $product->title,
                            "description" => $product->desc
                        ],
                        "unit_amount" => $product->price,
                    ],
                    "quantity" => 1
                ],
            ],
            'success_url' => route('products'),
            'cancel_url' => route('products.fail'),
        ]);
//        dd($session);

        Order::create([
            'user_id' => auth()->user()->id ?? null,
            'price' => $product->price,
            'paid' => 1,
        ]);
//        event(new OrderPaid($session));


        return redirect($session->url);
    }

    public function stripePaymentWebhook(Request $request)
    {
        $event = Event::constructFrom($request->all());

        switch ($event->type) {
            case 'payment_intent.payment_failed':
                event(new StripePaymentFailed($event));
                break;
            case 'payment_intent.succeeded':
                event(new OrderPaid($event));
                break;
            default:
                \Log::warning('Stripe: Received Unknown Webhook Event', [$event]);
        }

        return response('OK', 200);
    }

    public function buyFail()
    {
        return redirect()->back();
    }
}
