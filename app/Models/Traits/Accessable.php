<?php

namespace App\Models\Traits;

use App\Models\Acl;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function auth;

trait Accessable
{
    /**
     * Assign an acl collection to the give type
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
     * Given model acls relationship
     */
    public function acls(): MorphToMany
    {
        return $this->morphToMany(Acl::class, 'accessable')->withTimestamps();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function checkAcls(): bool
    {
        $acls = $this->acls;

        if ($acls->isEmpty() || $acls->pluck('id')->contains(\App\Enums\Acl::PUBLIC())) {
            return true;
        }

        if (auth()->user()?->isAdmin()) {
            return true;
        }
        if ($acls->pluck('id')->contains(\App\Enums\Acl::PORTAL()) && auth()->check()) {
            return ($this->assets()->count() > 0 && $this->is_public)
                || auth()->user()->can('view-video', $this);
        }
        if ($acls->pluck('id')->contains(\App\Enums\Acl::LMS())
            || $acls->pluck('id')->contains(\App\Enums\Acl::PASSWORD())) {
            return
                 checkAccessToken($this)
                 || ((auth()->check() && auth()->user()->can('view-video', $this)))
                 || (auth()->check() && auth()->user()->isAdmin());
        }

        return false;
    }
}
