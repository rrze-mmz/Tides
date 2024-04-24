<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class CheckAndCreateSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-and-create-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for new settings and stores them in the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Array of setting types to process
        $settingsTypes = ['portal', 'opencast', 'streaming', 'openSearch'];

        foreach ($settingsTypes as $settingsType) {
            $this->info("Starting with {$settingsType} settings");
            $this->processSettings($settingsType);
            $this->info("Finished with {$settingsType} settings");
        }

        $this->info('All settings have been checked and created');

        return Command::SUCCESS;
    }

    /**
     * Process settings for a specific type
     */
    protected function processSettings(string $settingsType): void
    {
        // Retrieve specific settings based on type
        $settingModel = Setting::{$settingsType}();  // Using dynamic method names
        $defaultSettings = config("settings.{$settingsType}");

        // Calculate new keys and merge with existing data
        $newKeys = array_diff_key($defaultSettings, $settingModel->data);
        $settingModel->data = array_merge($settingModel->data, $newKeys);

        // Save the updated settings
        $settingModel->save();
    }
}
