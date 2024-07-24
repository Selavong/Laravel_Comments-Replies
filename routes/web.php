<?php

use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\Admin\ActorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\MovieController;
use App\Http\Controllers\Admin\DirectorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UsersController;
// use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\CommentController as UserCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdatePasswordController;
use Faker\Guesser\Name;
use App\Http\Controllers\UserProfileController;
use App\Models\Movie;
use Illuminate\Http\Request;

// make it to be conflect
// Hello Welcome to merge conflict
// Public route
Route::get('/', function () {
    return view('welcome');
});




// Auth route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/movies', function () {
        $movies = Movie::get();
        return view('main.index',compact('movies'));
    });

    Route::get('/profile/setting', [UserController::class, 'profile']);

    // ajax upload image
    Route::post('/profile/upload', [UserController::class, 'upload']);

    Route::post('/comment', [UserCommentController::class, 'store'])->name('comments.store');

    Route::delete('/comments/{id}', [UserCommentController::class, 'destroy'])->name('comments.destroy');

    Route::put('/comments/update/{id}', [UserCommentController::class, 'update'])->name('comments.update');

    // Normal User Profile
    // Route::get('/profile', function () {
    //     return view('main.profile');
    // })->name('profile');

    // Add middleware 'auth' to protect profile route for logged in users
    // Route::middleware('auth')->group(function () {
        Route::get('/user/profile', [UserProfileController::class, 'profile'])->name('user.profile');
        Route::patch('/user/userprofileupdate', [UserProfileController::class, 'upload'])->name('user.profile.update');
        Route::post('/user/userupdatePassword', [UserController::class, 'changePasswordSave'])->name('user.update.password');
        Route::post('/user-upload-profile-photo', [UserProfileController::class, 'upload']);
    // });

    Route::get('/detail/{id}', function (Request $request, int $id) {
        $movie = Movie::find($id);
        $comments = \App\Models\Comment::where('movie_id', $id)
                                    ->with(['replies.user', 'user']) // Load replies and users
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
        return view('main.details')->with(compact('movie', 'comments'));
    })->name('detail');

    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    Route::put('/comments/replies/{reply}', [CommentController::class, 'updateReply'])->name('comments.replies.update');
    Route::delete('/comments/replies/{reply}', [CommentController::class, 'deleteReply'])->name('comments.replies.delete');

});

// Auth route + is Admin user
Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin');
    Route::get('/admin/actor', [ActorController::class, 'index'])->name('admin.actor');
    Route::get('/admin/movie', [MovieController::class, 'index'])->name('admin.movie');
    Route::get('/admin/director', [DirectorController::class, 'index'])->name('admin.director');
    Route::get('/admin/users', [UsersController::class, 'index'])->name('admin.users');
    Route::get('/admin/review', [ReviewController::class, 'index'])->name('admin.review');
    Route::get('/admin/comment', [CommentController::class, 'index'])->name('admin.comment');


    // User Admin
    Route::get('admin/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::patch('admin/profileupdate', [UserController::class, 'update'])->name('profile.update');
    Route::post('admin/upload-profile-photo', [UserController::class, 'uploadProfilePhoto']);
    Route::post('admin/updatePassword', [UserController::class, 'changePasswordSave'])->name('update.password');
});
Route::get('/catalog', function () {
    return view('main.catalog');
})->name('catalog');

Route::get('/home', function () {
    return view('main.index');
})->name('home');

Route::get('/pricing', function () {
    return view('main.pricing');
})->name('pricing');

Route::get('/live', function () {
    return view('main.live');
})->name('live');

Route::get('/aboutus', function () {
    return view('main.about');
})->name('about');

Route::get('/contact', function () {
    return view('main.contacts');
})->name('contact');

Route::get('/interview', function () {
    return view('main.interview');
})->name('interview');

Route::get('/privacy', function () {
    return view('main.privacy');
})->name('privacy');

Route::get('/404', function () {
    return view('main.404');
})->name('404');

