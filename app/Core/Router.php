<?php

namespace App\Core;

// 🔥 Autoload global (carrega tudo automaticamente)
require_once __DIR__ . '/Autoload.php';

class Router
{
    public function run()
    {
        $url = $this->getUrl();

		$controllerMap = [
			'login' => 'AuthController',
			'auth' => 'AuthController',
			'dashboard' => 'DashboardController'
		];

		$controllerKey = $url[0] ?? 'dashboard';

		$controllerName = $controllerMap[$controllerKey]
			?? ucfirst($controllerKey) . 'Controller';


		$method = $url[1] ?? 'index';

		// 🔥 regra especial para login
        if ($controllerKey === 'login' && $method === 'index') {
            $method = 'login';
        }


        $params = array_slice($url, 2);

        $controllerClass = "App\\Controllers\\$controllerName";

        // 🔥 Verifica se Controller existe
        if (!class_exists($controllerClass)) {
            $this->error("Controller não encontrado", $controllerClass);
            return;
        }

        $controller = new $controllerClass();

        // 🔥 Verifica se método existe
        if (!method_exists($controller, $method)) {
            $this->error("Método não encontrado", $method);
            return;
        }

        // 🚀 Executa
        call_user_func_array([$controller, $method], $params);
    }

    private function getUrl()
    {
        if (!empty($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return [];
    }

    // 🔥 Tratamento de erro padronizado
    private function error($type, $message)
    {
        http_response_code(404);

        echo "<h1>404</h1>";
        echo "<strong>$type:</strong> $message";
        exit;
    }
}

