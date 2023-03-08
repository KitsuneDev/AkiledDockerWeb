<?php
// Setup Env
$_SERVER['DOCUMENT_ROOT'] = '/usr/share/nginx/html';
define('BRAIN_CMS', 1);	
include_once $_SERVER['DOCUMENT_ROOT'].'/global.php';
// Setup Param
$_POST['register'] = '';
$_POST['username'] = 'test';
$_POST['password']  ='ara';
$_POST['password_repeat']='ara';
$_POST['email'] = 'a@b.com';
$_POST['code'] = '1234';
$_POST['code_bueno'] = '1234';
$_POST['dbg'] = 'true';
User::register();