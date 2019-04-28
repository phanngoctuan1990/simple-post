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
     * Get image
     * 
     * @return string
     */
    public function getImageAttribute($image)
    {
        if (strpos($image, 'dummyimage') !== false || $image == null) {
            return self::IMAGE_DEFAULT;
        }

        if (strpos($image, 's3.amazonaws.com') !== false) {
            return $image;
        }

        return Storage::disk('s3')->url('post-image/' . $image);
    }
}
