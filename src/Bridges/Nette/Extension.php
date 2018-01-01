<?php

namespace Authenticator\Bridges\Nette;

use Authenticator\Drivers\ArrayDriver;
use Authenticator\Drivers\DibiDriver;
use Authenticator\Drivers\NeonDriver;
use Authenticator\LoginForm;
use Nette\DI\CompilerExtension;


/**
 * Class Extension
 *
 * @author  geniv
 * @package Authenticator\Bridges\Nette
 */
class Extension extends CompilerExtension
{
    /** @var array default values */
    private $defaults = [
        'autowired'   => null,
        'source'      => null,  // Array|Neon|Dibi
        'tablePrefix' => null,  // db prefix for dibi driver
        'userlist'    => [],    // array for array driver
        'path'        => null,  // path to file for neon driver
        'classArray'  => ArrayDriver::class,
        'classNeon'   => NeonDriver::class,
        'classDibi'   => DibiDriver::class,
    ];


    /**
     * Load configuration.
     */
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        // define driver
        switch ($config['source']) {
            case 'Array':
                $builder->addDefinition($this->prefix('default'))
                    ->setFactory($config['classArray'], [$config]);
                break;

            case 'Neon':
                $builder->addDefinition($this->prefix('default'))
                    ->setFactory($config['classNeon'], [$config]);
                break;

            case 'Dibi':
                $builder->addDefinition($this->prefix('default'))
                    ->setFactory($config['classDibi'], [$config]);
                break;
        }

        // define form
        $builder->addDefinition($this->prefix('form'))
            ->setFactory(LoginForm::class);

        // if define autowired then set value
        if (isset($config['autowired'])) {
            $builder->getDefinition($this->prefix('default'))
                ->setAutowired($config['autowired']);

            $builder->getDefinition($this->prefix('form'))
                ->setAutowired($config['autowired']);
        }
    }
}
