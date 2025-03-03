<?php

namespace Soukar\Larepo\Console\Commands;

use Illuminate\Console\Command;

class MakeDTOCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larepo:dto {model}';

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

        $dtService = new \Soukar\Larepo\Services\DTOService();

        $dtService->generate($model,$this);

    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'model' => ['Which model ?', 'E.g. User'],
        ];
    }
}
