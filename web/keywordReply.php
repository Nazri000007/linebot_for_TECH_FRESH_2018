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
	//if(stristr($inputStr,'介紹') != false){
	    foreach($basic as $systems){
		    foreach($systems['keyword'] as $check){
			    if(stristr($inputStr, $check) != false){
				    $replyArr = Array();
			
				    foreach($systems['about'] as $message){
				    	switch ($message['type']) {
				    		case 'text':
					    		array_push($replyArr, $builder->text($message['text']));
				    		    break;
                            case 'image':
                                array_push($replyArr, $builder->img($message['url']));
                                break;
					    	case 'carousel':
				    			//array_push($replyArr, $builder->carousel($message['altText'], $message['columns']));
					    	    break;
                            case 'image_carousel':
                                //array_push($replyArr, $builder->image_carousel($message['altText'], $message['columns']));
                                break;
					    }
				    }
				
				return $replyArr;
				break;
			    }
		    }
	    }
	//}
	
	//read external file
	if(stristr($inputStr, '更新與公告') != false) {
		
		$file = fopen("https://www.dropbox.com/s/h9m9lfhj8pvlu8k/updated.txt?dl=1", "r");
		$reply = '';

		while(! feof($file))
		{
			$reply =  $reply.fgets($file);
		}
		fclose($file);
		
		return $builder->text($reply);
	}
	
/*
    //幫我選～～
	if(stristr($inputStr, '選') != false||
		stristr($inputStr, '決定') != false||
		stristr($inputStr, '挑') != false) {
		
		$rplyArr = explode(' ',$inputStr);
    
		if (count($rplyArr) == 1) {return buildTextMessage('選擇的格式不對啦！');}
    
		$Answer = $rplyArr[Dice(count($rplyArr)-1)];
				
		if( Dice(10) ==1){
			$rplyArr = Array(
                 '人生是掌握在自己手裡的',
                 '隨便哪個都好啦',
                 '連這種東西都不能決定，是不是不太應該啊',
                 '不要把這種東西交給'.$keyWord.'決定比較好吧');
		$Answer = $rplyArr[Dice(count($rplyArr)-1)];
		}
    return buildTextMessage('我想想喔……我覺得，'.$Answer.'。');
	}
	else    
	//以下是運勢功能
	if(stristr($inputStr, '運勢') != false){
		$rplyArr=Array('超大吉','大吉','大吉','中吉','中吉','中吉','小吉','小吉','小吉','小吉','凶','凶','凶','大凶','大凶','你還是，不要知道比較好','這應該不關我的事');
		return buildTextMessage('運勢喔…我覺得，'.$rplyArr[Dice(count($rplyArr))-1].'吧。');
	} 
	
    //以下是回應功能
	//讀入文字回應變數
	$content = file_get_contents($textReplyUrl);
	
	//如果失敗就調用預設值
	if ($content === false) {
		$content = file_get_contents('./exampleJson/textReply.json');
	}
	
	//userName會回傳為使用者名稱，如果有辦法取得的話。
	$content = preg_replace("/userName/" , $userName , $content);
	//keyWord會回傳為設定的關鍵字，通常就是機器人的名字。
	$content = preg_replace("/keyWord/" , $keyWord , $content);
	
	
	$content = json_decode($content, true);
		
	foreach($content as $txtChack){
		foreach($txtChack['chack'] as $chack){
	
			if(stristr($inputStr, $chack) != false){
			return buildTextMessage($txtChack['text'][Dice(count($txtChack['text']))-1]);
			break;
			}
		}
	}
	
  //沒有觸發關鍵字則是這個
	
	$rplyArr = $content[0]['text'];
	return buildTextMessage($rplyArr[Dice(count($rplyArr))-1]);
	
}

function SendImg($inputStr,$imgsReplyUrl) {
	
	//讀入圖片回應變數
	$content = file_get_contents($imgsReplyUrl);
	//如果失敗就調用預設值
	if ($content === false) {
		$content = file_get_contents('./exampleJson/imgReply.json');
	}
	
	$content = json_decode($content, true);
		
	
	foreach($content as $ImgChack){
		foreach($ImgChack['chack'] as $chack){
			
			if(stristr($inputStr, $chack) != false){
				
			$imgURL = $ImgChack['img'][Dice(count($ImgChack['img']))-1];
			
			//LINE不支援非加密協定的http://，因此在這裡代換成https://
			$imgURL = str_replace("http:","https:",$imgURL);

			return buildImgMessage($imgURL);
			break;
			}
		}
	}
*/
	return null;
}