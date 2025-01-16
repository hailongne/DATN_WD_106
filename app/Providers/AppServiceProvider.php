<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ShoppingCart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $categories = Category::all();
        View::share('categories', $categories);
        Paginator::useBootstrapFive();
        View::composer('*', function ($view) {
            $user = Auth::user();
            $cartCount = 0;
    
            if ($user) {
                $shoppingCart = ShoppingCart::where('user_id', $user->user_id)->first();
                if ($shoppingCart) {
                    $cartCount = $shoppingCart->cartItems->sum('qty');
                }
            }
    
            $view->with('cartCount', $cartCount);
        });
    }
}
