<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BRAIN_CMS', 1);
include_once $_SERVER['DOCUMENT_ROOT'].'/global.php';
echo "<h1>REGISTER test2<h1></h1>";
$_POST['register'] = '';
$_POST['username'] = 'test2';
$_POST['password']  ='araara';
$_POST['password_repeat']='araara';
$_POST['email'] = 'a@b.com';
$_POST['code'] = '1234';
$_POST['codebueno'] = '1234';
$_POST['dbg'] = 'true';
User::register();

echo "<h1>IP2<h1></h1>";
echo (userIp());
echo "<h1>SERVER<h1></h1>";
echo (var_dump($_SERVER));