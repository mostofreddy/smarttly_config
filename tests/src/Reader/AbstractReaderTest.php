<?php
/**
 * ReaderAbstractTest
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

// PHP
use ReflectionMethod;
// PHPUnit
use PHPUnit\Framework\TestCase;
// Config
use Smarttly\Config\Reader\AbstractReader;

/**
 * ReaderAbstractTest
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class AbstractReaderTest extends TestCase
{
    /**
     * Test method
     *
     * @expectedException        \Smarttly\Config\Exception\InvalidConfigFile
     * @expectedExceptionMessage File 'invalid_file' doesn't exist or not readable
     *
     * @return void
     */
    public function testValidateFile():void
    {
        $mock = $this->getMockForAbstractClass(AbstractReader::class);
        $ref = new ReflectionMethod($mock, 'validateFile');
        $ref->setAccessible(true);

        $ref->invokeArgs($mock, ['invalid_file']);
    }
}
