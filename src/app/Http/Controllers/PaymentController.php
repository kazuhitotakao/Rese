<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * 決済フォーム表示
     */
    public function create(Request $request)
    {
        $amount= Shop::find($request->shop_id)->amount;
        return view('payment.create',compact('amount'));
    }

    /**
     * 決済実行
     */
    public function store(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('stripe.stripe_secret_key'));

        try {
            \Stripe\Charge::create([
                'source' => $request->stripeToken,
                'amount' => $request->amount,
                'currency' => 'jpy',
            ]);
        } catch (Exception $e) {
            return back()->with('flash_alert', '決済に失敗しました！(' . $e->getMessage() . ')');
        }
        return back()->with('status', '決済が完了しました！');
    }
}
