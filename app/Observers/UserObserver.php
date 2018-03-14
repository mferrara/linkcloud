<?php


namespace App\Observers;

use App\Notifications\AdminUserSignup;
use App\User;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Get admin user
        $admin_user = User::where('email', 'mferrara@gmail.com')->first();

        // Send notification to admin that a new user was created
        $admin_user->notify(new AdminUserSignup($user));
    }
}