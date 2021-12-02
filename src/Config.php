<?php

namespace Andileong\Collection;

use Exception;
use Symfony\Component\Finder\Finder;

class Config
{

    private Finder $finder;
    private array $files = [];
    private $path;


    public function __construct($path = null)
    {
        $this->finder = new Finder();
        $this->path = $path ?? $this->getDefaultPath();
        $this->read();
    }

    public function getDefaultPath()
    {
        return config_path();
    }

    public function read()
    {
        $this->finder->files()->in($this->path)->name('*.php');
        if (!$this->finder->hasResults()) {
            throw new Exception("Cant retrieve config files!");
        }
        $this->buildFiles($this->finder);
        return $this;
    }

    public function get($key,$default = null)
    {
        return array_get($this->files,$key,$default);
    }

    public function all()
    {
        return $this->files;
    }

    private function buildFiles(Finder $finder)
    {
        $files = [];
        foreach ($finder as $file) {
            $fileNameWithExtension = $file->getRelativePathname();
            $key = substr(  str_replace("\\" , '.' , $fileNameWithExtension) , 0 , -4 );
            $files[$key] = require $file->getRealPath();
        }
        $this->files = $files;
    }

}