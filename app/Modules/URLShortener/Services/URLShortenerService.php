<?php

namespace App\Modules\URLShortener\Services;

use App\Models\User;
use App\Modules\URLShortener\Models\ShortURL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class URLShortenerService
{
    // Generate random string
    static function GenerateRand(): string
    {
        do {
            $random = Str::random(8);
        } while (self::isShortUrlCodeValid($random));
        return $random;
    }

    // Save Short URL Code
    static function saveShortUrlCode($short_url_code, $redirect_url, User $user = null): bool
    {
        $shortUrlInfo = new ShortURL();
        $shortUrlInfo->slug = $short_url_code;
        $shortUrlInfo->redirect_url = $redirect_url;
        $shortUrlInfo->creator_id = !empty($user) ? $user->email : null;
        $shortUrlInfo->save();

        return true;
    }

    /**
     * Check if short URL code already exists
     *
     * @param [type] $short_url_code
     * @return boolean
     */
    static function isShortUrlCodeValid($short_url_code): bool
    {
        return ShortURL::where('slug',$short_url_code)->exists();
    }

    // Get redirect url
    static function getRedirectURL($short_url_code): ?string
    {
        return ShortURL::where('slug',$short_url_code)->value('redirect_url');
    }

    // Get Short Code Details
    static function getShortUrlCodeDetails($short_url_code): ?ShortURL
    {
        return ShortURL::where('slug',$short_url_code)->first();
    }

    // Check if Redirect URL already exists
    static function isRedirectURLExists($redirect_url): bool
    {
        return ShortURL::where('redirect_url',$redirect_url)->exists();
    }

    // Get Short Code by Redirect URL
    static function getShortCodeFromRedirectURL($redirect_url): ?string
    {
        if(!self::isRedirectURLExists($redirect_url)){
            return null;
        }
        else{
            return ShortURL::where('redirect_url',$redirect_url)->first()->slug;
        }        
    }

    static function getAllShortUrls()
    {
        return ShortURL::select(['slug', 'redirect_url', 'creator_id'])->get()->toArray();
    }

    static function getShortUrlDetailsByShortCode(string $shortCode)
    {
        return ShortURL::where('slug',$shortCode)->select(['slug', 'redirect_url', 'creator_id'])->first();
    }

    // Update redirect URL by short code
    static function updateRedirectURLByShortCode(string $shortCode, string $newRedirectURL):?bool
    {
        return ShortURL::where('slug',$shortCode)->update(['redirect_url'=> $newRedirectURL]);
    }

    // Delete
    static function deleteShortURLDetails(string $shortCode):?bool
    {
        return ShortURL::where('slug',$shortCode)->delete();
    }
}