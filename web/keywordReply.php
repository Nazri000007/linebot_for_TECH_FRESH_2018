<?php

require_once('./messageBuilder.php');

function KeyWordReply($inputStr,$userName) {
	$inputStr = strtolower($inputStr);

	//get basic replies form json file
	$content = file_get_contents('./JSONs/basicReply.json');
	//replace userName in the file
	$content = preg_replace("/userName/" , $userName , $content);
	$basic = json_decode($content, true);

    $builder = new messageBuilder();

	//begin keywords replying
	    foreach($basic as $systems){
		    foreach($systems['keyword'] as $check){
			    if(stristr($inputStr, $check) != false){
				    $replyArr = array();
			
				    foreach($systems['about'] as $message){
				    	switch ($message['type']) {
				    		case 'text':
					    		array_push($replyArr, $builder->text($message['text']));
				    		    break;
                            case 'image':
                                array_push($replyArr, $builder->img($message['originalContentUrl'], $message['previewImageUrl']));
                                break;
                            case 'video':
                                array_push($replyArr, $builder->video($message['originalContentUrl'], $message['previewImageUrl']));
                                break;
					    	case 'carousel':
				    			array_push($replyArr, $builder->carousel($message['altText'], $message['columns']));
					    	    break;
                            case 'image_carousel':
                                array_push($replyArr, $builder->image_carousel($message['altText'], $message['columns']));
                                break;
                            case 'image_map':
                                array_push($replyArr, $builder->image_map($message['baseUrl'], $message['altText'], $message['actions']));
                                break;
                            case 'button':
                                array_push($replyArr, $builder->button($message['altText'], $message['text'], $message['actions'], null));
                                break;
					    }
				    }
				
				return $replyArr;
				break;
			    }
		    }
	    }
	return null;
}