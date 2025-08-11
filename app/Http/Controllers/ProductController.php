<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController
{
    /**
     * The model that this controller is handling.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * The policy that authorizes requests for this resource.
     *
     * @var string|null
     */
    protected $policy = \App\Policies\ProductPolicy::class;

    /**
     * Optional: Relationships to eager-load by default.
     *
     * @return array
     */
    protected function includes(): array
    {
        return ['author', 'tags'];
    }

    /**
     * Optional: Filters allowed for this resource.
     *
     * @return array
     */
    protected function filterableBy(): array
    {
        return ['title', 'status', 'published_at'];
    }

    /**
     * Optional: Sorts allowed for this resource.
     *
     * @return array
     */
    protected function sortableBy(): array
    {
        return ['title', 'published_at'];
    }

    /**
     * Optional: Fields allowed for search queries.
     *
     * @return array
     */
    protected function searchableBy(): array
    {
        return ['title', 'content'];
    }
}
