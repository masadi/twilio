<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class ChatBotController extends Controller
{
    public function index(){
        $data = [
            'success' => TRUE,
            'message' => 'Terhubung ke whatsapp'
        ];
    }
    public function listenToReplies(Request $request)
    {
        $from = $request->input('From');
        $body = $request->input('Body');
        Log::info('from: '.$from);
        Log::info('body: '.$body);
        $rawdata = file_get_contents("php://input");
		$json = json_decode($rawdata, true);
        Storage::disk('public')->put('whatsapp.json', json_encode(request()->all()));
        Storage::disk('public')->put('rawdata.json', json_encode($json));
        $this->sendWhatsAppMessage($body, $from);
        /*
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', "https://api.github.com/users/$body");
            $githubResponse = json_decode($response->getBody());
            if ($response->getStatusCode() == 200) {
                $message = "*Name:* $githubResponse->name\n";
                $message .= "*Bio:* $githubResponse->bio\n";
                $message .= "*Lives in:* $githubResponse->location\n";
                $message .= "*Number of Repos:* $githubResponse->public_repos\n";
                $message .= "*Followers:* $githubResponse->followers devs\n";
                $message .= "*Following:* $githubResponse->following devs\n";
                $message .= "*URL:* $githubResponse->html_url\n";
                $this->sendWhatsAppMessage($message, $from);
            } else {
                $this->sendWhatsAppMessage($githubResponse->message, $from);
            }
        } catch (Exception $e) {
            $this->sendWhatsAppMessage($e->getMessage(), $from);
        }*/
        return;
    }
    public function sendWhatsAppMessage(string $body, string $recipientNumber)
    {
        /*$twilio_whatsapp_number = env('TWILIO_WHATSAPP_NUMBER');
        $account_sid = env("TWILIO_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        Log::info('twilio_whatsapp_number: '.$twilio_whatsapp_number);
        Log::info('account_sid: '.$account_sid);
        Log::info('auth_token: '.$auth_token);
        //$client = new Client($account_sid, $auth_token);
        $twilio = new Client($account_sid, $auth_token);
        $pesan = $twilio->messages->create(
            "whatsapp:".$recipient,
            [
                "from" => "whatsapp:+" . $twilio_whatsapp_number,
                "body" => $message,
            ]
        );
        return $pesan;
        return $client->messages->create($recipient, array('from' => "whatsapp:+$twilio_whatsapp_number", 'body' => $message));*/
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
        $twilio = new Client($twilioSid, $twilioToken);
        $message = 'disini body nya ya';
        if($body == '/erapor'){
            $message = "Hai *$user*\n";
            $message .= "Selamat Datang di Pusat Layanan Aplikasi e-Rapor SMK\n";
            $message .= "Reply pesan ini dengan ketik:\n";
            $message .= "Angka 1 untuk informasi umum\n";
            $message .= "Angka 2 untuk bantuan\n";
        }
        $pesan = $twilio->messages->create(
            $recipientNumber,
            [
                "from" => "whatsapp:+" . $twilioWhatsAppNumber,
                "body" => $message,
                /*"actions" => [
                    [
                        "type" => "QUICK_REPLY",
                        "title" => "Check flight status",
                        "id" => "flightid1"
                    ],
                    [
                        "type" => "QUICK_REPLY",
                        "title" => "Check gate number",
                        "id" => "flightid2"
                    ],
                    [
                        "type" => "QUICK_REPLY",
                        "title" => "Speak with an agent",
                        "id" => "flightid2"
                    ]
                ],*/
            ]
        );
        return $pesan;
    }
}
