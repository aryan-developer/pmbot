<?php
require_once "Telegram.php";
$telegram = new Telegram(""); //توکن
define("admin", ""); //آیدی عددی ادمین
define("user_id", $telegram->ChatID());
define("type", $telegram->getUpdateType());
define('dontPrivate', $telegram->messageFromGroup());
define('message_id', $telegram->MessageID());
define('data', $telegram->getData());
define('text', $telegram->Text());
if (dontPrivate) {
    die();
}
if (!file_exists("users/" . user_id)) {
    writeToFile("users/" . user_id, "no");
}
if (file_get_contents("users/" . user_id, true) == "yes") {
    $telegram->sendMessage([
        "chat_id" => user_id,
        "reply_to_message_id" => message_id,
        "text" => 'شما دسترسی استفاده را ندارید!'
    ]);
    die();
}
if (admin == user_id) {
    if (text == "/panel" or text == "/start" or text == "بازگشت") {
        $option = array(
            array(
                $telegram->buildKeyboardButton("ارسال پیام به یکی"),
                $telegram->buildKeyboardButton("ارسال پیام به همه")
            ), array(
                $telegram->buildKeyboardButton("بن"),
                $telegram->buildKeyboardButton("آن بن")
            ),
        );
        $keyb = $telegram->buildKeyBoard($option, $onetime = false);
        $telegram->sendMessage([
            'chat_id' => user_id,
            'reply_markup' => $keyb,
            'text' => "خب مدیر👨🏻‍💻\nچیکار میخوای بکنی",
            "reply_to_message_id" => message_id
        ]);
    } elseif (text == 'بن') {
        writeToFile("users/" . user_id, "ban");
        $option = array(
            array(
                $telegram->buildKeyboardButton("بازگشت"),
            ),
        );
        $keyb = $telegram->buildKeyBoard($option, $onetime = false);
        $telegram->sendMessage([
            'chat_id' => user_id,
            'reply_markup' => $keyb,
            'text' => "خب مدیر👨🏻‍💻\nآیدی عددی کاربر رو بفرست",
            "reply_to_message_id" => message_id
        ]);
    } elseif (text == 'آن بن') {
        writeToFile("users/" . user_id, "unBan");
        $option = array(
            array(
                $telegram->buildKeyboardButton("بازگشت"),
            ),
        );
        $keyb = $telegram->buildKeyBoard($option, $onetime = false);
        $telegram->sendMessage([
            'chat_id' => user_id,
            'reply_markup' => $keyb,
            'text' => "خب مدیر👨🏻‍💻\nآیدی عددی کاربر رو بفرست",
            "reply_to_message_id" => message_id
        ]);
    } elseif (text == 'ارسال پیام به همه') {
        writeToFile("users/" . user_id, "sendAll");
        $option = array(
            array(
                $telegram->buildKeyboardButton("بازگشت"),
            ),
        );
        $keyb = $telegram->buildKeyBoard($option, $onetime = false);
        $telegram->sendMessage([
            'chat_id' => user_id,
            'reply_markup' => $keyb,
            'text' => "خب مدیر👨🏻‍💻\nپیام رو بفرست",
            "reply_to_message_id" => message_id
        ]);
    } elseif (text == 'ارسال پیام به یکی') {
        writeToFile("users/" . user_id, "send");
        $option = array(
            array(
                $telegram->buildKeyboardButton("بازگشت"),
            ),
        );
        $keyb = $telegram->buildKeyBoard($option, $onetime = false);
        $telegram->sendMessage([
            'chat_id' => user_id,
            'reply_markup' => $keyb,
            'text' => "خب مدیر👨🏻‍💻\nآیدی عددی رو بفرست",
            "reply_to_message_id" => message_id
        ]);
    } else {
        $step = file_get_contents("users/" . user_id, true);
        if ($step == "send") {
            writeToFile("users/" . user_id, "getMsg");
            writeToFile("users/" . user_id . ".id", text);
            $option = array(
                array(
                    $telegram->buildKeyboardButton("بازگشت"),
                ),
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime = false);
            $telegram->sendMessage([
                'chat_id' => user_id,
                'reply_markup' => $keyb,
                'text' => "خب مدیر👨🏻‍💻\nپیام رو بفرست",
                "reply_to_message_id" => message_id
            ]);
        } elseif ($step == "getMsg") {
            writeToFile("users/" . user_id, "none");
            $option = array(
                array(
                    $telegram->buildKeyboardButton("ارسال پیام به یکی"),
                    $telegram->buildKeyboardButton("ارسال پیام به همه")
                ), array(
                    $telegram->buildKeyboardButton("بن"),
                    $telegram->buildKeyboardButton("آن بن")
                ),
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime = false);
            $telegram->sendMessage([
                'chat_id' => file_get_contents("users/" . user_id . ".id" , true),
                'text' => "پیام از سمت مدیریت \r\n\n".text,
            ]);
            $telegram->sendMessage([
                'chat_id' => user_id,
                'reply_markup' => $keyb,
                'text' => "پیام ارسال شد!\nخب میخای چیکار کنی",
                "reply_to_message_id" => message_id
            ]);
            unlink("users/" . user_id . ".id");
        } elseif ($step == "ban") {
            writeToFile("users/" . user_id, "none");
            $option = array(
                array(
                    $telegram->buildKeyboardButton("ارسال پیام به یکی"),
                    $telegram->buildKeyboardButton("ارسال پیام به همه")
                ), array(
                    $telegram->buildKeyboardButton("بن"),
                    $telegram->buildKeyboardButton("آن بن")
                ),
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime = false);
            $telegram->sendMessage([
                'chat_id' => user_id,
                'reply_markup' => $keyb,
                'text' =>"کاربر بن شد!\nخب میخای چیکار کنی",
                "reply_to_message_id" => message_id
            ]);
            $telegram->sendMessage([
                'chat_id' =>text,
                'text' => "شما از سمت مدیریت بن شدید",
            ]);
            writeToFile("users/".text , "yes");
        }elseif ($step == "unBan") {
            writeToFile("users/" . user_id, "none");
            $option = array(
                array(
                    $telegram->buildKeyboardButton("ارسال پیام به یکی"),
                    $telegram->buildKeyboardButton("ارسال پیام به همه")
                ), array(
                    $telegram->buildKeyboardButton("بن"),
                    $telegram->buildKeyboardButton("آن بن")
                ),
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime = false);
            $telegram->sendMessage([
                'chat_id' => user_id,
                'reply_markup' => $keyb,
                'text' =>"کاربر آن بن شد👨🏻‍💻\nخب میخوای چیکار کنی",
                "reply_to_message_id" => message_id
            ]);
            $telegram->sendMessage([
                'chat_id' =>text,
                'text' => "شما از سمت مدیریت آن بن شدید",
            ]);
            writeToFile("users/".text , "no");
        }elseif ($step == "sendAll") {
            writeToFile("users/" . user_id, "none");
            $option = array(
                array(
                    $telegram->buildKeyboardButton("ارسال پیام به یکی"),
                    $telegram->buildKeyboardButton("ارسال پیام به همه")
                ), array(
                    $telegram->buildKeyboardButton("بن"),
                    $telegram->buildKeyboardButton("آن بن")
                ),
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime = false);
            $telegram->sendMessage([
                'chat_id' => user_id,
                'reply_markup' => $keyb,
                'text' =>"پیام به همه ارسال شد👨🏻‍💻\nخب میخوای چیکار کنی",
                "reply_to_message_id" => message_id
            ]);
            $path = 'users/';
            $files = scandir($path);
            foreach ($files as $file){
                $telegram->sendMessage([
                    'chat_id' =>$file,
                    'text' => "پیام از سمت مدیریت \r\n\n" . text,
                ]);
            }
        }else{
            $option = array(
                array(
                    $telegram->buildKeyboardButton("ارسال پیام به یکی"),
                    $telegram->buildKeyboardButton("ارسال پیام به همه")
                ), array(
                    $telegram->buildKeyboardButton("بن"),
                    $telegram->buildKeyboardButton("آن بن")
                ),
            );
            $keyb = $telegram->buildKeyBoard($option, $onetime = false);
            $telegram->sendMessage([
                'chat_id' => user_id,
                'reply_markup' => $keyb,
                'text' =>"خب میخوای چیکار کنی",
                "reply_to_message_id" => message_id
            ]);
        }
    }
}else{
    if(text == "/start"){
        $telegram->sendMessage([
            'chat_id' => user_id,
            'text' => "سلام\nپیامتو بفرست",
            "reply_to_message_id" => message_id
        ]);
    }else{
        $telegram->sendMessage([
            'chat_id' => user_id,
            'text' =>"پیام به مدیر ارسال شد",
            "reply_to_message_id" => message_id
        ]);
        $telegram->sendMessage([
            'chat_id' => admin,
            'parse_mode' => "MarkdownV2",
            'text' =>"user id : `".user_id."`\n\nپیام :\n".text ,
        ]);

    }
}

function writeToFile($path, $data)
{
    $f = fopen($path, "w");
    fwrite($f, $data);
    fclose($f);
}
