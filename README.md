# DropInBlog Laravel Rendered Package

This package allows you to easily integrate DropInBlog's rendered content into your Laravel application. It fetches pre-rendered HTML from DropInBlog's API and seamlessly integrates it into your Laravel views.

## Requirements

- PHP 8.2 or higher
- Laravel 11.0 or higher
- DropInBlog account with API access

## Installation

1. Install the package via composer:

```bash
composer require dropinblog/laravel-rendered
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --provider="DropInBlog\\Laravel\\Providers\\DropInBlogServiceProvider"
```

3. Add your DropInBlog credentials to your `.env` file:

```
DROPINBLOG_ID=your-dropinblog-id
DROPINBLOG_API_TOKEN=your-dropinblog-api-token
```

## Configuration

The package publishes a configuration file at `config/dropinblog.php` with the following options:

```php
return [
    // DropInBlog API Configuration
    'id' => env('DROPINBLOG_ID'),
    'api_token' => env('DROPINBLOG_API_TOKEN'),

    // View Configuration
    'layout' => 'layouts.app',
    'sections' => [
        'content' => 'content',
    ],

    // Feed Configuration
    'feed' => [
        'type' => 'rss',
        'limit' => 10,
    ],
];
```

- `id`: Your DropInBlog ID
- `api_token`: Your DropInBlog API token
- `layout`: The layout file that your blog views will extend
- `sections.content`: The section name in your layout where blog content will be displayed
- `feed.type`: The default feed type (rss or atom)
- `feed.limit`: The default number of items in feeds

## Usage

The package automatically registers routes for your blog at `/blog` (configurable). These include:

- `/blog` - Blog index
- `/blog/page/{page}` - Paginated blog index
- `/blog/{slug}` - Individual post
- `/blog/category/{slug}` - Category index
- `/blog/category/{slug}/page/{page}` - Paginated category index
- `/blog/author/{slug}` - Author index
- `/blog/author/{slug}/page/{page}` - Paginated author index
- `/blog/feed` - RSS/Atom feed
- `/blog/feed/category/{slug}` - Category-specific feed
- `/blog/feed/author/{slug}` - Author-specific feed
- `/blog/sitemap.xml` - XML sitemap

### Blade Directives

The package provides several Blade directives to help integrate DropInBlog content:

```blade
{{-- Include DropInBlog head content --}}
@dropInBlogHead

{{-- Conditional content only for DropInBlog pages --}}
@isDropInBlog
    This content only shows on blog pages
@endisDropInBlog

{{-- Conditional content only for non-DropInBlog pages --}}
@notDropInBlog
    This content only shows on non-blog pages
@endnotDropInBlog
```

#### Avoiding Duplicate Head Elements

The `@dropInBlogHead` directive is particularly useful for avoiding duplicate head elements such as `<title>` tags. When DropInBlog renders content, it automatically includes a `<title>` tag and other meta tags in the `headHtml` content.

To avoid duplicates, you should:

1. Place the `@dropInBlogHead` directive in your layout's `<head>` section
2. Use the `@isDropInBlog` and `@notDropInBlog` directives to conditionally include your own head elements

Example layout:

```blade
<head>
    {{-- Include DropInBlog head content (title, meta tags, etc.) --}}
    @dropInBlogHead

    {{-- Only include your own title on non-blog pages --}}
    @notDropInBlog
        <title>Your Site Title</title>
        <meta name="description" content="Your site description">
    @endnotDropInBlog

    {{-- Other head elements that should appear on all pages --}}
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js"></script>
</head>
```

This ensures that on blog pages, the title and meta tags come from DropInBlog, while on non-blog pages, your own title and meta tags are used.

### Customizing Views

If you want to customize the blog views, you can publish them:

```bash
php artisan vendor:publish --provider="DropInBlog\\Laravel\\Providers\\DropInBlogServiceProvider" --tag="views"
```

The views will be published to `resources/views/vendor/dropinblog/`.

## How It Works

This package fetches pre-rendered HTML from DropInBlog's API and displays it within your Laravel application. The rendered content includes:

- `bodyHtml`: The main content of the page
- `headHtml`: HTML for the `<head>` section (meta tags, title, etc.)

The package handles all routing and view rendering for you, making it easy to integrate a fully-featured blog into your Laravel application without managing blog content in your database.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
