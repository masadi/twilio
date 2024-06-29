<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class ChatBotController extends Controller
{
    public function listenToReplies(Request $request)
    {
        $from = $request->input('From');
        $body = $request->input('Body');
        Log::info('from: '.$from);
        Log::info('body: '.$body);
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
        }
        return;
    }
    public function sendWhatsAppMessage(string $message, string $recipient)
    {
        $twilio_whatsapp_number = env('TWILIO_WHATSAPP_NUMBER');
        $account_sid = env("TWILIO_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        Log::info('twilio_whatsapp_number: '.$twilio_whatsapp_number);
        Log::info('account_sid: '.$account_sid);
        Log::info('auth_token: '.$auth_token);
        $client = new Client($account_sid, $auth_token);
        return $client->messages->create(
            "whatsapp:".$recipient,
            [
                "from" => "whatsapp:+" . $twilio_whatsapp_number,
                "body" => $message,
            ]
        );
        return $client->messages->create($recipient, array('from' => "whatsapp:+$twilio_whatsapp_number", 'body' => $message));
    }
}
