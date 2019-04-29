<?php

namespace App;

use Storage;
use App\Traits\Search\Searchable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Searchable;

    const IMAGE_DEFAULT = 'https://dummyimage.com/600x400/000/fff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'description', 'image'];

    /**
     * Get post image
     *
     * @return string
     */
    public function getPostImageAttribute($image)
    {
        if ($this->image) {
            return Storage::disk('s3')->url('post-image/' . $this->image);
        }
        return self::IMAGE_DEFAULT;
    }
}
