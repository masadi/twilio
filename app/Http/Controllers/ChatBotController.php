<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;
use App\Models\Whatsapp;

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
        $user = $request->input('ProfileName');
        $OriginalRepliedMessageSid = $request->input('OriginalRepliedMessageSid');
        Log::info('from: '.$from);
        Log::info('body: '.$body);
        $rawdata = file_get_contents("php://input");
		$json = json_decode($rawdata, true);
        Storage::disk('public')->put('whatsapp.json', json_encode(request()->all()));
        Storage::disk('public')->put('rawdata.json', json_encode($json));
        $this->sendWhatsAppMessage($body, $user, $from, $OriginalRepliedMessageSid);
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
    public function sendWhatsAppMessage($body, $user, $recipientNumber, $OriginalRepliedMessageSid)
    {
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
        $twilio = new Client($twilioSid, $twilioToken);
        $message = "Hai *$user*\n";
        $message .= "Selamat Datang di Pusat Layanan Aplikasi e-Rapor SMK\n";
        $message .= "Silahkan ketik /erapor untuk memulai percakapan\n";
        if($body == '/erapor'){
            $message = $this->welcomeMessage();
        } else {
            if($OriginalRepliedMessageSid){
                $find = Whatsapp::where(function($query) use ($user, $OriginalRepliedMessageSid){
                    $query->where('nama', $user);
                    $query->where('sid', $OriginalRepliedMessageSid);
                    $query->where('status', 1);
                })->first();
                if($find){
                    if($body == 99){
                        Whatsapp::where('nama', $user)->update(['status' => 0]);
                        $message = "Terima Kasih telah menghubungi Pusat Layanan Aplikasi e-Rapor SMK\n";
                    } else {
                        $message =$this->replyMessage($body);
                        /*if($body == 0){
                        }
                        if($body == 1){
                            $message = "Informasi umum adalah sebagai berikut:\n";
                            $message .= "Aplikasi e-Rapor SMK adalah aplikasi yang dikembangkan oleh Direktorat SMK\n";
                            $message .= "Reply pesan ini dengan ketik:\n";
                            $message .= "0 untuk kembali ke menu sebelumnya\n";
                            $message .= "99 untuk keluar dari percakapan\n";
                        }
                        if($body == 2){
                            $message = "Bantuan Troubleshooting e-Rapor SMK:\n";
                            $message .= "Reply pesan ini dengan ketik:\n";
                            $message .= "0 untuk kembali ke menu sebelumnya\n";
                            $message .= "99 untuk keluar dari percakapan\n";
                        }*/
                    }
                } else {
                    $message = "Riwayat percakapan tidak ditemukan. Silahkan ketik /erapor untuk memulai percakapan\n";
                }
            }
        }
        $pesan = $twilio->messages->create(
            $recipientNumber,
            [
                "from" => "whatsapp:+" . $twilioWhatsAppNumber,
                "body" => $message,
                "type" => "QUICK_REPLY",
                "actions" => [
                    [
                        "title" => "Check flight status",
                        "id" => "flightid1"
                    ],
                    [
                        "title" => "Check gate number",
                        "id" => "flightid2"
                    ],
                    [
                        "title" => "Speak with an agent",
                        "id" => "flightid2"
                    ]
                ],
            ]
        );
        Whatsapp::create([
            'nama' => $user,
            'sid' => $pesan->sid,
        ]);
        //Storage::disk('public')->put('pesan.json', $pesan->sid);
        return $pesan;
    }
    private function welcomeMessage(){
        $message = "Reply pesan ini dengan ketik:\n";
        $message .= "1 untuk informasi umum\n";
        $message .= "2 untuk bantuan\n";
        return $message;
    }
    private function replyMessage($body){
        $data = [
            [
                ""
            ],
            [
                "Informasi umum adalah sebagai berikut:\n
                Aplikasi e-Rapor SMK adalah aplikasi yang dikembangkan oleh Direktorat SMK\n
                Reply pesan ini dengan ketik:\n
                0 untuk kembali ke menu sebelumnya\n
                99 untuk keluar dari percakapan\n",
            ],
            [
                "Bantuan Troubleshooting e-Rapor SMK:\n
                Reply pesan ini dengan ketik:\n
                0 untuk kembali ke menu sebelumnya\n
                99 untuk keluar dari percakapan\n",
            ]
        ];
        if(isset($data[$body])){
            return $data[$body];
        } else {
            return "Jawaban tidak ditemukan. Silahkan ketik /erapor untuk memulai percakapan\n";
        }
    }
}
