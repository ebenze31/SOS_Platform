<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\My_log;
use App\Models\Emergency_operation;
use App\Models\User_officer;
use App\User;

class LineWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info($request->all());
        $events = $request->input('events', []);

        foreach ($events as $event) {
            
            // --- บันทึก Log ลง DB ---
            $eventType = $event['type'] ?? '';
            $title = 'รับ Webhook จาก LINE'; 
            $textContent = ''; 

            if ($eventType === 'message') {
                $messageType = $event['message']['type'] ?? '';
                $title = 'ผู้ใช้ส่งข้อความ';
                $textContent = $messageType === 'text' ? ($event['message']['text'] ?? '') : "[$messageType]";
            } elseif ($eventType === 'postback') {
                $title = 'ผู้ใช้กดปุ่มแอคชัน (Postback)';
                $textContent = $event['postback']['data'] ?? '';
            } elseif ($eventType === 'follow') {
                $title = 'ผู้ใช้เพิ่มเพื่อน (Follow)';
            } elseif ($eventType === 'unfollow') {
                $title = 'ผู้ใช้บล็อค (Unfollow)';
            }

            // ตัดคำถ้าเกิน 250 ตัวอักษร
            if (mb_strlen($textContent, 'UTF-8') > 250) {
                $textContent = mb_substr($textContent, 0, 247, 'UTF-8') . '...';
            }

            $log = new My_log();
            $log->title = $title;
            $log->content = $textContent; 
            $log->event_arr = json_encode($event, JSON_UNESCAPED_UNICODE);
            $log->save();

            // --- แยกการทำงาน Routing Handlers ---
            if ($eventType === 'postback') {
                $this->postbackHandler($event);
            }
            elseif ($eventType === 'message') {
                $this->messageHandler($event);
            }
            elseif ($eventType === 'follow') {
                // $this->messageHello($event);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    // ==========================================
    // Handlers
    // ==========================================

    // private function postbackHandler($event)
    // {
    //     $this->Loading_Animation($event);

    //     $dataString = $event['postback']['data'] ?? '';
    //     parse_str($dataString, $parsedData);

    //     // ตรวจสอบ action
    //     if (isset($parsedData['action']) && $parsedData['action'] === 'accept') {
            
    //         $operationId = $parsedData['operation_id'] ?? null;
    //         $lineUserId = $event['source']['userId'];

    //         // ค้นหา User จากตาราง users ด้วย provider_id
    //         $user = User::where('provider_id', $lineUserId)->first();

    //         if ($user) {
    //             // ค้นหาข้อมูลเจ้าหน้าที่จากตาราง user_officers ด้วย user_id
    //             $officer = User_officer::where('user_id', $user->id)->first();

    //             if ($officer) {
    //                 // ดึงข้อมูลเคส (Operation)
    //                 $operation = Emergency_operation::find($operationId);

    //                 if ($operation) {
    //                     // ป้องกันกรณีเคสถูกคนอื่นรับไปแล้ว หรือถูกยกเลิกไปแล้ว
    //                     if ($operation->status !== 'สั่งการ') {
    //                         $this->replyText($event['replyToken'], "ขออภัย เคสนี้ถูกดำเนินการไปแล้ว หรือไม่อยู่ในสถานะที่รับงานได้");
    //                         return;
    //                     }
                        
    //                     // --- อัปเดตตาราง emergency_operations ---
    //                     $operation->waiting_reply = null;
    //                     $operation->status = 'กำลังไปช่วยเหลือ';
    //                     $operation->user_officers_id = $officer->id;
    //                     $operation->time_go_to_help = now();

    //                     // --- อัปเดตตาราง user_officers ---
    //                     $officer->line_notified_at = now();
    //                     $officer->save();

    //                     // --- อัปเดตคอลัมน์ log_command ---
    //                     $logs = json_decode($operation->log_command, true) ?? [];
                        
    //                     foreach ($logs as &$logItem) {
    //                         // เช็ค Log ของเจ้าหน้าที่คนนี้ และสถานะยังเป็น pending หรือ no_respond
    //                         if ($logItem['sendTo'] == $officer->id && in_array($logItem['status'], ['pending', 'no_respond'])) {
    //                             $logItem['status'] = 'go_to_help';
                                
    //                             // เก็บเวลาที่กดรับงานจริงๆ ไว้ด้วย
    //                             $logItem['time_accepted'] = now()->toIso8601String();
                                
    //                             // คำนวณเวลาที่ใช้ไป (sum_time) ตั้งแต่สั่งการจนถึงตอนกดรับ
    //                             $startTime = strtotime($logItem['datetime']);
    //                             $logItem['sum_time'] = time() - $startTime;
    //                         }
    //                     }
                        
    //                     $operation->log_command = json_encode($logs, JSON_UNESCAPED_UNICODE);
    //                     $operation->save();

    //                     // ส่ง Flex Message ตอบกลับไปยังเจ้าหน้าที่
    //                     $this->replyFlexConfirm($event['replyToken'], $operation, $officer);

    //                 } else {
    //                     $this->replyText($event['replyToken'], "ขออภัย ไม่พบเคสนี้ในระบบ");
    //                 }
    //             } else {
    //                 $this->replyText($event['replyToken'], "บัญชีของคุณยังไม่ได้ลงทะเบียนเจ้าหน้าที่");
    //             }
    //         } else {
    //             $this->replyText($event['replyToken'], "ไม่พบข้อมูลผู้ใช้งานของคุณในระบบ กรุณาล็อกอินผ่าน LINE ในระบบหลัก");
    //         }
    //     }
    // }

    private function postbackHandler($event)
    {
        $this->Loading_Animation($event);

        $dataString = $event['postback']['data'] ?? '';
        parse_str($dataString, $parsedData);

        if (!isset($parsedData['action'])) {
            return;
        }

        $operationId = $parsedData['operation_id'] ?? null;
        $lineUserId = $event['source']['userId'];

        // ค้นหา User และ Officer จาก Provider ID
        $user = User::where('provider_id', $lineUserId)->first();
        if (!$user) {
            $this->replyText($event['replyToken'], "ไม่พบข้อมูลผู้ใช้งานของคุณในระบบ");
            return;
        }

        $officer = User_officer::where('user_id', $user->id)->first();
        if (!$officer) {
            $this->replyText($event['replyToken'], "บัญชีของคุณยังไม่ได้ลงทะเบียนเจ้าหน้าที่");
            return;
        }

        $operation = Emergency_operation::find($operationId);
        if (!$operation) {
            $this->replyText($event['replyToken'], "ขออภัย ไม่พบเคสนี้ในระบบ");
            return;
        }

        // กรณีเจ้าหน้าที่กดรับงาน (Accept)
        if ($parsedData['action'] === 'accept') {
            if ($operation->status !== 'สั่งการ') {
                $this->replyText($event['replyToken'], "ขออภัย เคสนี้ถูกดำเนินการไปแล้ว");
                return;
            }

            $operation->waiting_reply = null;
            $operation->status = 'กำลังไปช่วยเหลือ';
            $operation->user_officers_id = $officer->id;
            $operation->time_go_to_help = now();
            $officer->status = 'Helping';
            $officer->line_notified_at = now();

            $currentHelp = (int)($officer->amount_help ?? 0);
            $officer->amount_help = (string)($currentHelp + 1);

            $officer->save();

            $logs = json_decode($operation->log_command, true) ?? [];
            foreach ($logs as &$logItem) {
                if ($logItem['sendTo'] == $officer->id && in_array($logItem['status'], ['pending', 'no_respond'])) {
                    $logItem['status'] = 'go_to_help';
                    $logItem['time_accepted'] = now()->toIso8601String();
                    $startTime = strtotime($logItem['datetime']);
                    $logItem['sum_time'] = time() - $startTime;
                }
            }
            
            $operation->log_command = json_encode($logs, JSON_UNESCAPED_UNICODE);
            $operation->save();

            $this->replyFlexConfirm($event['replyToken'], $operation, $officer);
        }
        // กรณีเจ้าหน้าที่กดปฏิเสธงาน (Reject)
        elseif ($parsedData['action'] === 'reject') {
            if ($operation->status !== 'สั่งการ' || $operation->waiting_reply != $officer->id) {
                $this->replyText($event['replyToken'], "ขออภัย คุณไม่สามารถปฏิเสธเคสนี้ได้ในขณะนี้");
                return;
            }

            // อัปเดตนับจำนวนการปฏิเสธเพิ่ม 1 ในตาราง user_officers
            $currentRefuse = (int)($officer->amount_refuse ?? 0);
            $officer->amount_refuse = (string)($currentRefuse + 1);
            $officer->save(); // เซฟข้อมูลเจ้าหน้าที่

            // อัปเดตตาราง operations ล้างค่าคนรอตอบรับ และเพิ่มรายชื่อในคนปฏิเสธ
            $operation->waiting_reply = null;
            $refuseList = json_decode($operation->officer_refuse, true) ?? [];
            if (!in_array($officer->id, $refuseList)) {
                $refuseList[] = $officer->id;
            }
            $operation->officer_refuse = json_encode($refuseList);

            // อัปเดต log_command เป็น reject
            $logs = json_decode($operation->log_command, true) ?? [];
            foreach ($logs as &$logItem) {
                if ($logItem['sendTo'] == $officer->id && $logItem['status'] === 'pending') {
                    $logItem['status'] = 'reject';
                    $logItem['time_rejected'] = now()->toIso8601String();
                    $startTime = strtotime($logItem['datetime']);
                    $logItem['sum_time'] = time() - $startTime;
                }
            }

            $operation->log_command = json_encode($logs, JSON_UNESCAPED_UNICODE);
            $operation->save();

            $this->replyText($event['replyToken'], "รับทราบครับ คุณได้ทำการปฏิเสธเคสเรียบร้อยแล้ว");
        }
    }

    private function messageHandler($event)
    {
        // ตอบกลับข้อความ
    }

    private function Loading_Animation($event)
    {
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . env('CHANNEL_ACCESS_TOKEN')
        );

        $url = "https://api.line.me/v2/bot/chat/loading/start";
        $data = array(
            "chatId" => $event['source']['userId'],
            "loadingSeconds" => 60
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        curl_close($ch);
    }

    // ส่ง Flex Message สำหรับการยืนยันรับเคส
    private function replyFlexConfirm($replyToken, $operation, $officer)
    {
        $template_path = public_path('json/flex-sos/officer_confirm.json'); 
        $actionUrl = url('/officer/action/' . $operation->id);
        
        // กันเหนียว เผื่อหาไฟล์ JSON ไม่เจอ ให้ส่งเป็นข้อความ + ลิงก์แทน
        if (!file_exists($template_path)) {
            $fallbackMessage = "รับเคสสำเร็จ ดำเนินการได้ที่ลิงก์นี้: \n" . $actionUrl;
            $this->replyText($replyToken, $fallbackMessage);
            return;
        }

        $string_json = file_get_contents($template_path);

        // ดึงข้อมูลวันที่และเวลาจาก time_create_sos (ถ้ามี) หรือเวลาสร้าง Operation
        $timestamp = !empty($operation->time_create_sos) 
            ? strtotime($operation->time_create_sos) 
            : strtotime($operation->created_at);
            
        $dateCreate = date('d/m/Y', $timestamp);
        $timeCreate = date('H:i น.', $timestamp);

        // ทำการ Replace ข้อความใน JSON ด้วยตัวแปร
        $string_json = str_replace("{operating_code}", $operation->operating_code ?? '-', $string_json);
        $string_json = str_replace("{name_officer}", $officer->name_officer ?? 'เจ้าหน้าที่', $string_json);
        $string_json = str_replace("{date_create}", $dateCreate, $string_json);
        $string_json = str_replace("{time_create}", $timeCreate, $string_json);
        $string_json = str_replace("{action_url}", $actionUrl, $string_json);

        $flexMessage = json_decode($string_json, true);

        $url = 'https://api.line.me/v2/bot/message/reply';
        $data = [
            'replyToken' => $replyToken,
            'messages' => [ $flexMessage ]
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . env('CHANNEL_ACCESS_TOKEN')
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    // เอาไว้ใช้ตอบกลับข้อความทั่วไป
    private function replyText($replyToken, $text)
    {
        $url = 'https://api.line.me/v2/bot/message/reply';
        $data = [
            'replyToken' => $replyToken,
            'messages' => [['type' => 'text', 'text' => $text]],
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . env('CHANNEL_ACCESS_TOKEN')
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    public static function sendLineNotice($userId, $text)
    {
        $url = 'https://api.line.me/v2/bot/message/push';
        
        $data = [
            'to' => $userId,
            'messages' => [
                ['type' => 'text', 'text' => $text]
            ],
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . env('CHANNEL_ACCESS_TOKEN')
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        $textContent = $text;
        
        // ตัดคำถ้าเกิน 250 ตัวอักษร
        if (mb_strlen($textContent, 'UTF-8') > 250) {
            $textContent = mb_substr($textContent, 0, 247, 'UTF-8') . '...';
        }

        // จัดรูปแบบข้อมูลที่จะเก็บลง event_arr
        $logData = [
            'action' => 'push_message',
            'request' => $data,
            'response' => json_decode($result, true) ?? $result,
            'curl_error' => $curlError
        ];

        $log = new My_log();
        $log->title = 'ระบบส่งข้อความแจ้งเตือน (Push)';
        $log->content = $textContent; 
        $log->event_arr = json_encode($logData, JSON_UNESCAPED_UNICODE);
        $log->save();
        // ==========================================

        // เก็บ Log ลงไฟล์ของ Laravel เผื่อกรณี Error ระดับระบบ
        if ($curlError) {
            Log::error('Line Push Error: ' . $curlError);
        }

        return $result;
    }
}