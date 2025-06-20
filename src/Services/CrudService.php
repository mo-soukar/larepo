<?php

namespace Soukar\Larepo\Services;

use Soukar\Larepo\Exceptions\ModelDoesNtExistsException;

class CrudService
{
    public function generate($model, $isView, $command)
    {
        try {

            if (!FilesService::checkFile(app_path('Models/' . $model . '.php'))) {
                throw new ModelDoesNtExistsException();
            }
            $repositoryService = new RepositoryService();
            $repositoryService->generate(
                $model,
                $command
            );
            $dtoService = new DTOService();
            $dtoService->generate(
                $model,
                $command
            );
            $requestService = new RequestService();
            $requestService->generate(
                $model,
                $command
            );

            $resourceService = new ResourceService();
            $resourceService->generate(
                $model,
                $command
            );
            $controllerService = new ControllerService();
            $controllerService->generate(
                $model,
                $command
            );
            $routesService = new RoutesGeneratorService();
            $routesService->appendCrudRoutes(
                $model,
                config('larepo.controllers.namespace') . '\\' . ControllerService::getControllerName($model),
                []
            );
            $command->info("Crud Generated Successfully");
        } catch (\Throwable $throwable) {
            $command->error($throwable->getMessage());
        }
    }
}
