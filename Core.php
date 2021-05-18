<?
require_once 'config.php';

class Core
{
    private $url;
    private $sendMessage = "sendMessage?";
    private $sendPhoto = "sendPhoto?";
    private $forwardMessage = "forwardMessage?";
    private $sendVideo = "sendVideo?";
    private $sendVoice = "sendVoice?";

    public $senderArray = array();

    public function __construct($input)
    {
        global $config;
        $this->url = $config['base'];
        $decodedString = json_decode($input, true);
        $this->senderArray = array(
            'user_id' => $decodedString['message']['from']['id'],
            'username' => $decodedString['message']['from']['username'],
            'message_id' => $decodedString['message']['message_id'],
            'chat_id' => $decodedString['message']['chat']['id'],
            'first_name' => $decodedString['message']['from']['first_name'],
            'reply_to_message' => $decodedString['message']['reply_to_message']['forward_from']['id'],
            'type' => [
                'text' => $decodedString['message']['text'],
                'voice' => $decodedString['message']['voice']['file_id'],
                'photo' => $decodedString['message']['photo'][0]['file_id'],
                'video' => $decodedString['message']['video'][0]['file_id']
            ],
            'caption' => $decodedString['message']['caption'],
        );
    }

    function sendMessage($chat_id, $text, $message_id = null, $reply_markup = null)
    {
        return $this->response($this->sendMessage . 'chat_id=' . $chat_id . '&text=' . $text . '&parse_mode=html' .
            (is_null($message_id) ? '' : '&reply_to_message_id=' . $message_id) . (is_null($reply_markup) ? '' : '&reply_markup=' . json_encode($reply_markup)));
    }

    function sendPhoto($chat_id, $photo, $caption = null)
    {
        return $this->response($this->sendPhoto . 'chat_id=' . $chat_id . '&photo=' . $photo . (is_null($caption) ? '' : '&caption=' . $caption));
    }

    function sendVideo($chat_id, $video, $caption = null)
    {
        return $this->response($this->sendVideo . 'chat_id=' . $chat_id . '&video=' . $video .
            (is_null($caption) ? '' : '&caption=' . $caption));
    }

    function sendVoice($chat_id, $voice)
    {
        return $this->response($this->sendVoice . 'chat_id=' . $chat_id . '&voice=' . $voice . '&disable_notification = false');
    }

    function forwardMessage($chat_id, $from_chat_id, $message_id)
    {
        return $this->response($this->forwardMessage . 'chat_id=' . $chat_id . '&from_chat_id=' . $from_chat_id . '&message_id=' . $message_id);
    }

    private function response($input)
    {
        return file_get_contents($this->url . $input);
    }
}

