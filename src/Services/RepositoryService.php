<?php

namespace Soukar\Larepo\Services;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RepositoryService
{


    public function generate(string $model , Command $command) : void
    {
        $this->generateRepositoryInterface($model);

        $command->info( $this->getInterfacesDir().$model."RepositoryInterface.php created Successfully");

        $this->generateRepository($model);

        $command->info(  $this->getRepositoriesDir().$model."Repository.php created Successfully");

        $this->generateRepositoryProxy($model);

        $command->info( $this->getProxiesDir().$model."RepositoryProxy.php created Successfully" );

    }


    public function generateRepositoryInterface(string $modelName)
    {
        FilesService::createDirectoryIfNotExists($this->getInterfacesDir());
        LaravelStub::from($this->getStubsPath().'RepositoryInterface.stub')
            ->to($this->getInterfacesDir())
            ->name($modelName.'RepositoryInterface')
            ->ext('php')
            ->replaces([
                'MODEL' => $modelName,
                'INTERFACES_NAMESPACE' => config('larepo.interfaces.namespace'),


            ])
            ->generate();
    }

    public function generateRepository(string $modelName)
    {

        FilesService::createDirectoryIfNotExists($this->getRepositoriesDir());
        LaravelStub::from($this->getStubsPath().'Repository.stub')
            ->to($this->getRepositoriesDir())
            ->name($modelName.'Repository')
            ->ext('php')
            ->replaces([
                'MODEL' => $modelName,
                'INTERFACES_NAMESPACE' => config('larepo.interfaces.namespace'),
                'REPOSITORIES_NAMESPACE' => config('larepo.repositories.namespace'),

            ])
            ->generate();
    }

    public function generateRepositoryProxy(string $modelName)
    {
        FilesService::createDirectoryIfNotExists($this->getProxiesDir());
        LaravelStub::from($this->getStubsPath().'RepositoryProxy.stub')
            ->to($this->getProxiesDir())
            ->name($modelName.'RepositoryProxy')
            ->ext('php')
            ->replaces([
                'MODEL' => $modelName,
                'INTERFACES_NAMESPACE' => config('larepo.interfaces.namespace'),
                'REPOSITORIES_NAMESPACE' => config('larepo.repositories.namespace'),
            ])
            ->generate();
    }


    private function getStubsPath()
    {
        return __DIR__.'/Stubs/';
    }

    private function getInterfacesDir()
    {
        return app_path(config('larepo.interfaces.path'));
    }

    private function getRepositoriesDir()
    {
        return app_path(config('larepo.repositories.path'));
    }

    private function getProxiesDir()
    {
        $repositoriesDir = $this->getRepositoriesDir();
        return $repositoriesDir . (Str::endsWith($repositoriesDir,'/') ? '':'/' ) .'Proxy';
    }


}
