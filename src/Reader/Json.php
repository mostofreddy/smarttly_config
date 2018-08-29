<?php
/**
 * Json
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
 * Json
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class Json extends ReaderAbstract
{
    /**
     * Load config form a file
     *
     * @param string $filename File path
     *
     * @return array
     */
    public function fromFile(string $filename):array
    {
        $this->validateFile($filename);

        $json = file_get_contents($filename);

        return $this->decode($json);
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
        if (empty($string)) {
            return [];
        }

        return $this->decode($string);
    }

    /**
     * Decode json string
     *
     * @param string $json Json string
     *
     * @throws InvalidConfigFile if the json string is invalid
     * @return array
     */
    protected function decode(string $json):array
    {
        $config = json_decode($json, true);

        if (null !== $config && !is_array($config)) {
            throw new InvalidConfigFile(
                'Invalid JSON configurations: did not return an array or object'
            );
        }

        if (JSON_ERROR_NONE !== json_last_error() || is_null($config)) {
            throw new InvalidConfigFile(
                sprintf('Invalid JSON configuration: %s', json_last_error_msg())
            );
        }

        return $config;
    }
}
