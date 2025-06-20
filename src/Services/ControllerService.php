<?php

namespace Soukar\Larepo\Services;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Support\Str;
use Soukar\Larepo\Exceptions\FileAlreadyExistsException;

class ControllerService
{
    public function generate($model, $command)
    {

        $this->generateControllerFile(
            $model
        );
        $command->info(
            self::getControllerFullPath(
                $model
            ) . ".php Created Successfully"
        );

    }

    public function generateControllerFile($model)
    {

        $fullPath = self::getControllerFullPath(
                $model
            ) . '.php';
        $name = self::getControllerName(
            $model
        );
        if (FilesService::checkFile($fullPath)) {
            throw new FileAlreadyExistsException($fullPath);
        }
        FilesService::createDirectoryIfNotExists(self::getControllersDir());
        LaravelStub::from($this->getStubsPath() . 'Controller.stub')
            ->to(self::getControllersDir())
            ->name(
                $name
            )
            ->ext('php')
            ->replaces(
                [
                    'REQUESTS_NAMESPACE'    => config('larepo.requests.namespace'),
                    'RESOURCES_NAMESPACE'    => config('larepo.resources.namespace'),
                    'INTERFACES_NAMESPACE'  => config('larepo.interfaces.namespace'),
                    'CONTROLLERS_NAMESPACE' => config('larepo.controllers.namespace'),
                    'DTO_NAMESPACE'         => config('larepo.DTO.namespace'),
                    'MODEL'                 => $model,
                    'CONTROLLER_NAME'       => $name,
                    'REPOSITORY_NAME'       => Str::camel($model) . 'Repository',
                    'DTO_NAME'              => DTOService::getDtoName($model),
                    'ADD_REQUEST'           => RequestService::getRequestName(
                        "Add",
                        $model
                    ),
                    'UPDATE_REQUEST'        => RequestService::getRequestName(
                        "Update",
                        $model
                    ),
                    'RESPONSE_TRAIT'        => config('larepo.responseTrait'),
                    'COLLECTION_RESOURCE' => ResourceService::getResourceName("Collection",$model),
                    'INFO_RESOURCE' => ResourceService::getResourceName("Info",$model),

                ]
            )
            ->generate();

    }


    private
    function getStubsPath()
    {
        return __DIR__ . '/Stubs/';
    }


    static function getControllersDir()
    {
        return app_path(config('larepo.controllers.path'));
    }

    static function getControllerName($model)
    {
        return Str::studly($model . 'Controller');
    }

    static function getControllerFullPath($model)
    {
        return self::getControllersDir() . '/' . self::getControllerName(
                $model
            );
    }
}
