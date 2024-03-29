<?php

namespace App\Blog\Policies;

use App\Blog\Enums\StatusEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return true;
    }
 
    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param \App\Models\Post $post
     * @return mixed
     */
    public function view(?User $user, Post $post)
    {
        return $post->status === StatusEnum::STATUS_ACTIVE || $user && $user->role === 'admin' || $user && $post->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param \App\Models\Post $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        return $user->role === 'admin' || $post->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param \App\Models\Post $post
     * @return mixed
     */
    public function delete(User $user, Post $post)
    {
        return $user->role === 'admin' || $post->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param \App\Models\Post $post
     * @return mixed
     */
    public function restore(User $user, Post $post)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param \App\Models\Post $post
     * @return mixed
     */
    public function forceDelete(User $user, Post $post)
    {
        return $user->role === 'admin';
    }
}
