<?php
/**
 * Config
 *
 * PHP version 7.2+
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
namespace Smarttly\Config;

use Countable;
use Iterator;
use ArrayAccess;

/**
 * Config
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class Config implements Countable, Iterator, ArrayAccess
{
    protected $config = [];

    /**
     * Construct
     *
     * @param array $config Configuration data. Default: []
     */
    public function __construct(array $config = [])
    {
        $this->load($config);
    }

    /**
     * Load array in object
     *
     * @param array $data Configuration data
     *
     * @return self
     */
    public function load(array $config):self
    {
        foreach ($config as $key => $value) {
            $this->config[$key] = is_array($value) ? new static($value) : $value;
        }

        return $this;
    }

    /**
     * Retrieve a value and return $default if there is no element set.
     *
     * @param string $name    Config name
     * @param mixed  $default Default value
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->config[$name] ?? $default;
    }

    /**
     * Set new value in configuration
     *
     * @param mixed $value Config value
     * @param mixed $key   Config name
     *
     * @return self
     */
    public function set($value, $key = null): self
    {
        if (is_null($key)) {
            $this->config[] = is_array($value) ? new static($value) : $value;
        } else {
            $this->config[$key] = is_array($value) ? new static($value) : $value;
        }
        return $this;
    }

    /**
     * Checks for a key
     *
     * @param string $key Config name
     *
     * @return boolean
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Merge config
     *
     * @param Config $config Config instance
     *
     * @return void
     */
    public function merge(Config $config):void
    {
        foreach ($config as $key => $value) {
            if (array_key_exists($key, $this->config)) {
                if (is_int($key)) {
                    $this->config[] = $value;
                } elseif ($value instanceof self && $this->config[$key] instanceof self) {
                    $this->config[$key]->merge($value);
                } else {
                    $this->config[$key] = $value;
                }
            } else {
                $this->config[$key] = $value;
            }
        }
    }

    /**
     * Transform data to array
     *
     * @return array
     */
    public function toArray():array
    {
        $array = [];
        foreach ($this->config as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = $value->toArray();
            } elseif (is_callable($value)) {
                $array[$key] = 'Callable function';
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /*************************************************
     * Magic methods
     ************************************************/

     /**
      * Method __get: magic method
      *
      * @param int|string $key Attribute name

      * @return mixed
      */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Method __set: magic method
     *
     * @param int|string $key   Attribute name
     * @param mixed      $value Value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        return $this->set($value, $key);
    }

    /**
     * Method __isset(): magic method
     *
     * @param int|string $key Attribute name
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->config[$key]);
    }

    /**
     * Method unset(): magic method
     *
     * @param string $key Attribute name
     *
     * @return void
     */
    public function __unset($key)
    {
        unset($this->config[$key]);
    }

    /*************************************************
     * Interfaces methods
     ************************************************/

    /**
     * Method count(): defined by Countable interface.
     *
     * @see    Countable::count()
     * @return int
     */
    public function count()
    {
        return count($this->config);
    }

    /**
     * Method current(): defined by Iterator interface.
     *
     * @see    Iterator::current()
     * @return int
     */
    public function current()
    {
        return current($this->config);
    }

    /**
     * Method key(): defined by Iterator interface.
     *
     * @see    Iterator::key()
     * @return mixed
     */
    public function key()
    {
        return key($this->config);
    }

    /**
     * Method next(): defined by Iterator interface.
     *
     * @see    Iterator::next()
     * @return void
     */
    public function next()
    {
        next($this->config);
    }

    /**
     * Method rewind(): defined by Iterator interface.
     *
     * @see    Iterator::rewind()
     * @return void
     */
    public function rewind()
    {
        reset($this->config);
    }

    /**
     * Method valid(): defined by Iterator interface.
     *
     * @see    Iterator::valid()
     * @return bool
     */
    public function valid()
    {
        return ($this->key() !== null);
    }


    /**
     * Method offsetExists(): defined by ArrayAccess interface.
     *
     * @param mixed $offset Offset
     *
     * @see    ArrayAccess::offsetExists()
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * Method offsetGet(): defined by ArrayAccess interface.
     *
     * @param mixed $offset Offset
     *
     * @see    ArrayAccess::offsetGet()
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Method offsetSet(): defined by ArrayAccess interface.
     *
     * @param mixed $offset Offset
     * @param mixed $value  Value
     *
     * @see    ArrayAccess::offsetSet()
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    /**
     * Method offsetUnset(): defined by ArrayAccess interface.
     *
     * @param mixed $offset Offset
     *
     * @see    ArrayAccess::offsetUnset()
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->__unset($offset);
    }
}
