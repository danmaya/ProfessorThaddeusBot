<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use Telegram\Bot\Api;

class XatbotController extends Controller
{
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
}