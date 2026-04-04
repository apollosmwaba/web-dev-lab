<?php
/**
 * Router Class
 */
class Router {
    private $routes = [];

    public function addRoute($pattern, $controller, $action) {
        $this->routes[$pattern] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($route, $params = []) {
        // Default route
        if (empty($route) || $route === '/') {
            $route = 'student/index';
        }

        // Check for exact match first
        if (isset($this->routes[$route])) {
            $routeConfig = $this->routes[$route];
            $controllerName = $routeConfig['controller'];
            $action = $routeConfig['action'];
            
            require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
            
            $controller = new $controllerName();
            
            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // Parse dynamic routes
        $parts = explode('/', trim($route, '/'));
        
        if (count($parts) >= 2) {
            $module = $parts[0];
            $actionName = $parts[1];
            $param = isset($parts[2]) ? $parts[2] : null;

            $controllerMap = [
                'student' => 'StudentController',
                'admin' => 'AdminController',
            ];

            if (isset($controllerMap[$module])) {
                $controllerName = $controllerMap[$module];
                require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
                
                $controller = new $controllerName();
                
                if ($param !== null && method_exists($controller, $actionName)) {
                    call_user_func_array([$controller, $actionName], [$param]);
                    return;
                } elseif (method_exists($controller, $actionName)) {
                    call_user_func_array([$controller, $actionName], []);
                    return;
                }
            }
        }

        // 404
        http_response_code(404);
        echo "404 - Page Not Found";
    }
}
