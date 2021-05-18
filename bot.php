<?
define('verify', true);
require_once 'config.php';
require_once 'Core.php';

$var = file_get_contents("php://input");
$core = new Core($var);
file_put_contents('result.txt', $var);
$group_chat_id = -1001203038873;

if ($core->senderArray['chat_id'] != $group_chat_id) {
    switch ($core->senderArray['type']['text']) {
        case '/start':
            $welcomeMessage = urlencode("سلام " . $core->senderArray['first_name'] . "
            حالت چطوره ؟!😉😍\n
            💡خب هرچی سوال داری سعی کن در قالب یک پیام بفرستی و ما در بهترین حالت جوابتو میدیم \n
🔴فقط تا زمانی که برات جوابو نفرستادیم رباتو بلاک یا تاریخچرو پاک نکن چون دیگه پیام مارو نمیبینی !!            ");
            $keyboard = array("keyboard" => array(array("ارسال سوال❓")), "resize_keyboard" => true, "one_time_keyboard" => true);
            $core->sendMessage($core->senderArray['user_id'], $welcomeMessage, null, $keyboard);
            break;
        case 'ارسال سوال❓':
            $core->sendMessage($core->senderArray['user_id'], "سوال خود را ارسال کنید : ", null, array(
                'remove_keyboard' => true
            ));
            break;
        default:
            if ($core->senderArray['chat_id'] != $group_chat_id) {
                $encodeForwardedMessage = $core->forwardMessage($group_chat_id, $core->senderArray['chat_id'], $core->senderArray['message_id']);
                $decodedMessage = json_decode($encodeForwardedMessage, true);
                $core->sendMessage($group_chat_id, 'سوال جدیدی از کاربر ' . $core->senderArray['first_name'] . ' با آی دی @' . $core->senderArray['username'] . ' ثبت شد.', $decodedMessage['result']['message_id']);
            }
            $core->sendMessage($core->senderArray['user_id'], "سوال شما ارسال شد و در اسرع وقت پاسخ داده می شود.❤️");
    }
} else {
    if ($core->senderArray['reply_to_message'] != null && $core->senderArray['chat_id'] === $group_chat_id) {
        $sentMessage = $core->senderArray['type'];
        switch ($sentMessage) {
            case !is_null($sentMessage['text']):
                $core->sendMessage($core->senderArray['reply_to_message'], $sentMessage['text']);
                break;
            case !is_null($sentMessage['photo']):
                $core->sendPhoto($core->senderArray['reply_to_message'], $sentMessage['photo'], $core->senderArray['caption']);
                break;
            case !is_null($sentMessage['video']):
                $core->sendVideo($core->senderArray['reply_to_message'], $sentMessage['video'], $core->senderArray['caption']);
                break;
            case !is_null($sentMessage['voice']):
                $core->sendVoice($core->senderArray['reply_to_message'], $sentMessage['voice']);
                break;
        }
    }
}