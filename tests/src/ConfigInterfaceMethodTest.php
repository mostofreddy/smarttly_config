<?php
/**
 * ConfigInterfaceMethodTest
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
namespace Smarttly\Config\Test;

// PHPUnit
use PHPUnit\Framework\TestCase;
// Config
use Smarttly\Config\Config;

/**
 * ConfigInterfaceMethodTest
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class ConfigInterfaceMethodTest extends TestCase
{
    /**
     * Data provider
     *
     * @return array
     */
    public function configProvider():array
    {
        $data = [
            'name' => 'John',
            'lastname' => 'Doe',
            'database' => [
                'driver' => 'mongodb'
            ]
        ];

        return [[$data]];
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testCount(array $data):void
    {
        $config = new Config($data);

        $this->assertEquals(count($data), $config->count());
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testCurrent(array $data):void
    {
        $config = new Config($data);

        $this->assertEquals(
            $data['name'],
            $config->current()
        );
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testKey(array $data):void
    {
        $config = new Config($data);

        $this->assertEquals(
            'name',
            $config->key()
        );
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testNext(array $data):void
    {
        $config = new Config($data);
        $config->next();

        $this->assertEquals(
            $data['lastname'],
            $config->current()
        );
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testRewind(array $data):void
    {
        $config = new Config($data);
        $config->next();
        $config->rewind();

        $this->assertEquals(
            $data['name'],
            $config->current()
        );
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testValid(array $data):void
    {
        $config = new Config($data);

        $this->assertTrue(
            $config->valid()
        );
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testValidFalse(array $data):void
    {
        $config = new Config($data);
        $config->next();
        $config->next();
        $config->next();
        $config->next();

        $this->assertFalse(
            $config->valid()
        );
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testOffsetExists(array $data):void
    {
        $config = new Config($data);
        $this->assertTrue(
            isset($config['name'])
        );
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testOffsetUnset(array $data):void
    {
        $config = new Config($data);
        unset($config['name']);
        $this->assertFalse(
            isset($config->name)
        );
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testOffsetSetKeyString(array $data):void
    {
        $expected = "Hello World";
        $config = new Config($data);
        $config->say = $expected;
        $this->assertEquals($expected, $config->say);
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testOffsetSetKeyInt(array $data):void
    {
        $expected = "Hello World";
        $config = new Config($data);
        $config[] = $expected;
        $this->assertEquals($expected, $config[0]);
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testOffsetSetArray(array $data):void
    {
        $expected = ["Hello", "World"];
        $configExpected = new Config($expected);

        $config = new Config($data);
        $config->say = $expected;

        $this->assertEquals($configExpected, $config->say);
        $this->assertInstanceOf(Config::class, $config->say);
    }
}
