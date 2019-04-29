<?php
namespace App\Traits\Search;

use App\Observers\ElasticsearchObserver;

trait Searchable
{
    /**
     * Boot search able
     */
    public static function bootSearchable()
    {
        // This makes it easy to toggle the search feature flag
        // on and off. This is going to prove useful later on
        // when deploy the new search engine to a live app.
        if (config('services.search.enabled')) {
            static::observe(ElasticsearchObserver::class);
        }
    }

    /**
     * Get search index
     *
     * @return string
     */
    public function getSearchIndex()
    {
        return $this->getTable();
    }

    /**
     * Get search type
     *
     * @return string
     */
    public function getSearchType()
    {
        if (property_exists($this, 'useSearchType')) {
            return $this->useSearchType;
        }
        return $this->getTable();
    }

    /**
     * Convert model to array
     *
     * @return array
     */
    public function toSearchArray()
    {
        // By having a custom method that transforms the model
        // to a searchable array allows us to customize the
        // data that's going to be searchable per model.
        return $this->toArray();
    }
}
