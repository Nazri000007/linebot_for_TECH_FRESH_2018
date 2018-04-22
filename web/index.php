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

$channelAccessToken = getenv('LINE_CHANNEL_ACCESSTOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');
$jsonUrl = getenv('JSON_URL');

function buildTextMessage($inputStr){
    settype($inputStr, "string");
    error_log(" building text message:[$inputStr]");
    $message = array
    (
        array(
            'type' => 'text',
            'text' => $inputStr
        )
    );
    return $message;
}

function buildImgMessage($inputStr){
    settype($inputStr, "string");
    error_log(" building image message:[url:$inputStr]");
    $message = array
    (
        array(
            'type' => "image",
            'originalContentUrl' => $inputStr,
            'previewImageUrl' => $inputStr
        )
    );
    return $message;
}

function buildStickerMessage($packageId, $stickerId){
    error_log("building sticker message: [packageId:$packageId, stickerId:$stickerId]");
    $message = array
    (
        array(
            'type' => "sticker",
            'packageId' => $packageId,
            'stickerId' => $stickerId
        )
    );
    return $message;
}

function buildCarouselMessage($altText, $columns){
    error_log("building carousel message");
    $message = array(
        'type'=> "template",
        'altText'=> $altText,
        'template'=> array(
            'type'=> "carousel",
            'columns'=> $columns
        )
    );
    return $message;
}

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            $source = $event['source'];

            if ($source['type'] == "user"){
                $username = $client->getProfile($source['userId'])['displayName'];
                error_log("message is sent from $username");
            }

            switch ($message['type']) {
                case 'text':
                	$m_message = $message['text'];
                	if($m_message!="")
                	{
                		$client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $message['text']
                            )
                        )
                    	));
                	}
                    break;

            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};