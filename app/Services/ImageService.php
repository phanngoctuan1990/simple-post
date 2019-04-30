<?php
namespace App\Services;

use Storage;

class ImageService
{
    /**
     * Upload image
     *
     * @param Request $request Request
     * @param Post    $post    Post
     *
     * @return string|null
     */
    public function upload($request, $post = null)
    {
        $name = null;
        if ($request->hasFile('image')) {
            if ($post && $post->image) {
                Storage::disk('s3')->delete('post-image/' . $post->image);
            }
            $name = time() . '.' . $request->image->getClientOriginalExtension();
            $image = $request->image;
            Storage::disk('s3')->put('post-image/' . $name, file_get_contents($image), 'public');
        } elseif ($post) {
            return $post->image;
        }
        return $name;
    }
}
