<?php


namespace App\Observers;

use App\Notifications\AdminUserSignup;
use App\Notifications\UserSignup;
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
        // Send the user welcome/registration email
        $user->notify(new UserSignup($user));

        // Get admin user
        $admin_user = User::where('email', 'mferrara@gmail.com')->first();

        // Send notification to admin that a new user was created
        $admin_user->notify(new AdminUserSignup($user));
    }
}