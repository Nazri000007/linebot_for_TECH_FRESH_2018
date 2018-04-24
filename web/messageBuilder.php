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

    public function img($originalUrl, $previewUrl){
        error_log("building image message:[url:$originalUrl]");
        $message = array(
            'type' => 'image',
            'originalContentUrl' => $originalUrl,
            'previewImageUrl' => $previewUrl
        );
        return $message;
    }

    public function video($originalUrl, $previewUrl){
        error_log("building video message:[url:$originalUrl]");
        $message = array(
            'type' => 'image',
            'originalContentUrl' => $originalUrl,
            'previewImageUrl' => $previewUrl
        );
        return $message;
    }

    public function sticker($packageId, $stickerId){
        error_log("building sticker message: [packageId:$packageId, stickerId:$stickerId]");
        $message = array(
            'type' => 'sticker',
            'packageId' => $packageId,
            'stickerId' => $stickerId
        );
        return $message;
    }

    public function carousel($altText, $columns){
        error_log("building carousel message: [altText:$altText]");
        $message = array(
            'type'=> 'template',
            'altText'=> $altText,
            'template'=> array(
                'type'=> "carousel",
                'columns'=> $columns
            )
        );
        return $message;
    }

    public function image_carousel($altText, $columns){
        error_log("building image carousel message: [altText:$altText]");
        $message = array(
            'type'=> 'template',
            'altText'=> $altText,
            'template'=> array(
                'type'=> "image_carousel",
                'columns'=> $columns
            )
        );
        return $message;
    }

    public function image_map($baseUrl, $altText, $actions){
        error_log("building image map message: [altText:$altText]");
        $message = array(
            'type'=> 'imagemap',
            'baseUrl'=> $baseUrl,
            'altText'=> $altText,
            'baseSize'=> array(
                'height'=> 1040,
                'width'=> 1040
            ),
            'actions'=> $actions
        );
        return $message;
    }

    public function button($altText, $text, $actions, $imgUrl){
        error_log("building button message: [altText:$altText]");
        $message = array(
            'type'=> 'template',
            'altText'=> $altText,
            'template'=> array(
                'type'=> 'buttons',
                'thumbnailImageUrl'=> $imgUrl,
                'text'=> $text,
                'actions'=> $actions
            )
        );
        return $message;
    }
}
