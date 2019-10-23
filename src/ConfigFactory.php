<?php
/**
 * ConfigFactory
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
namespace Smarttly\Config;

use Smarttly\Config\Config;
use Smarttly\Config\Exception\InvalidConfigFile;
use Smarttly\Config\Reader\ReaderInterface;

/**
 * ConfigFactory
 *
 * @category  Smarttly/Config
 * @package   Smarttly/Config
 * @author    Federico Lozada Mosto <mosto.federico@gmail.com>
 * @copyright 2018 Federico Lozada Mosto <mosto.federico@gmail.com>
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link      https://github.com/mostofreddy
 */
class ConfigFactory
{
    protected $readers = [
        'php' => '\Smarttly\Config\Reader\Php',
        'json' => '\Smarttly\Config\Reader\Json'
    ];
    protected $config;

    /**
     * Construct
     *
     * @param [string] ...$files Paths
     */
    public function __construct(string ...$files)
    {
        $this->config = new Config();
        $this->loadFiles(...$files);
    }

    /**
     * Create config object
     *
     * @param [string] ...$files Paths
     *
     * @static
     * @return self
     */
    public static function create(string ...$files): self
    {
        return new static(...$files);
    }

    /**
     * Return config instance
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Load files
     *
     * @param [string] ...$files Paths
     *
     * @return self
     */
    public function loadFiles(string ...$files): self
    {
        foreach ($files as $file) {
            $this->load($file);
        }
        return $this;
    }

    /**
     * Load file
     *
     * @param string $file Path
     *
     * @return void
     */
    public function load(string $file): void
    {
        $reader = $this->getReader(
            $this->getExtensionFile($file)
        );

        $this->config->merge(
            new Config($reader->fromFile($file))
        );
    }

    /**
     * Get extension file
     *
     * @param string $file Filename
     *
     * @throws InvalidConfigFile if file extension cannot be auto-detecte
     * @return string
     */
    protected function getExtensionFile(string $file): string
    {
        $pathinfo = pathinfo($file);

        if (!isset($pathinfo['extension'])) {
            throw new InvalidConfigFile(
                sprintf('File extension cannot be auto-detected: %s', $file)
            );
        }

        return $pathinfo['extension'];
    }

    /**
     * Get reader instance
     *
     * @param string $extension Extension file
     *
     * @throws InvalidConfigFile if unsupported config file extension
     * @return ReaderInterface
     */
    protected function getReader(string $extension): ReaderInterface
    {
        static $readerInstances = [];

        if (!isset($readerInstances[$extension])) {
            if (!isset($this->readers[$extension])) {
                throw new InvalidConfigFile(
                    sprintf('Unsupported config file extension: .%s', $extension)
                );
            }
            $class = $this->readers[$extension];
            $readerInstances[$extension] = new $class;
        }

        return $readerInstances[$extension];
    }
}
