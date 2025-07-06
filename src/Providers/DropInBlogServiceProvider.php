<?php

namespace DropInBlog\Laravel\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DropInBlogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'dropinblog');

        $this->publishes([
            __DIR__.'/../../config/dropinblog.php' => config_path('dropinblog.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/dropinblog'),
        ], 'views');

        Blade::directive('dropInBlogHead', function () {
            return "<?php echo \$__env->yieldPushContent('dropinblog-head'); ?>";
        });

        Blade::directive('isDropInBlog', function () {
            return "<?php if(isset(\$isDropInBlog) && \$isDropInBlog === true): ?>";
        });

        Blade::directive('endisDropInBlog', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('notDropInBlog', function () {
            return "<?php if(!isset(\$isDropInBlog) || \$isDropInBlog !== true): ?>";
        });

        Blade::directive('endnotDropInBlog', function () {
            return "<?php endif; ?>";
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/dropinblog.php', 'dropinblog'
        );
    }
}
