<?php
/**
 * ReaderHelperTrait
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
namespace Smarttly\Config\Test\Reader;

/**
 * ReaderHelperTrait
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
trait ReaderHelperTrait
{
    /**
     * Generate tmp file
     *
     * @param string $content Content file
     * @param string $ext     Extension file. Default: null
     *
     * @return string
     */
    protected function generateTmpFile(string $content, string $ext = null):string
    {
        $tmpFilename = tempnam(sys_get_temp_dir(), "config");

        if (!is_null($ext)) {
            $tmpFilename .= '.'.$ext;
        }

        file_put_contents($tmpFilename, $content);
        return $tmpFilename;
    }

    /**
     * Delete tmp file
     *
     * @param string $tmpFilename Temp file
     *
     * @return void
     */
    protected function unlinkFile(string $tmpFilename):void
    {
        unlink($tmpFilename);
    }
}
