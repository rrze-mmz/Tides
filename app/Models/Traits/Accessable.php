<?php

namespace App\Models\Traits;

use App\Models\Acl;
use function auth;
use function generateLMSToken;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use function session;

trait Accessable
{
    /**
     * Given model acls relationship
     *
     * @return MorphToMany
     */
    public function acls(): MorphToMany
    {
        return $this->morphToMany(Acl::class, 'accessable')->withTimestamps();
    }

    /**
     * Assign an acl collection to the give type
     *
     * @param  Collection  $aclsCollection
     * @return void
     */
    public function addAcls(Collection $aclsCollection): void
    {
        /*
         * Check for tags collection from post request.
         * The closure returns a tag model, where the model is either selected or created.
         * The acl model is synchronized with the type acls.
         */

        if ($aclsCollection->isNotEmpty()) {
            $this->acls()->sync($aclsCollection);
        } else {
            $this->acls()->detach();
        }
    }

    public function checkAcls(): bool
    {
        $acls = $this->acls;

        $tokenType = lcfirst(class_basename($this::class));
        $tokenTime = session()->get($tokenType.'_'.$this->id.'_time');

        $check = false;
        if ($acls->isEmpty()) {
            return true;
        }
        if (auth()->user()?->isAdmin()) {
            return true;
        }
        if ($acls->pluck('id')->contains('2') && auth()->check()) {
            $check = (($this->assets->count() > 0 && $this->is_public)
                || auth()->user()->can('view-video', $this));
        }
        if ($acls->pluck('id')->contains('4')) {
            $check = (
                session()->get($tokenType.'_'.$this->id.'_token') === generateLMSToken($this, $tokenTime)
                || ((auth()->check() && auth()->user()->can('view-video', $this)))
                || (auth()->check() && auth()->user()->isAdmin())
            );
        }

        return $check;
    }
}
