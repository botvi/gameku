<?php

if (!function_exists('asset_v')) {
    /**
     * Generate an asset path with automatic filemtime cache busting query parameter.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function asset_v($path, $secure = null)
    {
        if (empty($path)) {
            return $path;
        }

        if (str_contains($path, '?v=') || str_contains($path, '&v=') || str_starts_with($path, 'data:') || str_starts_with($path, 'blob:') || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return asset($path, $secure);
        }

        $parsedPath = parse_url($path, PHP_URL_PATH) ?? $path;
        $cleanPath = ltrim($parsedPath, '/');
        $fullPath = public_path($cleanPath);

        if (file_exists($fullPath) && !is_dir($fullPath)) {
            $version = filemtime($fullPath);
        } else {
            $version = config('app.version', '2.0.2');
        }

        $url = asset($cleanPath, $secure);
        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . 'v=' . $version;
    }
}
