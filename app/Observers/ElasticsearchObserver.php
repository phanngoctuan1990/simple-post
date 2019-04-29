<?php

namespace App\Observers;

use Elasticsearch\Client;

class ElasticsearchObserver
{
    /**
     * var $elasticsearch
     */
    private $elasticsearch;

    /**
     * Construct ElasticsearchObserver
     * 
     * @param Client $elasticsearch Elasticsearch
     * 
     * @return void
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Saved hook
     * 
     * @param Model $model Model need to hook
     * 
     * @return void
     */
    public function saved($model)
    {
        $this->elasticsearch->index([
            'id' => $model->id,
            'body' => $model->toSearchArray(),
            'type' => $model->getSearchType(),
            'index' => $model->getSearchIndex(),
        ]);
    }

    /**
     * Deleted hook
     * 
     * @param Model $model Model need to hook
     * 
     * @return void
     */
    public function deleted($model)
    {
        $this->elasticsearch->delete([
            'id' => $model->id,
            'type' => $model->getSearchType(),
            'index' => $model->getSearchIndex(),
        ]);
    }
}
