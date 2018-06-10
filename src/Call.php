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
 * Description of Call
 *
 * @author Wallace Osmar <wallace.osmar@hotmail.com>
 */
trait Call {
    
    /**
     * Call functions method and contructor of a class
     * 
     * @param \Closure $callback
     * 
     * @param array $param_arr
     * 
     * @return type
     */
    public function call ( $callback, $param_arr = array() ) {
        $param_new = array();
        
        // Cast to array
        $reflectionParameters = $this
                ->_getReflection($callback)->getParameters();
        
        for( $i = 0; $i < count( $reflectionParameters ); $i++ ) {
            $key = $reflectionParameters[$i]->getName();
            if( isset( $param_arr[$key] ) ) {
                $param_new[$i] = $param_arr[$key];
            } elseif ( isset( $param_arr[$i] ) ) {
                $param_new[$i] = $param_arr[$i];
            } else {
                $param_new[$i] = null;
            }
        }
        
        return call_user_func_array( $callback, $param_new);
    }
    
    /**
     * Get the reflection of the callback
     * 
     * @param \Closure $callback
     * 
     * @return \ReflectionFunction
     * 
     * @throws CallException
     */
    private function _getReflection( $callback ) {
        if ( $callback instanceof \Closure || ( is_string($callback) && function_exists( $callback ) ) ) {
            return new \ReflectionFunction($callback);
        }
        
        if( ( isset( $callback[0] ) && class_exists( $callback[0] ) ) &&
                ( isset( $callback[1] ) && method_exists($callback[0], $callback[1] ) ) ) {
            $class_name = is_object( $callback[0] ) ? get_class( $callback[0] ) : $callback[0];
            $class_method = $callback[1];
        } else {
            throw new CallException("The paramter need to be a function or a valid class!");
        }
        
        $reflection = new \ReflectionClass( $class_name );
        if ( !$reflection->hasMethod( $class_method ) ) {
            throw new CallException("The class {$class_name} does't have the method {$class_method}!");
        }
        return $reflection->getMethod( $class_method );
    }
    
}
