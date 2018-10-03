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
namespace Smarttly\Config\Test;

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
class ConfigTest extends TestCase
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
    public function testNestedConfigDataWithGet(array $data):void
    {
        $config = new Config($data);

        $this->assertEquals(
            $data['database']['driver'],
            $config->database->get('driver')
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
    public function testGetDefault(array $data):void
    {
        $config = new Config($data);
        $expected = 'defaultValue';

        $this->assertEquals(
            $expected,
            $config->get('invalidKey', $expected)
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

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testMerge1(array $data):void
    {
        $expected = 'Smith';

        $config1 = new Config($data);
        $config2 = new Config($data);
        $config2->lastname = $expected;

        $config1->merge($config2);

        $this->assertEquals($expected, $config1->lastname);
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testMerge2(array $data):void
    {
        $expected = 'Smith';

        $config1 = new Config($data);
        $config2 = new Config($data);
        $config2->say = ['Hello', 'World'];

        $config1->merge($config2);

        $this->assertInstanceOf(Config::class, $config1->say);
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testMerge3(array $data):void
    {
        $expected = 'Hello World';

        $config1 = new Config($data);
        $config2 = new Config($data);
        $config2[] = $expected;

        $config1->merge($config2);

        $this->assertEquals($expected, $config1[0]);
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testMerge4(array $data):void
    {
        $expected = 'Hello World';

        $config1 = new Config($data);
        $config1[0] = 'Hello';
        $config2 = new Config($data);
        $config2[0] = $expected;

        $config1->merge($config2);

        $this->assertEquals($expected, $config1[1]);
    }

    /**
     * Test method
     *
     * @param array $data Config
     *
     * @dataProvider configProvider
     * @return       void
     */
    public function testMerge5(array $data):void
    {
        $data = [
            'name' => 'John',
            'lastname' => 'Doe'
        ];
        $config1 = new Config($data);

        $data = [
            'lastname' => 'Smith',
            'database' => [
                'driver' => 'mongodb'
            ]
        ];
        $config2 = new Config($data);

        $config1->merge($config2);

        $this->assertEquals('Smith', $config1->lastname);
        $this->assertEquals('John', $config1->name);
        $this->assertEquals('mongodb', $config1->database->driver);
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testHasTrue():void
    {
        $data = [
            'name' => 'John',
            'lastname' => 'Doe'
        ];
        $config = new Config($data);

        $this->assertTrue($config->has('lastname'));
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testHasFalse():void
    {
        $config = new Config([]);

        $this->assertFalse($config->has('lastname'));
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testToArray():void
    {
        $data = [
            'name' => 'John',
            'lastname' => 'Doe',
            'database' => [
                'driver' => 'mongodb'
            ],
            'func' => function () {
            }
        ];

        $expected = $data;
        $expected['func'] = 'Callable function';

        $config = new Config($data);

        echo print_r($config->database->toArray(), true);

        $this->assertEquals($expected, $config->toArray());
    }
}
