<?php

    use App\Mail\MyTestEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Artisan;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\FrontendController;
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\MessageController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\WishlistController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\ProductReviewController;

    use App\Http\Controllers\NotificationController;
    use App\Http\Controllers\HomeController;
use \UniSharp\LaravelFilemanager\Lfm;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    // CACHE CLEAR ROUTE
    Route::get('cache-clear', function () {
        Artisan::call('optimize:clear');
        request()->session()->flash('success', 'Successfully cache cleared.');
        return redirect()->back();
    })->name('cache.clear');


    Route::get('storage-link',[AdminController::class,'storageLink'])->name('storage.link');


    Auth::routes(['register' => false]);

    Route::get('user/login', [FrontendController::class, 'login'])->name('login.form');
    Route::post('user/login', [FrontendController::class, 'loginSubmit'])->name('login.submit');
    Route::get('user/logout', [FrontendController::class, 'logout'])->name('user.logout');

    Route::get('user/register', [FrontendController::class, 'register'])->name('register.form');
    Route::post('user/register', [FrontendController::class, 'registerSubmit'])->name('register.submit');

    Route::get('login/{provider}/', [LoginController::class, 'redirect'])->name('login.redirect');
    Route::get('login/{provider}/callback/', [LoginController::class, 'Callback'])->name('login.callback');

    Route::get('/', [FrontendController::class, 'home'])->name('home');


    Route::get('/home', [FrontendController::class, 'index']);
    Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
    Route::post('/contact/message', [MessageController::class, 'store'])->name('contact.store');
    Route::get('product-detail/{slug}', [FrontendController::class, 'productDetail'])->name('product-detail');
    Route::post('/product/search', [FrontendController::class, 'productSearch'])->name('product.search');
    Route::get('/product-cat/{slug}', [FrontendController::class, 'productCat'])->name('product-cat');
    Route::get('/product-sub-cat/{slug}/{sub_slug}', [FrontendController::class, 'productSubCat'])->name('product-sub-cat');
    Route::get('/product-brand/{slug}', [FrontendController::class, 'productBrand'])->name('product-brand');

    Route::get('/add-to-cart/{slug}', [CartController::class, 'addToCart'])->name('add-to-cart')->middleware('user');
    Route::post('/add-to-cart', [CartController::class, 'singleAddToCart'])->name('single-add-to-cart')->middleware('user');
    Route::get('cart-delete/{id}', [CartController::class, 'cartDelete'])->name('cart-delete');
    Route::post('cart-update', [CartController::class, 'cartUpdate'])->name('cart.update');

    Route::get('/cart', function () {
        return view('frontend.pages.cart');
    })->name('cart');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('user');

    Route::get('/product-grids', [FrontendController::class, 'productGrids'])->name('product-grids');
    Route::get('/product-lists', [FrontendController::class, 'productLists'])->name('product-lists');


    Route::get('/product/track', [OrderController::class, 'orderTrack'])->name('order.track');
    Route::post('product/track/order', [OrderController::class, 'productTrackOrder'])->name('product.track.order');
   


    Route::resource('/review', 'ProductReviewController');
    Route::post('product/{slug}/review', [ProductReviewController::class, 'store'])->name('review.store');


    Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'admin']], function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin');
        Route::get('/file-manager', function () {
            return view('backend.layouts.file-manager');
        })->name('file-manager');
        Route::resource('users', 'UsersController');
        Route::resource('banner', 'BannerController');
        Route::resource('brand', 'BrandController');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin-profile');
        Route::post('/profile/{id}', [AdminController::class, 'profileUpdate'])->name('profile-update');
        Route::resource('/category', 'CategoryController');
        Route::resource('/product', 'ProductController');
        Route::post('/category/{id}/child', 'CategoryController@getChildByParent');
        Route::resource('/message', 'MessageController');
        Route::get('/message/five', [MessageController::class, 'messageFive'])->name('messages.five');
        Route::resource('/order', 'OrderController');
        Route::get('order/pdf/{id}', [OrderController::class, 'pdf'])->name('order.pdf');
        Route::get('settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('setting/update', [AdminController::class, 'settingsUpdate'])->name('settings.update');
        Route::get('/notification/{id}', [NotificationController::class, 'show'])->name('admin.notification');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('all.notification');
        Route::delete('/notification/{id}', [NotificationController::class, 'delete'])->name('notification.delete');
        Route::get('change-password', [AdminController::class, 'changePassword'])->name('change.password.form');
        Route::post('change-password', [AdminController::class, 'changPasswordStore'])->name('change.password');
    });

    Route::get('/wishlist/{slug}', [WishlistController::class, 'wishlist'])->name('add-to-wishlist')->middleware('user');

    Route::post('cart/order', [OrderController::class, 'store'])->name('cart.order');
    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        Lfm::routes();
    });
