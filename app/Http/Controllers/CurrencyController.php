<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{

    public function addCurrency(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die();
            if (empty($data['status'])) { $status=0; }else{ $status=1; }
            $currency = new Currency;
            $currency->currency_code = $data['currency_code'];
            $currency->exchange_rate = $data['exchange_rate'];
            $currency->status = $status;
            $currency->save();
            return redirect()->back()->with('flash_message_success','Currency has been added Successfully');
        }
        return view('admin.currencies.add_currency');
    }

    public function viewCurrencies()
    {
        $currencies = Currency::get();
        return view('admin.currencies.view_currencies', compact('currencies'));
    }

    public function editCurrency(Request $request, $id)
    {
        $currencyDetails = Currency::where('id',$id)->first();
        if ($request->isMethod('post')) {
            $data = $request->all();
            if (empty($data['status'])) { $status=0; }else{ $status=1; }
            Currency::where('id',$id)->update([
                'currency_code' => $data['currency_code'],
                'exchange_rate' => $data['exchange_rate'],
                'status' => $status,
            ]);
            return redirect()->back()->with('flash_message_success','Currency has been updated Successfully');
        }
        return view('admin.currencies.edit_currency', compact('currencyDetails'));
    }

    public function deleteCurrency($id)
    {
        Currency::where('id',$id)->delete();
        return redirect()->back()->with('flash_message_success','Currency has been Deleted Successfully');
    }

}
