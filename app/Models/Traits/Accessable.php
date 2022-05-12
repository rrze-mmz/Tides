<?php


namespace App\Models\Traits;

use App\Models\Acl;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use function auth;
use function generateLMSToken;
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

    public function addAcls(Collection $aclsCollection)
    {
        /*
         * Check for tags collection from post request.
         * The closure returns a tag model, where the model is either selected or created.
         * The tag model is synchronized with the clip tags.
         * In case the collection is empty assumed that clip has no tags and delete them
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
        $tokenTime = session()->get($tokenType . '_' . $this->id . '_time');

        $check = false;
        if ($acls->isEmpty()) {
            return true;
        }
        if ($acls->pluck('id')->contains('1')) {
            $check = (auth()->check() && auth()->user()->can('view-video', $this));
        }
        if ($acls->pluck('id')->contains('4')) {
            $check = (
                session()->get($tokenType . '_' . $this->id . '_token') === generateLMSToken($this, $tokenTime)
                || ((auth()->check() && auth()->user()->can('view-video', $this)))
                || (auth()->check() && auth()->user()->isAdmin())
            );
        }

        return $check;
    }
}
