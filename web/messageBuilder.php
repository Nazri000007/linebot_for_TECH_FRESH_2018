<?php
/**
 * Created by PhpStorm.
 * User: larrylai
 * Date: 2018/4/23
 * Time: 14:46
 */

//建立複數訊息，的物件
class messageBuilder{

    public function text($inputStr){
        settype($inputStr, "string");
        error_log("building text message:[$inputStr]");
        $message = array(
            'type' => 'text',
            'text' => $inputStr
        );
        return $message;
    }

    public function img($inputStr){
        settype($inputStr, "string");
        error_log("building image message:[url:$inputStr]");
        $message = array(
            'type' => "image",
            'originalContentUrl' => $inputStr,
            'previewImageUrl' => $inputStr
        );
        return $message;
    }

    public function sticker($packageId, $stickerId){
        error_log("building sticker message: [packageId:$packageId, stickerId:$stickerId]");
        $message = array(
            'type' => "sticker",
            'packageId' => $packageId,
            'stickerId' => $stickerId
        );
        return $message;
    }

    public function carousel($altText, $columns){
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
}
