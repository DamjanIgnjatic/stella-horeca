<?php
/**
 * use BoldizArt\WpTheme\IframeUrl;
 */
namespace BoldizArt\WpTheme;

class IframeUrl
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add filter
        if (function_exists('add_filter')) {

            // iframe_url filter
            \add_filter('iframe_url', [$this, 'generateIframeUrl']);
        }
    }

    /**
     * Generate iframe URL
     * @param string $url
     */
    public function generateIframeUrl($url)
    {
        // YouTube URL
        $url = $this->youTubeIframeUrl($url);
        
        // If it's not a valid YouTube URL, return false or handle the error
        return $url;
    }

    /**
     * Check for YouTube URL
     * @param string $url
     */
    public function youTubeIframeUrl ($url) {
        // Extract the video ID from a standard YouTube URL
        if (preg_match('/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches) ||
            preg_match('/(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches) ||
            preg_match('/(?:https?:\/\/)?(?:www\.)?youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/', $url, $matches)
        ) {
            if (is_array($matches) && count($matches)) {
                $videoId = $matches[1];
                
                // Return the iframe URL
                return 'https://www.youtube.com/embed/' . $videoId;
            }
        }
        
        // If it's not a valid YouTube URL, return false or handle the error
        return $url;
    }
}
