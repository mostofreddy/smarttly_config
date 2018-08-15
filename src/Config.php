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
class Config implements Countable, Iterator
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

    /*************************************************
     * Magic methods
     ************************************************/

     /**
      * Method __get: magic method
      *
      * @param string $name Attribute name

      * @return mixed
      */
    public function __get($name)
    {
        return $this->get($name);
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
}
