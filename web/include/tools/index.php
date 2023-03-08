<!DOCTYPE html><html><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>New WinNMP Project: <?=$_SERVER['SERVER_NAME']?></title>
		<meta charset="utf-8">
		<meta http-equiv="Content-Language" content="en">
		<meta name="language" content="en">
		<link href="//localhost/tools/style.css" rel="stylesheet" type="text/css">
	</head><body>
		<h1>New WinNMP Project: <?=$_SERVER['SERVER_NAME']?></h1>
		<p>This is a new WinNMP Project<br>
		<strong>Feel free to modify / overwrite this file!</strong><br>
		Read <a href="https://winnmp.wtriple.com/howtos.php">WinNMP Basic Usage - Getting Started</a>
		</p>
		
		<hr>
		<h4>MySql Database Connection:</h4>
		<?php
		$conn=new mysqli('p:localhost');
		$db='mysql';
		if (!$conn->connect_errno) {
			$conn->set_charset('utf8');
			$user=ini_get('mysqli.default_user');
			if ($user!='root') $db=$user;
			if ($conn->select_db($db)) {
				$mysqlVersion=$conn->get_server_info();
				echo "<p class='good'>Success, <b>MySql server</b> version $mysqlVersion</p>";
			} else echo "<p class='bad'>Unable to select database <b><?=$db?></b></p>";
		} else echo "<p class='bad'>Unable to connect to <b>MySql server</b></p>";
		?>
		<pre>
			$conn=new mysqli('p:localhost');
			$conn->set_charset('utf8');
			$conn->select_db('<?=$db?>');
		</pre>
		<p><small>You don`t need to specify the mysql username/password, they are stored in php.ini. You could allso connect using:</small></p>
		<pre>
			$DB_USER = '<?=$user?>';	//ini_get('mysqli.default_user');
			$DB_PASS = '';		//ini_get('mysqli.default_pw');
			$dbh = new PDO('mysql:host=localhost;dbname=<?=$db?>', $DB_USER, $DB_PASS);
		</pre>
		<hr>

		<h4>Redis Cache / NoSql Connection:</h4>
		<?php
		if (class_exists("Redis")) {
			$redis=new Redis();
			$addr=empty($_SERVER['REDIS']) ? 'localhost' : $_SERVER['REDIS'];
			try {
				if ($redis->connect($addr, null, 0.03)) {
						$infoRedis=$redis->info();
						$redisVersion=$infoRedis['redis_version'];
						echo "<p class='good'>Success, <b>Redis server</b> version $redisVersion</p>";
				} else echo"<p class='bad'>Unable to connect to <b>Redis server</b></p>";
			} catch( Exception $e ) {
				echo"<p class='bad'>Error while connecting to <b>Redis server</b></p>";
			}
		} else echo "<p class='bad'><b>php_redis</b> is not enabled.Add in to php.ini: <br>extension = <b>php_redis.dll</b></p>";
		?>
		
		<pre>
			$redis=new Redis();
			$addr=empty($_SERVER['REDIS']) ? 'localhost' : $_SERVER['REDIS'];
			$redis->connect($addr);
		</pre>
		<hr>

		<h4>Include Test:</h4>
		<p>Include path: <b><?=ini_get('include_path')?></b>; Including a global library file:</p>
		<?php
		include "tools/wtnmpIncludeTest.lib.php";
		if ( function_exists('wtnmpIncludeTest') ) wtnmpIncludeTest();
		?>
		<pre>
			include "tools/wtnmpIncludeTest.lib.php";
		</pre>
		<hr>

	</body></html>