<?php
/**
 * ConfigFactoryTest
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
use Smarttly\Config\ConfigFactory;
use Smarttly\Config\Config;
use Smarttly\Config\Test\Reader\ReaderHelperTrait;

/**
 * ConfigFactoryTest
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class ConfigFactoryTest extends TestCase
{
    use ReaderHelperTrait;

    /**
     * Test method
     *
     * @return void
     */
    public function testLoad()
    {
        $content = <<<TXT
{
    "say": "hello"
}
TXT;
        $tmpFilename = $this->generateTmpFile($content, 'json');

        $configFactory = new ConfigFactory();
        $configFactory->load($tmpFilename);

        $this->unlinkFile($tmpFilename);

        $this->assertAttributeEquals(
            ['say' => 'hello'],
            'config',
            $configFactory->getConfig()
        );
    }

    /**
     * Test method
     *
     * @expectedException        \Smarttly\Config\Exception\InvalidConfigFile
     * @expectedExceptionMessage File extension cannot be auto-detected: /tmp/dummyFile1
     *
     * @return void
     */
    public function testGetExtensionFileFail()
    {
        $configFactory = new ConfigFactory();

        $refMethod = new \ReflectionMethod(ConfigFactory::class, 'getExtensionFile');
        $refMethod->setAccessible(true);

        $refMethod->invokeArgs(
            $configFactory,
            ['/tmp/dummyFile1']
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testGetExtensionFile()
    {
        $content = <<<TXT
{
    "say": "hello"
}
TXT;
        $tmpFilename = $this->generateTmpFile($content, 'json');

        $configFactory = new ConfigFactory();

        $refMethod = new \ReflectionMethod(ConfigFactory::class, 'getExtensionFile');
        $refMethod->setAccessible(true);

        $ext = $refMethod->invokeArgs(
            $configFactory,
            [$tmpFilename]
        );

        $this->unlinkFile($tmpFilename);

        $this->assertEquals('json', $ext);
    }

    /**
     * Test method
     *
     * @expectedException        \Smarttly\Config\Exception\InvalidConfigFile
     * @expectedExceptionMessage Unsupported config file extension: .log
     *
     * @return void
     */
    public function testGetReaderFail()
    {
        $configFactory = new ConfigFactory();

        $refMethod = new \ReflectionMethod(ConfigFactory::class, 'getReader');
        $refMethod->setAccessible(true);

        $refMethod->invokeArgs(
            $configFactory,
            ['log']
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testGetReaderJson()
    {
        $configFactory = new ConfigFactory();

        $refMethod = new \ReflectionMethod(ConfigFactory::class, 'getReader');
        $refMethod->setAccessible(true);

        $reader = $refMethod->invokeArgs(
            $configFactory,
            ['json']
        );

        $this->assertInstanceOf(
            '\Smarttly\Config\Reader\Json',
            $reader
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testGetReaderPhp()
    {
        $configFactory = new ConfigFactory();

        $refMethod = new \ReflectionMethod(ConfigFactory::class, 'getReader');
        $refMethod->setAccessible(true);

        $reader = $refMethod->invokeArgs(
            $configFactory,
            ['php']
        );

        $this->assertInstanceOf(
            '\Smarttly\Config\Reader\Php',
            $reader
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testCreateV1()
    {
        $contentJson = <<<TXT
{
    "say": "hello",
    "lastName": "Doe"
}
TXT;

        $contentPhp = <<<TXT
<?php

\$config = [
    'lastName' => 'Smith',
    'name' => 'John'
];
return \$config;
TXT;

        $files = [
            $this->generateTmpFile($contentJson, 'json'),
            $this->generateTmpFile($contentPhp, 'php')
        ];

        $config = (ConfigFactory::create(...$files))
            ->getConfig();

        $this->unlinkFile($files[0]);
        $this->unlinkFile($files[1]);

        $this->assertAttributeEquals(
            [
                'say' => 'hello',
                'lastName' => 'Smith',
                'name' => 'John'
            ],
            'config',
            $config
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testCreateV2()
    {
        $contentPhp1 = <<<TXT
<?php

\$config = [
    'say' => 'hello',
    'lastName' => 'Doe'
];
return \$config;
TXT;

        $contentPhp2 = <<<TXT
<?php

\$config = [
    'lastName' => 'Smith',
    'name' => 'John'
];
return \$config;
TXT;

        $files = [
            $this->generateTmpFile($contentPhp1, 'php'),
            $this->generateTmpFile($contentPhp2, 'php')
        ];

        $config = (ConfigFactory::create(...$files))
            ->getConfig();

        $this->unlinkFile($files[0]);
        $this->unlinkFile($files[1]);

        $this->assertAttributeEquals(
            [
                'say' => 'hello',
                'lastName' => 'Smith',
                'name' => 'John'
            ],
            'config',
            $config
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testCreateV3()
    {
        $contentPhp1 = <<<TXT
<?php

\$config = [
    'say' => 'hello',
    'lastName' => 'Doe'
];
return \$config;
TXT;

        $contentPhp2 = <<<TXT
<?php

\$config = [
    'lastName' => 'Smith',
    'name' => 'John'
];
return \$config;
TXT;

        $files = [
            $this->generateTmpFile($contentPhp1, 'php'),
            $this->generateTmpFile($contentPhp2, 'php')
        ];

        $config = (ConfigFactory::create($files[0], $files[1]))
            ->getConfig();

        $this->unlinkFile($files[0]);
        $this->unlinkFile($files[1]);

        $this->assertAttributeEquals(
            [
                'say' => 'hello',
                'lastName' => 'Smith',
                'name' => 'John'
            ],
            'config',
            $config
        );
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testCreateV4()
    {
        $contentPhp1 = <<<TXT
<?php

\$config = [
    'say' => 'hello',
    'lastName' => 'Doe'
];
return \$config;
TXT;

        $contentPhp2 = <<<TXT
<?php

\$config = [
    'lastName' => 'Smith',
    'name' => 'John'
];
return \$config;
TXT;

        $files = [
            $this->generateTmpFile($contentPhp1, 'php'),
            $this->generateTmpFile($contentPhp2, 'php')
        ];

        //$config = (ConfigFactory::create($files[0], $files[1]))
        //    ->getConfig();

        $factory = ConfigFactory::create();
        $config = $factory->loadFiles(...$files)
            ->getConfig();

        $this->unlinkFile($files[0]);
        $this->unlinkFile($files[1]);

        $this->assertAttributeEquals(
            [
                'say' => 'hello',
                'lastName' => 'Smith',
                'name' => 'John'
            ],
            'config',
            $config
        );
    }




    /**
     * Test method
     *
     * @return void
     */
    public function testCreateEmpty()
    {
        $config = (ConfigFactory::create())
            ->getConfig();

        $this->assertAttributeEquals(
            [],
            'config',
            $config
        );
    }
}
