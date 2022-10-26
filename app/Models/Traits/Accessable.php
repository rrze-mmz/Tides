<?php

namespace App\Models\Traits;

use App\Models\Acl;
use function auth;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function checkAcls(): bool
    {
        $acls = $this->acls;

        $check = false;
        if ($acls->isEmpty() || $acls->pluck('id')->contains(\App\Enums\Acl::PUBLIC())) {
            return true;
        }
        if (auth()->user()?->isAdmin()) {
            return true;
        }
        if ($acls->pluck('id')->contains(\App\Enums\Acl::PORTAL()) && auth()->check()) {
            $check = (($this->assets->count() > 0 && $this->is_public)
                || auth()->user()->can('view-video', $this));
        }
        if ($acls->pluck('id')->contains(\App\Enums\Acl::LMS())) {
            $check = (
                compareLMSToken($this)
                || ((auth()->check() && auth()->user()->can('view-video', $this)))
                || (auth()->check() && auth()->user()->isAdmin())
            );
        }

        return $check;
    }
}
