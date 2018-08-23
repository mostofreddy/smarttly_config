<?php
/**
 * ReaderAbstract
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

use Smarttly\Config\Reader\ReaderInterface;
use Smarttly\Config\Exception\InvalidConfigFile;

/**
 * ReaderAbstract
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
abstract class ReaderAbstract implements ReaderInterface
{
    /**
     * Validate if the file is valid and if it has read permission
     *
     * @param string $filename Path
     *
     * @throws InvalidConfigFile if the file is invalid o it not has read permission
     * @return bool
     */
    protected function validateFile(string $filename):bool
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new InvalidConfigFile(sprintf("File '%s' doesn't exist or not readable", $filename));
        }
        return true;
    }

    /**
     * Load config form a file
     *
     * @param string $path File path
     *
     * @return array
     */
    abstract public function fromFile(string $path):array;
}
