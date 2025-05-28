<?php

namespace Soukar\Larepo\Services;

use Binafy\LaravelStub\Facades\LaravelStub;
use Illuminate\Support\Str;
use Soukar\Larepo\Exceptions\FileAlreadyExistsException;

class DTOService
{

    public function generate(string $model, $command): void
    {
        try {
            if (Str::endsWith(
                $model,
                'DTO'
            )) {
                $model = Str::replaceLast(
                    'DTO',
                    '',
                    $model
                );
            }
            $this->generateDTO($model);
            $command->info(
                self::getFullDtoPath($model) . ".php created Successfully"
            );
        } catch (FileAlreadyExistsException $exception) {
            $command->error($exception->getMessage());
        } catch (\Exception $exception) {
            $command->error($exception->getMessage());
        }
    }

    public function generateDTO(string $modelName): bool
    {
        $fullPath = $this->getDTODir() . '\\' . $modelName . 'DTO.php';
        if (FilesService::checkFile($fullPath)) {
            throw new FileAlreadyExistsException($fullPath);
        }
        FilesService::createDirectoryIfNotExists($this->getDTODir());
        LaravelStub::from($this->getStubsPath() . 'DTO.stub')
            ->to($this->getDTODir())
            ->name(self::getDtoName($modelName))
            ->ext('php')
            ->replaces(
                [
                    'MODEL'         => $modelName,
                    'DTO_NAMESPACE' => config('larepo.DTO.namespace'),
                ]
            )
            ->generate();
        return true;
    }


    private
    function getStubsPath()
    {
        return __DIR__ . '\\Stubs\\';
    }


    static function getDTODir()
    {
        return app_path(config('larepo.DTO.path'));
    }

    static function getDtoName($model): string
    {
        if (Str::endsWith(
            $model,
            'DTO'
        )) {
            $model = Str::replaceLast(
                'DTO',
                '',
                $model
            );
        }
        return $model . 'DTO';
    }

    static function getFullDtoPath($model): string
    {
        return self::getDTODir() . '\\' . self::getDtoName($model);
    }
}
