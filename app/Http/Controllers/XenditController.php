<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Xendit\Xendit;

require '..\vendor/autoload.php';

class XenditController extends Controller
{


    public function index()
    {

        Xendit::setApiKey('xnd_development_zJ7geXthOPJOioCRfE5mQ9FmzD4F6cVI8tkXI24HeWjzHU8UQ4Bf4WssV9AmSPB');

        $params = [
            'external_id' => 'SJB20220321TRX1',
            'payer_email' => 'nengkirahmat@gmail.com',
            'description' => 'Tagihan Transaksi',
            'amount' => 2000000,
            // "expiry_date" => "2022-03-21T12:00:00.469Z"
            "expiry_date" => Carbon::now()->addDays(1)->toSOString()
        ];

        $createInvoice = \Xendit\Invoice::create($params);
        dd($createInvoice);
    }

    public function saldo()
    {
        Xendit::setApiKey('xnd_development_zJ7geXthOPJOioCRfE5mQ9FmzD4F6cVI8tkXI24HeWjzHU8UQ4Bf4WssV9AmSPB');
        $getBalance = \Xendit\Balance::getBalance('CASH');
        return $getBalance;
    }

    public function createInvoice($trans, $customer, $item, $ongkir, $ppn, $pph, $successUrl, $failurUrl)
    {
        // dd($customer);
        Xendit::setApiKey('xnd_development_zJ7geXthOPJOioCRfE5mQ9FmzD4F6cVI8tkXI24HeWjzHU8UQ4Bf4WssV9AmSPB');

        $params = [
            'external_id' => $trans['invoice'],
            'amount' => $trans['amount'],
            'description' => $trans['deskription'],
            // 'invoice_duration' => $trans['duration'],
            'invoice_duration' => 100,
            'customer' => $customer,
            'customer_notification_preference' => [
                'invoice_created' => [
                    'whatsapp',
                    'sms',
                    'email'
                ],
                'invoice_reminder' => [
                    'whatsapp',
                    'sms',
                    'email'
                ],
                'invoice_paid' => [
                    'whatsapp',
                    'sms',
                    'email'
                ],
                'invoice_expired' => [
                    'whatsapp',
                    'sms',
                    'email'
                ]
            ],
            'success_redirect_url' => $successUrl,
            'failure_redirect_url' => $failurUrl,
            'currency' => 'IDR',
            'items' => $item,
            'fees' => [
                [
                    'type' => 'ONGKIR',
                    'value' => $ongkir
                ],
                [
                    'type' => 'PPN',
                    'value' => $ppn
                ],
                [
                    'type' => 'PPH',
                    'value' => -$pph
                ]
            ]
        ];

        $createInvoice = \Xendit\Invoice::create($params);
        return $createInvoice;
    }

    public function getInvoice($id)
    {
        Xendit::setApiKey('xnd_development_zJ7geXthOPJOioCRfE5mQ9FmzD4F6cVI8tkXI24HeWjzHU8UQ4Bf4WssV9AmSPB');

        $getInvoice = \Xendit\Invoice::retrieve($id);
        return $getInvoice;
    }

    // public function myInvoice($id)
    // {
    //     Xendit::setApiKey('');

    //     $invoice=Xeninvoice::where('xen_external_id',$id)->first();
    //     $xenId=$invoice->xen_id;
    //     $getInvoice = \Xendit\Invoice::retrieve($xenId);
    //     return $getInvoice;

    // }

    public function getReport()
    {
        Xendit::setApiKey('xnd_development_zJ7geXthOPJOioCRfE5mQ9FmzD4F6cVI8tkXI24HeWjzHU8UQ4Bf4WssV9AmSPB');

        $params = [
            'type' => 'TRANSACTIONS'
        ];
        $generate = \Xendit\Report::generate($params);
        var_dump($generate);

        $detail = \Xendit\Report::detail('report_5c1b34a2-6ceb-4c24-aba9-c836bac82b28');
        var_dump($detail);
    }
}
