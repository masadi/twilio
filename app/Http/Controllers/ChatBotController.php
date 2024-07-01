<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;
use App\Models\Whatsapp;
use App\Models\Message;

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
        $WaId = $request->input('WaId');
        $OriginalRepliedMessageSid = $request->input('OriginalRepliedMessageSid');
        Log::info('from: '.$from);
        Log::info('body: '.$body);
        $rawdata = file_get_contents("php://input");
		$json = json_decode($rawdata, true);
        Storage::disk('public')->put('whatsapp.json', json_encode(request()->all()));
        Storage::disk('public')->put('rawdata.json', json_encode($json));
        $this->sendWhatsAppMessage($body, $user, $from, $WaId, $OriginalRepliedMessageSid);
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
    public function sendWhatsAppMessage($body, $user, $recipientNumber, $WaId, $OriginalRepliedMessageSid)
    {
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
        $twilio = new Client($twilioSid, $twilioToken);
        $message = "Hai *$user*\n";
        $message .= "Selamat Datang di Pusat Layanan Aplikasi e-Rapor SMK\n";
        $message .= "Silahkan ketik /erapor untuk memulai percakapan\n";
        if($body == '/erapor'){
            $message = $this->welcomeMessage(0);
        } else {
            $find = Whatsapp::where(function($query) use ($user, $OriginalRepliedMessageSid, $WaId){
                $query->where('nama', $user);
                //$query->where('sid', $OriginalRepliedMessageSid);
                $query->where('wa_id', $WaId);
                $query->where('status', 1);
            })->first();
            if($find){
                if($body == 99){
                    Whatsapp::where('wa_id', $WaId)->update(['status' => 0]);
                    $message = "Terima Kasih telah menghubungi Pusat Layanan Aplikasi e-Rapor SMK\n";
                } else {
                    $message = $this->welcomeMessage($body);
                    /*$msg = Message::with('messages')->find($body);
                    if($msg){
                        if($msg->messages){
                            $message = '*'.$msg->title."*\n\n";
                            $message .= $msg->body."\n";
                            $i=1;
                            foreach($msg->messages as $sub){
                                $message .= $i.' '.$sub->title."\n";
                            }
                        } else {
                            $message = $msg->title."\n".$msg->body."\n";
                            $message .= "0 untuk kembali ke menu utama\n";
                            $message .= $msg->message_id." untuk kembali ke menu sebelumnya\n";
                            $message .= "99 untuk keluar dari percakapan";
                        }
                    } else {
                        $message = "Jawaban tidak ditemukan:\nBalas pesan ini Dengan memilih 1 opsi:\n0 untuk kembali ke menu utama\n99 untuk keluar dari percakapan\n";
                    }*/
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
                //'MediaUrl' => 'https://erapor.ditpsmk.net/img/faq/composer-update.png'
            ]
        );
        if($body != 99){
            Whatsapp::updateOrCreate([
                'nama' => $user,
                'sid' => $pesan->sid,
                'wa_id' => $WaId,
            ]);
        }
        //Storage::disk('public')->put('pesan.json', $pesan->sid);
        return $pesan;
    }
    private function welcomeMessage($id){
        if($id){
            $msg = Message::with('messages')->find($id);
        } else {
            $msg = Message::with('messages')->where('title', 0)->first();
        }
        $message = "Jawaban tidak ditemukan:\nBalas pesan ini Dengan memilih 1 opsi:\n0 untuk kembali ke menu utama\n99 untuk keluar dari percakapan\n";
        if($msg){
            if($msg->messages){
                if($msg->title){
                    $message = '*'.$msg->title."*\n\n";
                    $message .= $msg->body."\n";
                } else {
                    $message = $msg->body."\n";
                }
                $i=1;
                foreach($msg->messages as $sub){
                    $message .= $i.' '.$sub->title."\n";
                }
            } else {
                //$message = $msg->title."\n".$msg->body."\n";
                if($msg->title){
                    $message = '*'.$msg->title."*\n\n";
                    $message .= $msg->body."\n";
                } else {
                    $message = $msg->body."\n";
                }
                $message .= "0 untuk kembali ke menu utama\n";
                $message .= $msg->message_id." untuk kembali ke menu sebelumnya\n";
                $message .= "99 untuk keluar dari percakapan";
            }
        }
        return $message;
    }
    private function replyMessage($body){
        $data = [
            [
                $this->welcomeMessage()
            ],
            [
                "Informasi umum adalah sebagai berikut:\nAplikasi e-Rapor SMK adalah aplikasi yang dikembangkan oleh Direktorat SMK\nBalas pesan ini Dengan memilih 1 opsi:\n0 untuk kembali ke menu utama\n99 untuk keluar dari percakapan\n",
            ],
            [
                "Bantuan Troubleshooting e-Rapor SMK:\nBalas pesan ini Dengan memilih 1 opsi:\n0 untuk kembali ke menu utama\n99 untuk keluar dari percakapan\n",
            ]
        ];
        if(isset($data[$body])){
            return $data[$body];
        } else {
            return "Jawaban tidak ditemukan:\nBalas pesan ini Dengan memilih 1 opsi:\n0 untuk kembali ke menu utama\n99 untuk keluar dari percakapan\n";
        }
    }
}
