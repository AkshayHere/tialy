<?php

namespace App\Modules\URLShortener\Controllers;

use App\Helpers\CustomResponse;
use App\Helpers\ErrorRespository;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Modules\URLShortener\Services\URLShortenerService;
use App\Modules\UserManagement\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class UrlShortenerController extends Controller
{
    /**
     * Generates a short URL
     *
     * @param Request $request
     * @return void
     */
    public function generateShortUrl(Request $request)
    {
        // validate user before continue
        $user = Auth::user();
        if (empty($user)) {
            ErrorRespository::addError('0', 'Missing User Data !');
            return response()->json((new CustomResponse())->error());
        }
        
        // validate inputs
        $url = $request->input('url', false);
        if (empty($url)) {
            ErrorRespository::addError('0', 'Missing URL/Parameters !');
            return response()->json((new CustomResponse())->error());
        }

        // Check if URL already has a short code | if have, then retrieve that guy
        if (URLShortenerService::isRedirectURLExists($url)) {
            Log::info('Short URL already exists !!!');
            $slug = URLShortenerService::getShortCodeFromRedirectURL($url);
            $shortURL = route('shorturl', ['shortUrlCode' => $slug]);

            $response = (new CustomResponse())->success([
                'data' => [
                    'short_url' => $shortURL,
                ]]);

            return json_encode($response, JSON_UNESCAPED_SLASHES);
        }

        $random = !empty($customize) ? $customize : URLShortenerService::GenerateRand();
        Log::info('$random : ' . $random);

        $isSaveShortURL = URLShortenerService::saveShortUrlCode($random, $url, $user);

        if ($isSaveShortURL) {
            $shortURL = route('shorturl', ['shortUrlCode' => $random]);

            $response = (new CustomResponse())->success([
                'data' => [
                    'short_url' => $shortURL,
                ]]);
            return json_encode($response, JSON_UNESCAPED_SLASHES);
        } else {
            ErrorRespository::addError('0', 'Failed to generate short url !');
            return response()->json((new CustomResponse())->error());
        }
    }

    /**
     * Load the short URL
     */
    public function loadShortURL(Request $request, $shortUrlCode)
    {
        if (!URLShortenerService::isShortUrlCodeValid($shortUrlCode) || ($shortUrlCode == "")) {
            abort(404);
        }

        if (URLShortenerService::isShortUrlCodeValid($shortUrlCode)) {
            $redirectURL = URLShortenerService::getRedirectURL($shortUrlCode);
            Log::info('Loading url : '.$redirectURL);

            return Redirect::to($redirectURL);
        }
    }

    /**
     * Get all URLs
     */
    public function getAllUrls(Request $request)
    {
        $list = UserService::getAllShortUrls();
        dd($list);
    }

    // To Modify the existing short URL
    public function customizeShortUrl(Request $request)
    {
        $existingShortCode = $request->input('existing_short_code', "");
        $newShortCode = $request->input('new_short_code', "");
        $prefix = $request->input('prefix', "");
        $suffix = $request->input('suffix', "");

        // check the customized url code has minimum length or not | possibly 5 characters
        if (strlen($newShortCode) < 5 || strlen($newShortCode) > 20) {
            ErrorRespository::addError('0', 'Custom URL Code should be 5-20 Characters !');
            return response()->json((new CustomResponse())->error());
        }

        // check if url code is valid or not | eg: ABCD1234
        $shortCodeDetails = URLShortenerService::getShortUrlCodeDetails($existingShortCode);
        if (empty($shortCodeDetails)) {
            ErrorRespository::addError('0', 'Invalid Short Code !');
            return response()->json((new CustomResponse())->error());
        }

        // if valid, retrieve the redirect url
        $redirectURL = URLShortenerService::getRedirectURL($existingShortCode);

        // since we are customizing the short url, always add a middle letter or letters accordingly after the prefix text | eg: agent/*/ABCD1234
        // https://stackoverflow.com/questions/2673360/most-efficient-way-to-get-next-letter-in-the-alphabet-using-php
        // check if this short url already exists | if exists, then replace middle letter with the next in sequence
        $str = 'a';
        $updatedShortCode = $str . '/' . $newShortCode;

        $updatedShortCode = self::customizeShortURLCode($updatedShortCode, $prefix, $suffix);

        while (URLShortenerService::isShortUrlCodeValid($updatedShortCode)) {
            $updatedShortCode = ++$str . '/' . $newShortCode;
            $updatedShortCode = self::customizeShortURLCode($updatedShortCode, $prefix, $suffix);
        }
        Log::info('$newShortCode : ' . $newShortCode);
        Log::info('$updatedShortCode : ' . $updatedShortCode);

        // Save and return output to show on ui | when we save, save as agent/*/ABCD1234
        $isSaveShortURL = URLShortenerService::saveShortUrlCode($updatedShortCode, $redirectURL);
        Log::info('$isSaveShortURL : ' . $isSaveShortURL);

        if ($isSaveShortURL) {
            $shortURL = route('shorturl', ['shortUrlCode' => $updatedShortCode]);
            Log::info('$shortURL : ' . $shortURL);

            $response = (new CustomResponse())->success([
                'data' => [
                    'short_url' => $shortURL,
                ]]);
            return json_encode($response, JSON_UNESCAPED_SLASHES);
        } else {
            ErrorRespository::addError('0', 'Failed to generate short url !');
            return response()->json((new CustomResponse())->error());
        }
    }

    private static function customizeShortURLCode(string $originalCode, ?string $prefix = null, ?string $suffix = null)
    {
        // Prefix
        if (!empty($prefix)) {
            $originalCode = $prefix . '/' . $originalCode;
        }
        // Suffix
        if (!empty($suffix)) {
            $originalCode = $originalCode . '/' . $suffix;
        }

        return $originalCode;
    }
}
