<?php
/**
 * Php
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
namespace Smarttly\Config\Reader;

use Smarttly\Config\Reader\ReaderAbstract;
use Smarttly\Config\Exception\InvalidConfigFile;

/**
 * Php
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class Php extends ReaderAbstract
{
    /**
     * Load config form a file
     *
     * @param string $filename Filename
     *
     * @return array
     */
    public function fromFile(string $filename):array
    {
        $this->validateFile($filename);

        $config = include $filename;

        return $this->decode($config);
    }

    /**
     * Load config form a string
     *
     * @param string $string Data
     *
     * @return array
     */
    public function fromString(string $string):array
    {
        return $this->fromFile($string);
    }

    /**
     * Decode php array
     *
     * @param mixed $config Json
     *
     * @throws InvalidConfigFile if the json string is invalid
     * @return array
     */
    protected function decode($config):array
    {
        if ($config === 1 || (!is_array($config) && !is_object($config))) {
            throw new InvalidConfigFile(
                'Invalid PHP configurations: did not return an array or object'
            );
        }

        return json_decode(json_encode($config), true);
    }
}
