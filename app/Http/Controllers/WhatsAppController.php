<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {

        return view('whatsapp');

    }

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function store(Request $request)
    {

        $twilioSid = env('TWILIO_SID');

        $twilioToken = env('TWILIO_AUTH_TOKEN');

        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');

        $recipientNumber = $request->phone;

        $message = $request->message;

        try {

            $twilio = new Client($twilioSid, $twilioToken);

            $pesan = $twilio->messages->create(

                "whatsapp:+".$recipientNumber,

                [

                    "from" => "whatsapp:+" . $twilioWhatsAppNumber,

                    "body" => $message,

                ]

            );

            return back()->with(['success' => 'WhatsApp message sent successfully!', 'pesan' => $pesan]);

        } catch (Exception $e) {

            return back()->with(['error' => $e->getMessage()]);

        }

    }
}
