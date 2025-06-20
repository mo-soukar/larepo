<?php

namespace Soukar\Larepo\Services;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Support\Str;
use Soukar\Larepo\Exceptions\FileAlreadyExistsException;

class ResourceService
{
    public function generate($model, $command)
    {

        $this->generateRequestFile(
            "Collection",
            $model
        );
        $command->info(
            self::getResourceFullPath(
                "Collection",
                $model
            ) . ".php Created Successfully"
        );
        $this->generateRequestFile(
            "Info",
            $model
        );
        $command->info(
            self::getResourceFullPath(
                "Info",
                $model
            ) . ".php Created Successfully"
        );
    }

    public function generateRequestFile($type, $model)
    {

        $fullPath = self::getResourceFullPath(
                $type,
                $model
            ) . '.php';
        $name = self::getResourceName(
            $type,
            $model
        );
        if (FilesService::checkFile($fullPath)) {
            throw new FileAlreadyExistsException($fullPath);
        }
        FilesService::createDirectoryIfNotExists(self::getResourcesDir());
        LaravelStub::from($this->getStubsPath() . 'Resource.stub')
            ->to(self::getResourcesDir())
            ->name(
                $name
            )
            ->ext('php')
            ->replaces(
                [
                    'RESOURCE_NAME'       => $name,
                    'RESOURCES_NAMESPACE' => config('larepo.resources.namespace'),
                ]
            )
            ->generate();

    }


    private
    function getStubsPath()
    {
        return __DIR__ . '/Stubs/';
    }


    static function getResourcesDir()
    {
        return app_path(config('larepo.resources.path'));
    }

    static function getResourceName($type, $model)
    {
        return Str::studly(  $model .$type. 'Resource');
    }

    static function getResourceFullPath($type, $model)
    {
        return self::getResourcesDir() . '/' . self::getResourceName(
                $type,
                $model
            );
    }
}
