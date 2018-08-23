<?php
/**
 * JsonTest
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

// PHPUnit
use PHPUnit\Framework\TestCase;
// Config
use Smarttly\Config\Reader\Json;
use Smarttly\Config\Test\Reader\ReaderHelperTrait;

/**
 * JsonTest
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class JsonTest extends TestCase
{
    use ReaderHelperTrait;

    /**
     * Test method
     *
     * @expectedException        \Smarttly\Config\Exception\InvalidConfigFile
     * @expectedExceptionMessage Invalid JSON configuration: Syntax error
     *
     * @return void
     */
    public function testFromFileEmpty():void
    {
        $content = <<<TXT
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [],
            $config
        );
    }

    /**
     * Test method
     *
     * @expectedException        \Smarttly\Config\Exception\InvalidConfigFile
     * @expectedExceptionMessage Invalid JSON configuration: Syntax error
     *
     * @return void
     */
    public function testFromFileInvalidJsonV1():void
    {
        $content = <<<TXT
Hello world
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [],
            $config
        );
    }

    /**
     * Test method
     *
     * @expectedException        \Smarttly\Config\Exception\InvalidConfigFile
     * @expectedExceptionMessage Invalid JSON configuration: Syntax error
     *
     * @return void
     */
    public function testFromFileInvalidJsonV2():void
    {
        $content = <<<TXT
{
    'say': 'hello'
}
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [],
            $config
        );
    }

    /**
     * Test method
     *
     * @expectedException        \Smarttly\Config\Exception\InvalidConfigFile
     * @expectedExceptionMessage Invalid JSON configurations: did not return an array or object
     *
     * @return void
     */
    public function testFromFileInvalidJsonV3():void
    {
        $content = <<<TXT
"Hello World"
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [],
            $config
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testFromFileV1():void
    {
        $content = <<<TXT
{}
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [],
            $config
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testFromFileV2():void
    {
        $content = <<<TXT
[]
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [],
            $config
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testFromFileV3():void
    {
        $content = <<<TXT
{
    "say": "hello"
}
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [
                "say" => "hello"
            ],
            $config
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testFromFileV4():void
    {
        $content = <<<TXT
{
    "say": [
        "Hello",
        "World"
    ]
}
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [
                "say" => [
                    "Hello",
                    "World"
                ]
            ],
            $config
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testFromFileV5():void
    {
        $content = <<<TXT
{
    "object": {
        "say": [
            "Hello",
            "World"
        ]
    }
}
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Json();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [
                "object" => [
                    "say" => [
                        "Hello",
                        "World"
                    ]
                ]
            ],
            $config
        );
    }
}
