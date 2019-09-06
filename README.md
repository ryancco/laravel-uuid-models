# HasUuidRouteKey
HasUuidRouteKey is a simple [trait](http://php.net/manual/en/language.oop5.traits.php) to be used on [Laravel](https://laravel.com) Eloquent Models to provide a drop-in solution for UUID route keys.

 # Installation
The only supported automated installation is via [Composer](https://getcomposer.org)
 ```
composer require ryancco/hasuuidroutekey
```

# Usage
Once HasUuidRouteKey has been installed, you can add the trait to any Eloquent Models you wish to have UUID route keys.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ryancco\HasUuidRouteKey\HasUuidRouteKey;

class Post extends Model
{
    use HasUuidRouteKey;
}
```

Now you can use the configured UUID attribute as a route key just as you would with an auto-incrementing ID route key.

## Routes
```php
<?php
// Route registration works exactly as it had with models routed by the "id" attribute

// "binded" routes
Route::get('posts/{post}', 'PostController@show');

// resourceful routes
Route::resource('posts', 'PostController');

// ...
```

## Tests
```php
<?php

namespace Tests\Feature;

use App\Models\Post;
use Tests\TestCase;

class PostsControllerTest extends TestCase
{
    public function testAdminsHavePermissionToViewPrivatePosts(): void
    {
        $post = factory(Post::class)->state('private')->create();

        $this->get(route('posts.view', $post))->assertOk();
        // route('posts.view', $post->uuid) - localhost/posts/94205252-7c44-4e5b-ad75-682ac81fea84
    }
}
```

# Configuration
By default, the route key is named 'uuid', but this can be configured to whatever you would like.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ryancco\HasUuidRouteKey\HasUuidRouteKey;

class Post extends Model
{
    use HasUuidRouteKey;

    public function getRouteKeyName() : string
    {
        return 'something-else';
    }
}
```

# Caveats
One thing to take note of, is that the UUID generation is triggered when the model event "creating" is triggered. The most important thing to keep in mind, and why this is the case, is that the UUID will not be generated when instantiating a model via the new keyword* but rather once it has been persisted to (or retrieved from) the database. 

If the situation arises where you need the UUID generated before the "creating" model event is fired, you can manually call the following method:
```php
<?php

$post = new App\Models\Post();
$post->generateUuidRouteKey();
```
 
 # Contributing
 Please report any problems by creating an [issue](https://github.com/ryancco/hasuuidroutekey/issues) and [pull request](https://github.com/ryancco/hasuuidroutekey/pulls) (encouraged, but not required).
