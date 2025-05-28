<?php

namespace Soukar\Larepo\Console\Commands;

use Illuminate\Console\Command;

class MakeCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larepo:crud {model} {--view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');
        $crudService = new \Soukar\Larepo\Services\CrudService();
        $crudService->generate(
            $model,
            $this->option('view'),
            $this
        );

    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'model' => [
                'Which model ?',
                'E.g. User',
            ],
        ];
    }
}
