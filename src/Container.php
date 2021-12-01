<?php

namespace Andileong\Collection;


use ArrayAccess;
use Closure;
use Exception;
use ReflectionClass;

class Container implements ArrayAccess
{
    public array $binding = [];
    public array $singletons = [];


    public function bind($key,$concrete,$shared = false)
    {
        $this->binding[$key] = [
            'concrete' => $concrete,
            'shared' => $shared,
        ];
        
    }

    public function singleton($key,$concrete)
    {
        $this->bind($key,$concrete,true);
    }

    public function get($key)
    {
        if (!isset($this->binding[$key])) {

            if( class_exists($key)){
                return $this->instantiate($key);
            }
            throw new Exception("No binding was registered for {$key}");
        }

        $binding = $this->binding[$key];

        if( $binding['shared'] && isset($this->singletons[$key]) ){
            return $this->singletons[$key];
        }

        if(!$binding['concrete'] instanceof Closure){
            return $binding['concrete'] ;
        }

        return tap($binding['concrete'](), function($concrete) use($binding,$key){
            if( $binding['shared'] ){
                $this->singletons[$key] = $concrete;
            }
        });
    }

    private function instantiate($class)
    {
        $reflector = new ReflectionClass($class);
        if (!$constructor = $reflector->getConstructor()) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();

                continue;
            }

            if (!$parameter->getType() || !class_exists($dependency = $parameter->getType()?->getName())) {
                $message = "No binding was registered on {$class} for constructor parameter, \${$parameter->getName()}.";

                throw new Exception($message);
            }

            $dependencies[] = $this->instantiate($dependency);
        }

        return $reflector->newInstanceArgs($dependencies);
    }

    public function offsetExists($offset)
    {
        return isset($this->binding[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->binding[$offset];
    }

    public function offsetSet($offset, $value)
    {
        return $this->binding[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->binding[$offset]);
    }
}