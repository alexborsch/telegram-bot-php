<?php
/********************************************************
*  For the script to work, 
*  you need to install this library:
*  https://github.com/irazasyed/telegram-bot-sdk
*  using composer. 
*  Command to install the library using Composer:
*  composer require irazasyed/telegram-bot-sdk ^2.0
*
*  https://coderlog.top
*  https://youtube.com/CoderLog
********************************************************/
include('vendor/autoload.php');
include('menu.php');
include('settings.php');
include('bot_lib.php');
use Telegram\Bot\Api;

$telegram = new Api($api);
$result = $telegram->getWebhookUpdates();

$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$name = $result["message"]["from"]["username"];
$first_name = $result["message"]["from"]["first_name"];
$last_name = $result["message"]["from"]["last_name"];
$get_user = get_user($connect, $chat_id);
$old_id = $get_user['chat_id'];
$username = $first_name . ' ' . $last_name;



if($text == "/start"){
	$reply = "Menu: ";
	$reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $menu, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
}elseif($text == "button 1"){
    $img = 'img_url';
	$reply = "Hello " . $first_name . " " . $last_name;
	$reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $menu, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
	$telegram->sendPhoto(['chat_id' => $chat_id, 'photo' => $img, 'caption' => $reply, 'parse_mode' => 'HTML']);
}elseif($text == "button 2"){
	$reply = "Hello " . $first_name . " " . $last_name . " it's button 2";
	$reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $menu2, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
	$telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup]);
}elseif ($text == "Google News") {
   
$reply = "Наука и технологии: \n\n";
    $xml=simplexml_load_file('https://news.google.com/rss/topics/CAAqKAgKIiJDQkFTRXdvSkwyMHZNR1ptZHpWbUVnSnlkUm9DVlVFb0FBUAE?hl=ru&gl=UA&ceid=UA%3Aru');
    $i = 0;
    foreach ($xml->channel->item as $item) {
        $i++;
        if($i > 10){
            break;
        }
        $reply .= "\xE2\x9E\xA1 ".$item->title."\nДата: ".$item->pubDate."(<a href='".$item->link."'>Читать полностью</a>)\n\n";
    }
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode' => 'HTML', 'disable_web_page_preview' => true, 'text' => $reply ]);
}elseif($text == "Inline"){
    $reply = "Inline keyboard";
    $inline[] = ['text'=>'CoderLog', 'url'=>'https://coderlog.top'];
    $inline[] = ['text'=>'CoderLog Chat', 'url'=>'https://t.me/coderlog_channel'];
    $inline = array_chunk($inline, 2);
    $reply_markup = ['inline_keyboard'=>$inline];
    $inline_keyboard = json_encode($reply_markup);
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $inline_keyboard]);
}


add_user($connect, $username, $chat_id, $name, $old_id);
textlog($connect, $chat_id, $text);