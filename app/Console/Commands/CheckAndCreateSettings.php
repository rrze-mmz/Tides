<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

use function Laravel\Prompts\select;

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
        $settingsTypes = ['all', 'portal', 'opencast', 'streaming', 'openSearch'];
        $settingToBeChecked = select(
            label: 'First select the setting you want to update:',
            options: $settingsTypes,
            default: 'portal',
            hint: 'Series/Clips/Assets/Users may take longer as expected'
        );

        if ($settingToBeChecked === 'all') {
            $this->info('Start migrating all settings');
            foreach (Arr::except($settingsTypes, [0]) as $settingType) {
                $this->info("Starting with {$settingType} settings");
                $this->processSettings($settingType);
                $this->info("Finished with {$settingType} settings");
            }
        } else {
            $this->info("Starting with {$settingToBeChecked} settings");
            $this->processSettings($settingToBeChecked);
            $this->info("Finished with {$settingToBeChecked} settings");
        }

        $this->info($settingToBeChecked.'settings have been checked and created');

        return Command::SUCCESS;
    }

    /**
     * Process settings for a specific type
     */
    protected function processSettings(string $settingsType): void
    {
        // Retrieve specific settings based on type
        $settingModel = Setting::firstOrCreate(
            ['name' => $settingsType],
            ['data' => config("settings.$settingsType")]
        );  // Using dynamic method names
        $defaultSettings = config("settings.{$settingsType}");

        // Calculate new keys and merge with existing data
        $newKeys = array_diff_key($defaultSettings, $settingModel->data);
        $settingModel->data = array_merge($settingModel->data, $newKeys);

        // Save the updated settings
        $settingModel->save();
    }
}
