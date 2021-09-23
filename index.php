<?php
require_once 'vendor/autoload.php';

use function App\config\loaders\bootstrap;
use function Telegram\key\getKey;
use Connect\Connect;

$connect = new Connect();
$connect->parseStr();
