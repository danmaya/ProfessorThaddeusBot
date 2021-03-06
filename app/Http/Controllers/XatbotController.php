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
            case '/recipes':
            case '/recipes@ProfessorThaddeusBot':
                $this->showRecipes($arg);
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

        $message .= "Pleased to make your acquaintance.\nMy name is Professor Thaddeus, I will be glad to assist you in matters of knowledge.\n\nThese are my currently available services:\n/assistance - Inquires me about my available services.\n/compliment - Presents your compliments to me for my services.\n/pet - Caresses my plumage in order to please your needs.\n/dice - Appeals me to roll a die from the ones available (D4, D6, D8, D10, D12 and D20).\n/guidance - Requests me guidance on a matter of your choice.\n/recipes - Petitions Professor Thaddeus to inform you about recipes containing a specific ingredient." . chr(10);

        $this->sendMessage($message);
    }

    public function showCompliment()
    {
        $array = array("My most sincere appreciation.", "I am most grateful.", "I am very much obliged.", "My most sincere gratitude.");

        $answer = $array[array_rand($array)] . chr(10);

        $this->sendMessage($answer);
    }

    public function showPet()
    {
        $array = array("If that is what you need, please continue.", "Hoot-hoo-ahem, my apologies, I may have got carried away.", "Curious, but please, proceed.", "I could get accustomed to this.");

        $answer = $array[array_rand($array)] . chr(10);

        $this->sendMessage($answer);
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
                $roll = "I am afraid I require you to instruct me about the variety of die you need me to handle before I can assist you.";
                break;
        }

        $this->sendMessage($roll, true);
    }

    public function showGuidance()
    {
        $array = array("Most likely.", "I don't think so.", "I am not certain.", "Without a doubt.", "I think so.", "I am certain.", "I am doubtful.", "Indeed.");

        $answer = $array[array_rand($array)] . chr(10);

        $this->sendMessage($answer);
    }

    public function showRecipes($ingredient = null)
    {
        $message = "";

        if ($ingredient == null) {

            $message .= "My apologies, but I require you to instruct me about the ingredient you need me to look recipes of before I can assist you.";

        } else {

            $data = file_get_contents("http://www.recipepuppy.com/api/?i=" . $ingredient);

            if (json_decode($data, true)["results"] != null) {

                $title = json_decode($data, true)["results"][rand(0, 9)]["title"];
                $ingredients = json_decode($data, true)["results"][rand(0, 9)]["ingredients"];
                $href = json_decode($data, true)["results"][rand(0, 9)]["href"];

                $message .= "<b>Name:</b> " . $title . "\n" . "<b>Ingredients:</b> " . $ingredients . "\n" . "<b>Link:</b> " . $href . chr(10);

            } else {

                $message .= "My apologies, but there do not seem to be any recipes with that ingredient, may I suggest you try with another one?" . chr(10);

            }

        }

        $this->sendMessage($message, true);
    }

    public function showSuera()
    {
        $message = "";

        $message .= "Hmm? You wish you know about a place called Suera? My apologies, I am afraid I don't have any records of such place." . chr(10);

        $this->sendMessage($message);
    }

    public function showSueras()
    {
        $message = "";

        $message .= "Hmm? You wish you know about a place called Sueras? My apologies, I am afraid I don't have any records of such place." . chr(10);

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