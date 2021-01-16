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
            case '/assistance':
                $this->showAssistance();
                break;
            case '/compliment':
                $this->showCompliment();
                break;
            case '/pet':
                $this->showPet();
                break;
            case '/dice':
                $this->showDice($arg);
                break;
            default:
                break;
        }
    }

    public function showAssistance()
    {
        $message = "";

        $message .= "May I assist you?" . chr(10);

        $this->sendMessage($message);
    }

    public function showCompliment()
    {
        $message = "";

        $message .= "My most sincere appreciation" . chr(10);

        $this->sendMessage($message);
    }

    public function showPet()
    {
        $message = "";

        $message .= "If that is what you need, please continue" . chr(10);

        $this->sendMessage($message);
    }

    public function showDice($type = null)
    {
        switch ($type) {
            case 'd4':
                $roll = rand(1, 4);
                break;
            case 'd6':
                $roll = rand(1, 6);
                break;
            case 'd8':
                $roll = rand(1, 8);
                break;
            case 'd10':
                $roll = rand(1, 10);
                break;
            case 'd12':
                $roll = rand(1, 12);
                break;
            case 'd20':
                $roll = rand(1, 20);
                break;
            default:
                $roll = "I'm afraid I require you to instruct me about the variety of die you need me to handle before I can assist you";
                break;
        }

        $this->sendMessage($roll, true);
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