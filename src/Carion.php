<?php

/*
 * The MIT License
 *
 * Copyright 2018 Wallace Osmar.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Carion;

/**
 * Carion
 *
 * @author Wallace Osmar <wallace.osmar@hotmail.com>
 */
class Carion implements \ArrayAccess, \Countable, \IteratorAggregate {
    
    /**
     * Trait Call
     */
    use Call;
    
    /**
     * Array of singletons
     * 
     * @var array 
     */
    public $singleton;
    
    /**
     * Magic method __call
     * 
     * @param mixed $name
     * @param array $arguments
     * 
     * @return mixed
     */
    public function __call( $name, $arguments) {
        return $this->call( $this->singleton[ $name ], $arguments);
    }
    
    /**
     * Magic method __get
     * 
     * @param mixed $name
     * 
     * @return mixed
     */
    public function &__get($name) {
        $return = null;
        if ( $this->__isset( $name ) ) {
            if ( is_object( $this->singleton[ $name ] ) && method_exists( $this->singleton[ $name ], '__invoke') ) {
                $return = $this->singleton[ $name ]($this);
            }
        }
        return $return;
    }
    
    /**
     * Magic method __isset
     * 
     * @param mixed $name
     * 
     * @return bool
     */
    public function __isset($name) {
        return isset( $this->singleton[$name] );
    }
    
    /**
     * Magic method __set
     * 
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $this->singleton[$name] = $value;
    }
    
    /**
     * Magic method __unset
     * 
     * @param string $name
     */
    public function __unset($name) {
        unset( $this->singleton[$name] );
    }
    
    /**
     * Total of singletons
     * 
     * @return int return the total of singletons of the class
     */
    public function count() {
        return count( $this->singleton );
    }
    
    /**
     * 
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator( $this->singleton );
    }
    
    /**
     * 
     * @param type $offset
     * @return type
     */
    public function offsetExists($offset) {
        return $this->__isset( $offset );
    }
    
    /**
     * 
     * @param type $offset
     * @return type
     */
    public function offsetGet($offset) {
        return $this->__get( $offset );
    }
    
    /**
     * 
     * @param type $offset
     * @param type $value
     */
    public function offsetSet($offset, $value) {
        $this->__set( $offset, $value );
    }
    
    /**
     * 
     * @param type $offset
     */
    public function offsetUnset($offset) {
        $this->__unset( $offset );
    }
    
    /**
     * 
     * @param type $name
     * @param type $value
     */
    public function singleton( $name, $value ) {
        $this->__set ( $name, function ( $c ) use( $value ) {
            static $object;
            if ( null === $object ) {
                $object = $this->call( $value, $c );
            }
            return $object;
        });
    }

}
