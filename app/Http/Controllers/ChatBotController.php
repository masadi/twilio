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
        $MessageSid = $request->input('MessageSid');
        $rawdata = file_get_contents("php://input");
		$json = json_decode($rawdata, true);
        Storage::disk('public')->put('whatsapp.json', json_encode(request()->all()));
        $this->sendWhatsAppMessage($body, $user, $from, $WaId, $MessageSid);
        return;
    }
    public function sendWhatsAppMessage($body, $user, $recipientNumber, $WaId, $MessageSid)
    {
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
        $twilio = new Client($twilioSid, $twilioToken);
        $message = $this->welcomeMessage($user);
        $isi_pesan = [
            "from" => "whatsapp:+" . $twilioWhatsAppNumber,
            "body" => $message,
        ];
        if($body == '/erapor'){
            $isi_pesan = $this->bodyMessage(0, $twilioWhatsAppNumber, $WaId, $MessageSid);
        } else {
            $find = Whatsapp::where(function($query) use ($user, $WaId){
                $query->where('nama', $user);
                $query->where('wa_id', $WaId);
                $query->where('status', 1);
            })->first();
            if($find){
                if($body == 99){
                    Whatsapp::where('wa_id', $WaId)->update(['status' => 0]);
                    $isi_pesan = $this->byeMessage($twilioWhatsAppNumber, $WaId, $MessageSid);
                } else {
                    $isi_pesan = $this->bodyMessage($body, $twilioWhatsAppNumber, $WaId, $MessageSid);
                }
            }
        }
        Storage::disk('public')->put('rawdata.json', json_encode($isi_pesan));
        $pesan = $twilio->messages->create(
            $recipientNumber,
            $isi_pesan
        );
        if($body != 99){
            Whatsapp::updateOrCreate([
                'nama' => $user,
                'sid' => $pesan->sid,
                'wa_id' => $WaId,
            ]);
        }
        return $pesan;
    }
    private function welcomeMessage($user){
        $message = "Hai *$user*\n";
        $message .= "Selamat Datang di Pusat Layanan Aplikasi e-Rapor SMK\n";
        $message .= "Silahkan ketik /erapor untuk memulai percakapan\n";
        return $message;
    }
    private function bodyMessage($id, $twilioWhatsAppNumber, $WaId, $MessageSid){
        if($id){
            $msg = Message::with('messages')->find($id);
        } else {
            $msg = Message::with('messages')->where('title', 0)->first();
        }
        $message = "Jawaban tidak ditemukan:\nBalas pesan ini Dengan memilih 1 opsi:\n0 untuk kembali ke menu utama\n99 untuk keluar dari percakapan\n";
        $MediaUrl = NULL;
        if($msg){
            $MediaUrl = $msg->MediaUrl;
            if($msg->title){
                $message = '*'.$msg->title."*\n\n";
                $message .= $msg->body."\n\n";
            } else {
                $message = $msg->body."\n\n";
            }
            $message .= 'Balas pesan ini Dengan memilih 1 opsi:'."\n";
            if($msg->messages->count()){
                foreach($msg->messages as $sub){
                    $message .= $sub->id.' '.$sub->title."\n";
                }
            }
            $message .= "0 untuk kembali ke menu utama\n";
            if($msg->message_id){
                $message .= $msg->message_id." untuk kembali ke menu sebelumnya\n";
            }
            $message .= "99 untuk keluar dari percakapan";
        }
        if($MediaUrl){
            $isi_pesan = [
                "from" => "whatsapp:+" . $twilioWhatsAppNumber,
                "body" => $message,
                'MediaUrl' => $MediaUrl
            ];
        } else {
            $isi_pesan = [
                "from" => "whatsapp:+" . $twilioWhatsAppNumber,
                "body" => $message,
            ];
        }
        return $isi_pesan;
    }
    private function byeMessage($twilioWhatsAppNumber, $WaId, $MessageSid){
        $isi_pesan = [
            "from" => "whatsapp:+" . $twilioWhatsAppNumber,
            "body" => "Terima Kasih telah menghubungi Pusat Layanan Aplikasi e-Rapor SMK\n",
        ];
        return $isi_pesan;
    }
}
