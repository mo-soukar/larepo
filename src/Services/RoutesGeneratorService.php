<?php

namespace Soukar\Larepo\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RoutesGeneratorService
{
    protected $routesPath;


    public function __construct(private $isWeb = false)
    {
        $this->routesPath = base_path('routes/' . ($this->isWeb ? 'web' : 'api') . '.php');
    }

    public function appendCrudRoutes($model, $controller, $options = [])
    {
        // Generate route code
        $routeCode = $this->generateRouteCode(
            $model,
            $controller,
            $options
        );
        // Append to routes file
        $this->appendToRoutesFile($routeCode);
    }

    protected function generateRouteCode($model, $controller, $options)
    {
        $modelPlural = Str::plural(Str::kebab($model));
        $middleware = isset($options['middleware'])
            ? "->middleware('" . implode(
                "','",
                (array)$options['middleware']
            ) . "')"
            : '';
        $except = isset($options['except'])
            ? "->except(['" . implode(
                "','",
                (array)$options['except']
            ) . "'])"
            : '';
        $only = isset($options['only'])
            ? "->only(['" . implode(
                "','",
                (array)$options['only']
            ) . "'])"
            : '';
        $prefix = isset($options['prefix'])
            ? "->prefix('{$options['prefix']}')"
            : '';
        return "\nRoute::" . ($this->isWeb ? "resources" : "apiResource") . "('{$modelPlural}', \\{$controller}::class){$middleware}{$except}{$only}{$prefix};";
    }

    protected function appendToRoutesFile($content)
    {
        $routesContent = File::get($this->routesPath);
        // Don't add if already exists
        if (str_contains(
            $routesContent,
            $content
        )) {
            return false;
        }
        if (str_contains(
            $routesContent,
            '?>'
        )) {
            $routesContent = str_replace(
                '?>',
                $content . "\n?>",
                $routesContent
            );
        } else {
            $routesContent .= $content;
        }
        File::put(
            $this->routesPath,
            $routesContent
        );
        return true;
    }
}
