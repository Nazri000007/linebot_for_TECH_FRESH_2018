<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');
require_once('./keywordReply.php');
require_once('./messageBuilder.php');

$channelAccessToken = getenv('LINE_CHANNEL_ACCESSTOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');
$richmenuId = "richmenu-5c6408345e017060bc67765a757167f2";

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$builder = new messageBuilder();

foreach ($client->parseEvents() as $event) {

    $source = $event['source'];
    //get display name of the user
    if ($source['type'] == "user"){
        $username = $client->getProfile($source['userId'])['displayName'];
    } else
        $username = "";


    switch ($event['type']) {
        case 'message'://received message
            error_log("message event");
            $message = $event['message'];

            error_log("received message sent by $username");

            //filter message types
            switch ($message['type']) {
                case 'text':
                	$msg = KeyWordReply($message['text'], $username);
                	//error_log(print_r($msg,true));
                	if($message['text']!="")
                	{
                	    if ($msg == null){
                            $client->replyMessage(array(
                                    'replyToken' => $event['replyToken'],
                                    'messages' => array(
                                        $builder->text("抱歉，我不懂[".$message['text']."]的意思，請您使用預設的選單哦！"),
                                        $builder->sticker(2,153)
                                    )
                                )
                            );
                            error_log("from $username, unsupported message: ".$message['text']);
                        }else {
                            $client->replyMessage(array(
                                    'replyToken' => $event['replyToken'],
                                    'messages' => $msg
                                )
                            );

                            error_log("from $username, message: ".$message['text']);
                        }
                	}
                    break;

            }
            break;

        case 'follow'://added as friend
            error_log("follow event");

            if (linkToUser($channelAccessToken, $source['userId'], $richmenuId) == 'success')
                error_log("richmenu added successfully for $username");
            else
                error_log("adding richmenu failed for $username");

            $source = $event['source'];
            //get display name of the user
            if ($source['type'] == "user"){
                $username = $client->getProfile($source['userId'])['displayName'];
                error_log("added as friend by $username");
            } else
                $username = "";

            //get basic replies form json file
            $content = file_get_contents('./JSONs/basicReply.json');
            $basic = json_decode($content, true);

            $client->replyMessage(array(
                    'replyToken' => $event['replyToken'],
                    'messages' => array(
                            $builder->text($username."你好！我是 Larry 創造的機器人。\n想要多認識 Larry 的話可以問我哦！"),
                            $builder->sticker(3,225),
                            $builder->text("建議使用手機界面，可以更簡單地選取指令。"),
                            $builder->button($basic[0]['altText'],  $basic[0]['text'], $basic[0]['actions'], $basic[0]['imgUrl'])
                    )
                )
            );
            break;

        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};

function linkToUser($channelAccessToken, $userId, $richmenuId) {
    $sh = <<< EOF
    curl -v -X POST https://api.line.me/v2/bot/user/$userId/richmenu/$richmenuId \
    -H "Authorization: Bearer $channelAccessToken" \
    -H 'Content-Length: 0'
EOF;
    $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
    if(isset($result['message'])) {
        return $result['message'];
    }
    else {
        return 'success';
    }
}