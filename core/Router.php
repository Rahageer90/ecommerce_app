<?php
namespace Core;
class Router {
    protected $routes = [];
    public function add($method, $uri,$controller)
    {
        $this->routes[]=[
            'uri' => $uri,
            'controller'=> $controller,
            'method'=> strtoupper($method)
        ];
    }

    public function get($uri,$controller){
        $this->add('GET',$uri,$controller);
    }
    public function post($uri,$controller){
        $this->add('POST',$uri,$controller);
    }
    public function put($uri,$controller){
        $this->add('PUT',$uri,$controller);
    }
    public function delete($uri,$controller){
        $this->add('DELETE',$uri,$controller);
    }
    public function patch($uri,$controller){
        $this->add('PATCH',$uri,$controller);
    }
    public function route($uri, $method) {
        foreach ($this->routes as $route) {
            if ($route['uri'] == $uri && $route['method'] == strtoupper($method)) {
                return $route['controller'];
            }
        }
        $this->abort();
    }
    
    protected function abort(){
        http_response_code(404);
        die("404 - Page Not Found");
    }
    
}