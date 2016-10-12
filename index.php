

<?php

/*
echo "<!DOCTYPE html>\n";
echo "<html><head>\n";
echo "<script src=\"https://code.jquery.com/jquery-1.12.4.js\"></script>\n";
echo '<a name="form778398148" id="formAnchor778398148"></a>
<script type="text/javascript" src="https://fs26.formsite.com/include/form/embedManager.js?778398148"></script>
<script type="text/javascript">
EmbedManager.embed({
	key: "https://fs26.formsite.com/res/showFormEmbed?EParam=m%2FOmK8apOTBqZW9QBc1%2Bv2rYe2Y6sJfY&778398148",
	width: "100%",
	mobileResponsive: true,
	prePopulate: { "1": 2 },
});
</script>';
echo "</head><body></body></html>\n";*/


use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

//this initializes phalcon and loads it's classes

$loader = new Loader();

$loader->registerDirs (array(
		'controllers/',
		'models/',
));

$loader->register();

//next we specify which phalcon services should be initializes (DB, login, etc, this handles the common ones)

$di = new FactoryDefault();

//now we add the views that will be a part of our app
$di->set (
		'view',
		function() {
			$view = new View();
			$view->setViewsDir('views/');
			return $view;
		}
	);



$di->set(
		'url',
		function() {
			$url = new UrlProvider;
			$url->setBaseUri("/");
			return $url;
		}
	);




$di->set(
		'db',
		function() {
			return new DbAdapter(array(
					'host' => '127.0.0.1',
					'username' => 'root',
					'password' => 'abc123',
					'dbname' => 'phalcon_db'

			));
		}
	);


//finally we create an application with the settings we used
$application = new Application($di);

try {

	$response = $application->handle();

	$response->send();

} catch (Exception $e) {
	echo "Error: ". $e->getMessage();
}





