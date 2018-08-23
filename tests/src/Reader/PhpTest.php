<?php
/**
 * PhpTest
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
use Smarttly\Config\Reader\Php;
use Smarttly\Config\Test\Reader\ReaderHelperTrait;

/**
 * PhpTest
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class PhpTest extends TestCase
{
    use ReaderHelperTrait;

    /**
     * Test method
     *
     * @expectedException        \Smarttly\Config\Exception\InvalidConfigFile
     * @expectedExceptionMessage Invalid PHP configurations: did not return an array or object
     *
     * @return void
     */
    public function testFromFileEmpty():void
    {
        $content = <<<TXT
<?php

TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Php();
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
     * @expectedExceptionMessage Invalid PHP configurations: did not return an array or object
     *
     * @return void
     */
    public function testFromFileWithOutReturn():void
    {
        $content = <<<TXT
<?php

\$config = [
    'say' => 'hello'
];
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Php();
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
     * @expectedExceptionMessage Invalid PHP configurations: did not return an array or object
     *
     * @return void
     */
    public function testFromFileStringReturn():void
    {
        $content = <<<TXT
<?php

return 'Hello';
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Php();
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
<?php

\$config = [
    'say' => 'hello'
];
return \$config;
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Php();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            ['say' => 'hello'],
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
<?php

\$config = [
    'say' => 'hello',
    'hello' => 'World'
];
return \$config;
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Php();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [
                'say' => 'hello',
                'hello' => 'World'
            ],
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
<?php

\$config = new StdClass();
\$config->say = 'Hello World';
return \$config;
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Php();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [
                'say' => 'Hello World'
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
<?php

\$config = new StdClass();
\$config->say = 'Hello World';
\$config->other = new StdClass();
\$config->other->name = 'John Doe';
return \$config;
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Php();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [
                'say' => 'Hello World',
                'other' => ['name' => 'John Doe']
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
<?php

\$config = [];
\$config['say'] = 'Hello World';
\$config['other'] = new StdClass();
\$config['other']->name = 'John Doe';
return \$config;
TXT;
        $tmpFilename = $this->generateTmpFile($content);

        $reader = new Php();
        $config = $reader->fromFile($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertEquals(
            [
                'say' => 'Hello World',
                'other' => ['name' => 'John Doe']
            ],
            $config
        );
    }
}
