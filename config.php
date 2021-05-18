<?
if (!defined('verify')) { echo "Forbidden Request"; exit; }

global $config;
$config['api_key'] = 'Your bot token';
$config['base'] = 'https://api.telegram.org/bot'.$config['api_key'].'/';