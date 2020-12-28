<?php

namespace App\Modules\URLShortener\Controllers;

use App\Helpers\CustomResponse;
use App\Helpers\ErrorRespository;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Modules\URLShortener\Services\URLShortenerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class UrlShortenerController extends Controller
{
    /**
     * Generates a short URL
     * METHOD : POST
     * URL    : admin/urls
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
        $valid = validator($request->only('url', 'customSlug'), [
            'url' => 'required|url',
            'customSlug' => 'string|min:8|max:20|nullable',
        ]);

        if ($valid->fails()) {
            $jsonError = response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        // validate inputs
        $url = $request->input('url', false);
        $customSlug = $request->input('customSlug', '');

        // trim trailing slash
        if (substr($url, -1) == '/') {
            $url = substr($url, 0, -1);
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

        // generate slug
        $random = URLShortenerService::GenerateRand();

        // customize slug
        if (!empty($customSlug)) {
            $str = 'a';
            $temp = $customSlug;
            while (URLShortenerService::isShortUrlCodeValid($customSlug)) {
                $customSlug = $str++ . '/' . $temp;
            }
            $random = strtolower($customSlug);
        }
        Log::info('$random : ' . $random);
        // dd($random);

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
     * METHOD : GET
     * URL    : /{slug}
     * Unprotected route
     */
    public function loadShortURL(Request $request, $shortUrlCode)
    {
        if (!URLShortenerService::isShortUrlCodeValid($shortUrlCode) || ($shortUrlCode == "")) {
            abort(404);
        }

        if (URLShortenerService::isShortUrlCodeValid($shortUrlCode)) {
            $redirectURL = URLShortenerService::getRedirectURL($shortUrlCode);
            Log::info('Loading url : ' . $redirectURL);

            return Redirect::to($redirectURL);
        }
    }

    /**
     * Get all URLs
     * METHOD : GET
     * URL    : admin/urls
     */
    public function getAllUrls(Request $request)
    {
        // validate user before continue
        $user = Auth::user();
        if (empty($user)) {
            ErrorRespository::addError('0', 'Missing User Data !');
            return response()->json((new CustomResponse())->error());
        }

        $shortUrlDetails = URLShortenerService::getAllShortUrls();
        if (empty($shortUrlDetails)) {
            ErrorRespository::addError('0', 'No short urls were found !');
            return response()->json((new CustomResponse())->error());
        }

        $response = (new CustomResponse())->success(['data' => $shortUrlDetails]);
        return json_encode($response, JSON_UNESCAPED_SLASHES);
        // return response()->json((new CustomResponse())->success(['data' => $shortUrlDetails]));
    }

    /**
     * Get URL details by id
     * METHOD : GET
     * URL    : admin/urls/{slug}
     * PARAMS : slug (short code)
     */
    public function getUrlBySlug(Request $request, $slug)
    {
        if (!URLShortenerService::isShortUrlCodeValid($slug) || (empty($slug))) {
            ErrorRespository::addError('0', 'Invalid Id !!!');
            return response()->json((new CustomResponse())->error());
        }

        $shortURLDetails = URLShortenerService::getShortUrlDetailsByShortCode($slug);

        $response = (new CustomResponse())->success(['data' => $shortURLDetails]);
        return json_encode($response, JSON_UNESCAPED_SLASHES);
        // return response()->json((new CustomResponse())->success(['data' => $shortURLDetails]));
    }

    /**
     * Set redirect URL by slug
     * METHOD : PUT
     * URL    : admin/urls/{slug}
     * PARAMS : url
     */
    public function setUrlBySlug(Request $request, string $slug)
    {
        // validate user before continue
        $user = Auth::user();
        if (empty($user)) {
            ErrorRespository::addError('0', 'Missing User Data !');
            return response()->json((new CustomResponse())->error());
        }

        // validate inputs
        $valid = validator($request->only('url'), [
            'url' => 'required|url',
        ]);

        if ($valid->fails()) {
            $jsonError = response()->json($valid->errors()->all(), 400);
            return response()->json($jsonError);
        }

        $newRedirectURL = $request->get('url', '');
        if (empty($newRedirectURL)) {
            ErrorRespository::addError('0', 'Missing new redirect link !');
            return response()->json((new CustomResponse())->error());
        }

        if (!URLShortenerService::isShortUrlCodeValid($slug) || ($slug == "")) {
            ErrorRespository::addError('0', 'Invalid Id !!!');
            return response()->json((new CustomResponse())->error());
        }

        $shortURLDetails = URLShortenerService::getShortUrlDetailsByShortCode($slug);
        $existingRedirectURL = $shortURLDetails->redirect_url;

        // change only if both are different, else return the existing one
        if ($existingRedirectURL != $newRedirectURL) {
            Log::info('setUrlBySlug @ UrlShortenerController | redirect url ' . $slug . ' was updated by ' . $user->email);
            $isUpdateSuccessful = URLShortenerService::updateRedirectURLByShortCode($slug, $newRedirectURL);
            if (!$isUpdateSuccessful) {
                return response()->json((new CustomResponse())->error(["Failed to update short url details !!"]));
            }
            $shortURLDetails = URLShortenerService::getShortUrlDetailsByShortCode($slug);
        }

        $response = (new CustomResponse())->success(['data' => $shortURLDetails]);
        return json_encode($response, JSON_UNESCAPED_SLASHES);
        // return response()->json((new CustomResponse())->success(['data' => $shortURLDetails]));
    }

    /**
     * delete short URL details by slug
     * METHOD : DELETE
     * URL    : admin/urls/{slug}
     */
    public function deleteUrlBySlug(Request $request, string $slug)
    {
        // validate user before continue
        $user = Auth::user();
        if (empty($user)) {
            ErrorRespository::addError('0', 'Missing User Data !');
            return response()->json((new CustomResponse())->error());
        }

        // validate slug passed in
        if (!URLShortenerService::isShortUrlCodeValid($slug) || ($slug == "")) {
            ErrorRespository::addError('0', 'Invalid Id !!!');
            return response()->json((new CustomResponse())->error());
        }

        // if exists, delete the url details
        // put a log in for future references
        if (URLShortenerService::isShortUrlCodeValid($slug)) {
            Log::info('deleteUrlBySlug @ UrlShortenerController | slug ' . $slug . ' was deleted by ' . $user->email);
            $isDeleteSuccessful = URLShortenerService::deleteShortURLDetails($slug);
            if (!$isDeleteSuccessful) {
                return response()->json((new CustomResponse())->error(["Failed to delete short url details !!"]));
            }
            return response()->json((new CustomResponse())->success());
        } else {
            ErrorRespository::addError('0', 'Unable to find URL details !!');
            return response()->json((new CustomResponse())->error());
        }
    }
}
