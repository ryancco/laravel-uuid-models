<?php

namespace Ryancco\HasUuidRouteKey\Tests\Mocks;

use Illuminate\Database\Eloquent\Model;
use Ryancco\HasUuidRouteKey\HasUuidRouteKey;

class Post extends Model
{
    use HasUuidRouteKey;

    protected $guarded = [];
}
