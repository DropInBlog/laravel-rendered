<?php

use Illuminate\Support\Facades\Route;
use DropInBlog\Laravel\Http\Controllers\BlogController;

Route::prefix(config('dropinblog.path', 'blog'))->group(function () {
    // Blog index
    Route::get('/', [BlogController::class, 'index'])->name('dropinblog.index');
    Route::get('/page/{page}', [BlogController::class, 'index'])->name('dropinblog.index.page');

    // Category routes
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('dropinblog.category');
    Route::get('/category/{slug}/page/{page}', [BlogController::class, 'category'])->name('dropinblog.category.page');

    // Author routes
    Route::get('/author/{slug}', [BlogController::class, 'author'])->name('dropinblog.author');
    Route::get('/author/{slug}/page/{page}', [BlogController::class, 'author'])->name('dropinblog.author.page');

    // Feed routes
    Route::get('/feed', [BlogController::class, 'feed'])->name('dropinblog.feed');
    Route::get('/feed/category/{slug}', [BlogController::class, 'feedCategory'])->name('dropinblog.feed.category');
    Route::get('/feed/author/{slug}', [BlogController::class, 'feedAuthor'])->name('dropinblog.feed.author');

    // Sitemap
    Route::get('/sitemap.xml', [BlogController::class, 'sitemap'])->name('dropinblog.sitemap');

    // Individual post (must be last to avoid conflicts)
    Route::get('/{slug}', [BlogController::class, 'post'])->name('dropinblog.post');
});
