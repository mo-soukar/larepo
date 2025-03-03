<?php

namespace Soukar\Larepo;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Soukar\Larepo\Services\RepositoryService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $interfacesNameSpace = config('larepo.interfaces.namespace');
        $proxiesNamesSpace = config('larepo.repositories.namespace').'\Proxy';
        $interfacesPath = app_path(config('larepo.interfaces.path'));

        if(File::exists($interfacesPath)){
            $interfacesFiles = File::files($interfacesPath);

            foreach ($interfacesFiles as $interface)
            {
                $interfaceName = $interface->getFilenameWithoutExtension();
                $proxyName = Str::replace('Interface','Proxy',$interfaceName);
                $proxyNameSpace = $proxiesNamesSpace.'\\'.$proxyName;
                $interfaceNameSpace = $interfacesNameSpace.'\\'.$interfaceName;

                $this->app->bind($interfaceNameSpace,$proxyNameSpace);
            }
        }

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
