<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class Deliverer {
    private $bot;

    public function __construct($accessToken, $secret)
        {
            $httpClient = new CurlHTTPClient($accessToken);
            $this->bot = new LINEBot($httpClient, ['channelSecret' => $secret]);
        }

    public function deliverAll($message){
        $messageBuilder=new TextMessageBuilder($message);
        $response = $this->bot->broadcast($messageBuilder);

        if(!$response->isSucceded()){
            Log::error($response->getRawBody());
        }
    }

    public function reply($replyToken, $message){
         $messageBuilder = new TextMessageBuilder($message);
         $response = $this->bot ->replyMessage($replyToken, $messageBuilder);

        if(!$response->isSucceded()){
            Log::error($response->getRawBody());
        }
    }
}