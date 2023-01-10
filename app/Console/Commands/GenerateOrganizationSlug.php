<?php

namespace App\Console\Commands;

use App\Models\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateOrganizationSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organizations:slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the slugs for all organizations if slug is null';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $organizations = Organization::all();

        $organizations->each(function ($organization) {
            if (is_null($organization->slug)) {
                $slug = Str::of($organization->name)->slug('-');
                if ($counter = Organization::whereRaw('slug like (?)', ["{$slug}%"])->count()) {
                    $slug = $slug.'-'.$counter + 1;
                }
                $organization->slug = $slug;
                $organization->save();
            }
        });

        $this->info('Finish organizations slugs');

        return Command::SUCCESS;
    }
}
