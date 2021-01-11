<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use Telegram\Bot\Api;

class XatbotController extends Controller
{
    protected $telegram;
    protected $chat_id;
    protected $text;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function updatedActivity() {
        $activity = Telegram::getUpdates();
        dd($activity);
    }

    public function welcome() {
        $text = "Greetings";

        Telegram::sendMessage([
            'chat_id' => -424369432,
            'parse_mode' => 'HTML',
            'text' => $text
        ]);
    }

    public function setWebhook() {
        $response = Telegram::setWebhook(['url' => 
        'https://professorthaddeusbot.herokuapp.com/api/3ymCKxba9W61ANhx03Ub7i8dOhOEsh4me5j2dOQGhgShGoxjlCivKF3uiUEThxBuZqN9nvM2tPg3UGze/webhook']);
        dd($response);
    }

    public function handleRequest(Request $request)
    {
        if (!isset($request['message']['text'])) {
            return;
        }
        $this->chat_id = $request['message']['chat']['id'];
        $this->text = $request['message']['text'];

        $instruction = explode(' ',$this->text);

        $cmd = $instruction[0];
        $arg = count($instruction) > 1 ? $instruction[1] : "";

        switch ($cmd) {
            case '/help':
                $this->showHelp();
                break;
            default:
                break;
        }
    }

    public function showHelp()
    {
        $message = "";

        $message .= "May I help you?" . chr(10);

        $this->sendMessage($message);
    }

    protected function sendMessage($message, $parse_html = false) {
        $data = [
            'chat_id' => $this->chat_id,
            'text' => $message,
        ];

        if ($parse_html) $data['parse_mode'] = 'HTML';

        $this->telegram->sendMessage($data);
    }
}