<?php  

/**
 * 
 */
class Router
{

	private $routes; // Массив роутов
	
	function __construct()
	{
		$routesPath = ROOT."/config/routes.php";
		$this->routes = include($routesPath);
	}

	private function getURI()
	{
		if(!empty( $_SERVER["REQUEST_URI"] )) {
			return trim( $_SERVER["REQUEST_URI"], "");
		}
	}

	public function run()
	{
		// получить строку запроса
		

		$uri = $this->getURI();

		// Проверить наличие такого запроса в routes.php

		foreach ($this->routes as $uriPattern => $path) {
			
			// Сравниваем $uriPattern and path

			if( preg_match("~$uriPattern~", $uri) ) {
				echo $path;
				// url = mvc/products выведет product/list

				// Определить какой контроллер и экшн обрабатывают запрос

				$segments = explode("/", $path);
				$controllerName = array_shift($segments)."Controller";
				$controllerName = ucfirst($controllerName);

				$actionName = "action".ucfirst(array_shift($segments));

				echo "<br>Class: ". $controllerName;
				echo "<br>Action: ". $actionName;

				// Подключить файл класса контроллера

				$controllerFile = ROOT . "/controllers/" . $controllerName . ".php";

				if(file_exists($controllerFile)) {
					include_once ( $controllerFile );
				}

				// Создать обьект, вызвать метод ( экшн )

				$controllerObject = new $controllerName;

				$result = $controllerObject->$actionName();

				if( $result != null ) {
					break;
				}

			}



		}
	}

}

