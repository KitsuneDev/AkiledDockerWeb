<?php 



if (!class_exists("MongoDB\Driver\Manager")) die ("<b>Fatal error</b>: <b>php_mongodb</b> extension is not enabled.<br> Add/Uncomment in WinNMP\conf\php.ini: <br>extension = <b>php_mongodb.dll</b><br><p>Also Instal MongoDB by running WinNMP Installer!</p><hr>");

$addr=empty($_SERVER['MONGODB']) ? 'localhost' : $_SERVER['MONGODB'];
$manager = new MongoDB\Driver\Manager("mongodb://$addr");

try {
	if (!empty($_GET['pingMongo'])) {
		$command = new MongoDB\Driver\Command(['buildInfo' => 1]);
		$cursor = $manager->executeCommand('admin', $command);
		$data = $cursor->toArray();
		if (!empty($data[0]->version)) {
			$mongoVersion="MongoDB/".$data[0]->version;
			header("X-pingMongo: $mongoVersion");
			die("PONG");
		}
	} else {
		$command = new MongoDB\Driver\Command(['ping' => 1]);
		$cursor = $manager->executeCommand('admin', $command);
	}
} catch( Exception $e ) {
	die("Error: Unable to connect: ".$e->getMessage());
}


echo "<h4>TODO: MongoDB Ajax Console</h4>";
echo "<p>If you are looking for a GUI to MongoDB, WinNMP does not include one yet.<br>You could use <a href='https://robomongo.org/'>Robomongo / Robo 3T</a> (free) or <a href='https://studio3t.com/'>Studio 3T/</a> (paid)</p>";
