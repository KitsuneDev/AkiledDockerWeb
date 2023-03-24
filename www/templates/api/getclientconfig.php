<?php 
$config = array();
$config['ip'] = $_ENV['HOST'];
$config['port'] = "30000";
$config['AkiledUrl'] = $_ENV['WEB_URL'];
$config['Akiledswfs'] = $_ENV['WEB_URL'].'/swfs';
$config['Vars'] = $config["Akiledswfs"].'/gamedata/vars.txt';
$config['Texts'] = $config["Akiledswfs"].'/gamedata/texts.txt';
$config['Producdata'] = $config["Akiledswfs"].'/gamedata/productdata.txt';
$config['Furnidata'] = $config["Akiledswfs"].'/gamedata/furnidata.xml';
$config['MessageFun'] = "La Aventura Está Por Comenzar/hionix Ya tiene listas tus salas/Guzman les dice: ¡lanza la vaca!./hionix necesita de tu apoyo!/Eres mi carnada favorita./No quiero que pienses que es una ilusión./Has venido para quedarte/Adoro tu camisa./Regresame eso, si no quieres un chanclazo !/Si no estas aquí.. No eres nadie/Esta es la Pixel Guerra./Cambia esa cara que lo mejor ha llegado.";
$config['Message'] = "Cargando...";
$config['R_64'] = $config["Akiledswfs"].'/gordon/R_64/';
$config['swf'] = "habbo.swf";
$config['cache'] = time();

echo json_encode($config);
?>
