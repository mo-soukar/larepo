<?php

namespace Soukar\Larepo\Console\Commands;

use Illuminate\Console\Command;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larepo:repository {model}';

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

        $repositoryService = new \Soukar\Larepo\Services\RepositoryService();

        $repositoryService->generate($model , $this);

    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'model' => ['Which model ?', 'E.g. User'],
        ];
    }
}
