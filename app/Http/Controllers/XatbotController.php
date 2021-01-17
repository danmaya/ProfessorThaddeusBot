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
            case '/assistance@ProfessorThaddeusBot':
                $this->showAssistance();
                break;
            case '/compliment':
            case '/compliment@ProfessorThaddeusBot':
                $this->showCompliment();
                break;
            case '/pet':
            case '/pet@ProfessorThaddeusBot':
                $this->showPet();
                break;
            case '/dice':
            case '/dice@ProfessorThaddeusBot':
                $this->showDice($arg);
                break;
            case '/guidance':
            case '/guidance@ProfessorThaddeusBot':
                $this->showGuidance();
                break;
            case '/suera':
            case '/suera@ProfessorThaddeusBot':
                $this->showSuera();
                break;
            case '/sueras':
            case '/sueras@ProfessorThaddeusBot':
                $this->showSueras();
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
            case 'D4':
                $roll = rand(1, 4);
                break;
            case 'd6':
            case 'D6':
                $roll = rand(1, 6);
                break;  
            case 'd8':
            case 'D8':
                $roll = rand(1, 8);
                break;
            case 'd10':
            case 'D10':
                $roll = rand(1, 10);
                break;
            case 'd12':
            case 'D12':
                $roll = rand(1, 12);
                break;
            case 'd20':
            case 'D20':
                $roll = rand(1, 20);
                break;
            default:
                $roll = "I am afraid I require you to instruct me about the variety of die you need me to handle before I can assist you";
                break;
        }

        $this->sendMessage($roll, true);
    }

    public function showGuidance()
    {
        $array = array("Most likely", "I don't think so", "I am not certain", "Without a doubt", "I think so", "I am certain", "I am doubtful", "Indeed");

        $answer = $array[array_rand($array)] . chr(10);

        $this->sendMessage($answer);
    }

    public function showSuera()
    {
        $message = "";

        $message .= "Hmm? You wish you know about a place called Suera? My apologies, I am afraid I don't have any records of such place" . chr(10);

        $this->sendMessage($message);
    }

    public function showSueras()
    {
        $message = "";

        $message .= "Hmm? You wish you know about a place called Sueras? My apologies, I am afraid I don't have any records of such place" . chr(10);

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