<?php
/**
 * ConfigTest
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
namespace Resty\Test;

// PHPUnit
use PHPUnit\Framework\TestCase;
// Config
use Smarttly\Config\Config;


/**
 * ConfigTest
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class EnvTest extends TestCase
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
    public function testNestedConfigInstanceOf(array $data):void
    {
        $config = new Config($data);

        $this->assertInstanceOf(
            Config::class,
            $config->get('database')
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
    public function testNestedConfigData(array $data):void
    {
        $config = new Config($data);

        $this->assertEquals(
            $data['database']['driver'],
            $config->get('database')->get('driver')
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
    public function testGet(array $data):void
    {
        $config = new Config($data);

        $this->assertEquals(
            $data['name'],
            $config->get('name')
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
    public function testMagicGet(array $data):void
    {
        $config = new Config($data);

        $this->assertEquals(
            $data['name'],
            $config->name
        );
    }

    /*************************************************
     * Tests interfaces methods
     ************************************************/

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
}
