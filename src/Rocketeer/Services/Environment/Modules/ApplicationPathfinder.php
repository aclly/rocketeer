<?php

/*
 * This file is part of Rocketeer
 *
 * (c) Maxime Fabre <ehtnam6@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Rocketeer\Services\Environment\Modules;

use Exception;
use Illuminate\Support\Str;

class ApplicationPathfinder extends AbstractPathfinderModule
{
    /**
     * Get the path to the application.
     *
     * @return string
     */
    public function getApplicationPath()
    {
        $applicationPath = $this->modulable->getPath('app').'/' ?: $this->modulable->getBasePath();
        $applicationPath = $this->modulable->unifySlashes($applicationPath);

        return $applicationPath;
    }

    /**
     * Get the path to the .rocketeer folder
     *
     * @return string
     */
    public function getRocketeerPath()
    {
        // Get path to configuration
        $configuration = $this->container->get('path.rocketeer.config');

        return $this->modulable->unifyLocalSlashes($configuration);
    }

    /**
     * Get the path to the configuration folder.
     *
     * @return string
     */
    public function getConfigurationPath()
    {
        return $this->getRocketeerPath().DS.'config';
    }

    /**
     * Get the path to the user's PSR4 folder
     *
     * @return string
     */
    public function getAppFolderPath()
    {
        $namespace = ucfirst($this->config->get('application_name'));

        return $this->getRocketeerPath().DS.$namespace;
    }

    /**
     * @return string
     */
    public function getDotenvPath()
    {
        $path = $this->modulable->getBasePath().'.env';

        return $this->modulable->unifyLocalSlashes($path);
    }

    /**
     * Get path to the storage folder.
     *
     * @return string
     */
    public function getStoragePath()
    {
        // If no path is bound, default to the Rocketeer folder
        if (!$this->container->has('path.storage')) {
            return '.rocketeer';
        }

        // Unify slashes
        $storage = $this->container->get('path.storage');
        $storage = $this->modulable->unifySlashes($storage);
        $storage = str_replace($this->modulable->getBasePath(), null, $storage);

        return $storage;
    }

    /**
     * @return string[]
     */
    public function getProvided()
    {
        return [
            'getAppFolderPath',
            'getApplicationPath',
            'getConfigurationPath',
            'getDotenvPath',
            'getStoragePath',
            'getUserHomeFolder',
        ];
    }
}