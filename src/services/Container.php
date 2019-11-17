<?php
namespace hannespries\services;

class Container {
    private static $instance = null;

    private $services = [];
    private $instancesMap = [];

    public static function instance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addServiceDescriptor($sd = []) {
        $this->services = array_merge($this->services, $sd);
    } 

    public function get($name, $params = []) {
        $result = null;
        if(!isset($this->instancesMap[$name])) {
            if(isset($this->services[$name])) {
                $sd = $this->services[$name];
                $clazz = $sd['class'];

                $args = [];
                foreach ($sd['arguments'] as $arg) {
                    if($arg['type'] == 'primitive') {
                        $args[] = isset($arg['value']) ? $arg['value'] : $arg['id']; 
                    }
                    else if($arg['type'] == 'json') {
                        $args[] = json_decode(isset($arg['value']) ? $arg['value'] : $arg['id'], true);
                    }
                    else if($arg['type'] == 'param') {
                        $args[] = isset($param[$arg['id']]) ? $param[$arg['id']] : null; //TODO fallback value
                    }
                    else if($arg['type'] == 'service') {
                        $args[] = $this->get($arg['id'], $params);
                    }
                    else if($arg['type'] == 'class') {
                        $r = new \ReflectionClass(isset($arg['class']) ? $arg['class'] : $arg['id']);
                        $args[] = $r->newInstance();
                    }
                    else {
                        throw new \Exception('unkown argument type: ' . $arg['type']);
                    }    
                }

                $ref = new \ReflectionClass($clazz);
                if(count($args) > 0) {
                    $result = $ref->newInstanceArgs($args);
                }
                else {
                    $result = $ref->newInstance();
                }

                if(isset($sd['singleton']) && $sd['singleton'] === false) {
                    $this->instancesMap[$name] = $result;
                }
            }
        }
        else {
            $result = $this->instancesMap[$name];
        }
        return $result;
    }
}