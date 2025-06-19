<?php

namespace Soukar\Larepo\Services;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Support\Str;
use Soukar\Larepo\Exceptions\FileAlreadyExistsException;

class RequestService
{
    public function generate($model, $command)
    {

        $this->generateRequestFile(
            "Add",
            $model
        );
        $command->info(
            self::getRequestFullPath(
                "Add",
                $model
            ) . ".php Created Successfully"
        );
        $this->generateRequestFile(
            "Update",
            $model
        );
        $command->info(
            self::getRequestFullPath(
                "Update",
                $model
            ) . ".php Created Successfully"
        );
    }

    public function generateRequestFile($type, $model)
    {

        $fullPath = self::getRequestFullPath(
                $type,
                $model
            ) . '.php';
        $name = self::getRequestName(
            $type,
            $model
        );
        if (FilesService::checkFile($fullPath)) {
            throw new FileAlreadyExistsException($fullPath);
        }
        FilesService::createDirectoryIfNotExists(self::getRequestsDir());
        LaravelStub::from($this->getStubsPath() . 'Request.stub')
            ->to(self::getRequestsDir())
            ->name(
                $name
            )
            ->ext('php')
            ->replaces(
                [
                    'REQUEST_NAME'       => $name,
                    'REQUESTS_NAMESPACE' => config('larepo.requests.namespace'),
                    'DTO_NAME'           => DTOService::getDtoName($model),
                    'DTO_NAMESPACE'      => config('larepo.DTO.namespace'),
                    'DTO_RULES'          => $type == "Add" ? 'addRules($this)' : 'updateRules($this)',
                ]
            )
            ->generate();

    }


    private
    function getStubsPath()
    {
        return __DIR__ . '/Stubs/';
    }


    static function getRequestsDir()
    {
        return app_path(config('larepo.requests.path'));
    }

    static function getRequestName($type, $model)
    {
        return Str::studly($type . $model . 'Request');
    }

    static function getRequestFullPath($type, $model)
    {
        return self::getRequestsDir() . '/' . self::getRequestName(
                $type,
                $model
            );
    }
}
