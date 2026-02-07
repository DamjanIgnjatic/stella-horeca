<?php
/**
 * use BoldizArt\WpTheme\ReadingTime;
 */
namespace BoldizArt\WpTheme;

class ReadingTime
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {
            
            // Init function
            \add_action('reading_time', [$this, 'readingTime'], 10, 1);
        }
    }

    /**
     * Count the reading time
     */
    function calculateReadingTime($text = '', $wordsPerMinute = 200)
    {
        $wordsCount = str_word_count($text);
        return $wordsCount ? ceil($wordsCount / $wordsPerMinute) : false;
    }

    /**
     * Reading time HTML
     */
    function readingTime($text)
    {
        $text = wp_strip_all_tags($text);
        $readTime = $this->calculateReadingTime($text);
        $timeText = ($readTime > 1) ? __('minutes reading', 'startertheme') : __('minute reading', 'startertheme');
        ?>
        <span class="reading-time">
            <span class="time"><strong><?php echo $readTime; ?></strong> <?php echo $timeText ?></span>
        </span>
        <?php
    }
}
