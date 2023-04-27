<?php

namespace App\Services;

use App\Models\RecievedMessage;
use Illuminate\Support\Collection;

class RequestParser {
    private $content;

    public function __construct(string $content)
    {
        $this->content = json_decode($content->getContent());
    }

    public function getRecievedMessages(): Collection
    {
        $recievedMessages = new Collection();

        if (is_null($this->content) || is_null($this->content->events)) {
              return $recievedMessages;
        }

        foreach ($this->content->events as $event){
            if($event->type !== 'message'){
                continue;
             }
             if($event->message->type !== 'text'){
                 continue;
             }

             $replyToken = $event->replyToken;
             $messageText = $event->message->text;

             $recievedMessages->add(new RecievedMessage($replyToken,$messageText));
        }
        return $recievedMessages;
    }
}