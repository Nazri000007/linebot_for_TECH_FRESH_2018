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

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$builder = new messageBuilder();

foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message'://received message
            $message = $event['message'];
            $source = $event['source'];

            //get display name of the user
            if ($source['type'] == "user"){
                $username = $client->getProfile($source['userId'])['displayName'];
                error_log("received message sent from $username");
            } else
                $username = "";

            //filter message types
            switch ($message['type']) {
                case 'text':
                	error_log("the message was: ".$message['text']);
                	if($message['text']!="")
                	{
                		$client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages'=> array (
                                $builder->text("Did you say"),
                                $builder->text($message['text']."?")
                                )
                    	    )
                        );
                		error_log(print_r(KeyWordReply($message['text'], $username), true));
                        $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages'=> KeyWordReply($message['text'], $username)
                            )
                        );
                	}
                    break;

            }
            break;

        case 'follow'://added as friend
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

            $client->replyMessage(
                array(
                    'replyToken' => $event['replyToken'],
                    'messages' => Array(
                            $builder->text($username."你好！我是賴念群 Larry 創造的機器人。\n想要多認識 Larry 的話可以問我哦！"),
                            $builder->sticker(3,225),
                            $builder->text("建議使用手機界面，可以更簡單地選取指令。"),
                            $builder->button($basic[0]['altText'], $basic[0]['imgUrl'], $basic[0]['text'], $basic[0]['actions'])
                    )
                )
            );
            break;

        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};