<?php

namespace BackBeeCloud;

use BackBeeCloud\Listener\CacheListener;
use BackBeeCloud\UserAgentHelper;
use BackBeePlanet\GlobalSettings;
use BackBeePlanet\RedisManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Eric Chau <eric.chau@lp-digital.fr>
 */
class CacheLayer
{
    public static function getCachedResponse(Request $request, $basedir, GlobalSettings $settings = null)
    {
        $response = null;

        $settings = $settings ?: new GlobalSettings();
        $settings = $settings->redis();
        if (isset($settings['disable_page_cache']) && true === $settings['disable_page_cache']) {
            return $response;
        }

        if (!$request->cookies->has(CacheListener::COOKIE_DISABLE_CACHE) && $request->isMethod('get')) {
            $redisClient = null;

            try {
                $redisClient = RedisManager::getClient();
            } catch (\Exception $exception) {
                error_log(sprintf('[%s] %s', __METHOD__, $exception->getMessage()));

                return $response;
            }

            if (is_link($basedir) && readlink($basedir)) {
                $basedir = readlink($basedir);
            }

            preg_match('~/(bp[0-9]+)\.~', $basedir, $matches);
            $key = sprintf('%s:%s[%s]', $matches[1], $request->getRequestUri(), UserAgentHelper::getDeviceType());
            if (false != $result = $redisClient->get($key)) {
                $contentType = 'text/html';
                if (1 === preg_match('~\.css$~', $request->getRequestUri())) {
                    $contentType = 'text/css';
                }

                $response = new Response($result, Response::HTTP_OK, [
                    'Content-Type' => $contentType,
                ]);
            }
        }

        return $response;
    }
}
