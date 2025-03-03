<?php

namespace Soukar\Larepo\Services;

use Binafy\LaravelStub\Facades\LaravelStub;

class DTOService
{

    public function generate(string $model , $command) : void
    {

        $this->generateDTO($model);
        $command->info($this->getDTODir().$model."RepositoryInterface.php created Successfully") ;

    }


    public function generateDTO(string $modelName)
    {
        FilesService::createDirectoryIfNotExists($this->getDTODir());
        LaravelStub::from($this->getStubsPath().'DTO.stub')
            ->to($this->getDTODir())
            ->name($modelName.'DTO')
            ->ext('php')
            ->replaces([
                'MODEL' => $modelName,
                'DTO_NAMESPACE' => config('larepo.DTO.namespace')
            ])
            ->generate();
    }


    private function getStubsPath()
    {
        return __DIR__.'/Stubs/';
    }

    private function getDTODir()
    {
        return app_path(config('larepo.DTO.path'));
    }

}
