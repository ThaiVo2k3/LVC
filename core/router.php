<?php
class Router
{
    protected $routes = [];
    protected $groupPrefix = '';
    protected $groupMiddleware = [];

    public function group($prefix, $options, $callback)
    {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;

        // gộp prefix
        $this->groupPrefix .= $prefix;

        // gộp middleware
        if (isset($options['middleware'])) {
            $this->groupMiddleware = array_merge(
                $this->groupMiddleware,
                $options['middleware']
            );
        }

        // chạy callback để đăng ký route
        $callback($this);

        // rollback lại
        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }
    public function get($url, $action, $middlewares = [])
    {
        $fullUrl = $this->groupPrefix . $url;

        $this->routes['GET'][$fullUrl] = [
            'action' => $action,
            'middleware' => array_merge($this->groupMiddleware, $middlewares)
        ];
    }

    public function post($url, $action, $middlewares = [])
    {
        $fullUrl = $this->groupPrefix . $url;

        $this->routes['POST'][$fullUrl] = [
            'action' => $action,
            'middleware' => array_merge($this->groupMiddleware, $middlewares)
        ];
    }
    public function getRoutes()
    {
        return $this->routes;
    }
    private function runMiddleware($middlewares)
    {
        $map = [
            'auth' => AuthMiddleware::class,
            'admin' => AdminMiddleware::class,
        ];

        foreach ($middlewares as $middleware) {
            if (!isset($map[$middleware])) continue;

            $instance = new $map[$middleware];
            $instance->handle();
        }
    }
    private function convertToRegex($url)
    {
        $defaultPatterns = [
            'id'   => '\d+',
            'slug' => '[A-Za-z0-9-]+'
        ];

        return "#^" . preg_replace_callback(
            '/\{(\w+)(?::([^}]+))?\}/',
            function ($matches) use ($defaultPatterns) {

                $paramName = $matches[1];

                // Nếu có regex custom
                if (isset($matches[2])) {
                    return '(' . $matches[2] . ')';
                }

                // Nếu có pattern mặc định
                if (isset($defaultPatterns[$paramName])) {
                    return '(' . $defaultPatterns[$paramName] . ')';
                }

                // fallback
                return '([^/]+)';
            },
            $url
        ) . "$#";
    }

    public function xulyPath($method, $url)
    {
        $url = $url ?: '/';

        if (!isset($this->routes[$method])) {
            require_once "./app/views/404.php";
            return;
        }

        foreach ($this->routes[$method] as $routeUrl => $route) {
            $action = $route['action'];
            $middlewares = $route['middleware'];

            $pattern = $this->convertToRegex($routeUrl);

            if (preg_match($pattern, $url, $matches)) {

                // ✅ CHỈ chạy middleware khi match
                $this->runMiddleware($middlewares);

                array_shift($matches);

                [$controllerPath, $func] = explode('@', $action);

                $filePath = "./app/controllers/" . $controllerPath . ".php";
                if (!file_exists($filePath)) {
                    die("Controller not found: $filePath");
                }

                require_once $filePath;

                $parts = explode('/', $controllerPath);
                $className = end($parts);

                $controller = new $className();

                call_user_func_array([$controller, $func], $matches);
                return;
            }
        }

        require_once "./app/views/404.php";
    }
}
