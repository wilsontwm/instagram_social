<?php

namespace App\Providers;

use App\CashoutRequest;
use App\Gift;
use App\Note;
use App\User;
use App\Policies\NotePolicy;
use App\Policies\UserPolicy;
use App\Policies\GiftPolicy;
use App\Policies\UserGiftPolicy;
use App\Policies\CashoutRequestPolicy;
use App\UserGift;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class         =>  UserPolicy::class,
        Note::class         =>  NotePolicy::class,
        Gift::class         =>  GiftPolicy::class,
        UserGift::class     =>  UserGiftPolicy::class,
        CashoutRequest::class   => CashoutRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
