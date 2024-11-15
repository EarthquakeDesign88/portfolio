<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PaymentMethodController extends Controller
{
    public function index(Request $request) 
    { 
        if ($request->ajax()) {
            return DataTables::of(PaymentMethod::query()->orderBy('id', 'ASC'))
                ->addIndexColumn()
                ->make(true);
        }

        return view('payment.payment-method');
    }

    public function getPaymentMethods()
    {
        $paymentMethods = PaymentMethod::orderBy('id', 'ASC')->get();
        $countPaymentMethods = $paymentMethods->count();

        return response()->json([
            'paymentMethods' => $paymentMethods,
            'countPaymentMethods' => $countPaymentMethods
        ]);
    }
}
