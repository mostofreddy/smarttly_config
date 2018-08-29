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
    protected $data = [];

    /**
     * Construct
     *
     * @param array $data Configuration data. Default: []
     */
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    /**
     * Load array in object
     *
     * @param array $data Configuration data
     *
     * @return self
     */
    public function load(array $data):self
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $this->data[$k] = new static($v);
            } else {
                $this->data[$k] = $v;
            }
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
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return $default;
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
            if (array_key_exists($key, $this->data)) {
                if (is_int($key)) {
                    $this->data[] = $value;
                } elseif ($value instanceof self && $this->data[$key] instanceof self) {
                    $this->data[$key]->merge($value);;
                } else {
                    $this->data[$key] = $value;
                }
            } else {
                $this->data[$key] = $value;
            }
        }
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

     * @return void
     */
    public function __set($key, $value)
    {
        if (is_array($value)) {
            $this->data[$key] = new static($value);
        } else if (is_null($key)) {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }
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
        return isset($this->data[$key]);
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
        unset($this->data[$key]);
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
        return count($this->data);
    }

    /**
     * Method current(): defined by Countable interface.
     *
     * @see    Iterator::current()
     * @return int
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * Method key(): defined by Iterator interface.
     *
     * @see    Iterator::key()
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * Method next(): defined by Iterator interface.
     *
     * @see    Iterator::next()
     * @return void
     */
    public function next()
    {
        next($this->data);
    }

    /**
     * Method rewind(): defined by Iterator interface.
     *
     * @see    Iterator::rewind()
     * @return void
     */
    public function rewind()
    {
        reset($this->data);
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
