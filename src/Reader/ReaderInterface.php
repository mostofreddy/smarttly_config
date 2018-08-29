<?php
/**
 * ReaderInterface
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

/**
 * ReaderInterface
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
interface ReaderInterface
{
    /**
     * Load config form a file
     *
     * @param string $filename File path
     *
     * @return array
     */
    public function fromFile(string $filename):array;

    /**
     * Load config form a string
     *
     * @param string $string Data
     *
     * @return array
     */
    public function fromString(string $string):array;
}
