<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Illuminate\Support\Facades\Log;
use App\Services\Deliverer;
use App\Services\ReplyMessageGenerator;
use App\Services\RequestParser

class LineController extends Controller
{
    // メッセージ送信
    public function delivery()
    {
        // 1. 登録されている友だちにメッセージを送信
        $deliverer = new Deliverer(env('LINE_CHANNEL_ACCESS_TOKEN'),env('LINE_CHANNEL_SECRET'));
        $deliverer->deliverAll('text');

        return response()->json(['message' => 'sent']);
    }

    // メッセージを受け取って返信
    public function callback(Request $request)
    {
        // TODO: ここに具体的に実装

        // 1. 受け取った情報からメッセージの情報を取り出す
        $parser = new RequestParser($request->getContent());
        $recievedMessages = $parser->getRecievedMessages();

        if ($recievedMessages->isEmpty()) {
              return response()->json(['message' => 'received(no events)']);
        }

        $generator = new ReplyMessageGenerator();
        $deliverer = new Deliverer(env('LINE_CHANNEL_ACCESS_TOKEN'),env('LINE_CHANNEL_SECRET'));

        foreach ($recievedMessages as $recievedMessage){
            Text = $event->message->text;

            // 2. 受け取ったメッセージの内容から返信するメッセージを生成
            $replyMessage = $generator->generate($recievedMessage->getText());

            // 3. 返信メッセージを返信先に送信v
            $deliverer->reply($recievedMessage->getReplyToken(),$replyMessage);

        }

        return response()->json(['message' => 'received']);
    }
}

