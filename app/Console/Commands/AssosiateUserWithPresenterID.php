<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\Presenter;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AssosiateUserWithPresenterID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:link-user-with-presenter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Links an existing user with an existing presenter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Start to iterating over employees');
        $moderators = User::byRole(Role::MEMBER);
        $bar = $this->output->createProgressBar($moderators->count());
        $this->info("Found {$moderators->count()} members");
        $bar->start();
        $moderators->get()->each(function ($moderator) use ($bar) {
            $presenter = Presenter::where(function ($query) use ($moderator) {
                $query->where('username', $moderator->username)
                    ->orWhereRaw('LOWER(email) = ?', [Str::lower($moderator->email)]);
            })->first();
            $this->info("Found a presenter for user:{$moderator->getFullNameAttribute()}");
            if (! is_null($presenter) && is_null($moderator->presenter_id)) {
                $this->newLine();
                $moderator->presenter_id = $presenter->id;
                $moderator->save();
                $this->info("Presenter ID is set for user:{$moderator->getFullNameAttribute()}");
            } else {
                $this->info('User already has a presenter ID');
            }
            $this->newLine();
            $bar->advance();
        });
        $bar->finish();

        return Command::SUCCESS;
    }
}
