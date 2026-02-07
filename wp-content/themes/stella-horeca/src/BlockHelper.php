<?php
/**
 * use BoldizArt\WpTheme\BlockHelper;
 */
namespace BoldizArt\WpTheme;

class BlockHelper
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_filter')) {

            // Init function
            \add_filter('block_content', [$this, 'blockContent']);
        }
    }

    /**
     * Add HTML elements above the non-custom blocks
     * @param string $content
     * @return string $content
     */
    public function blockContent($content)
    {
        if (\has_blocks($content)) {
            $blocks = \parse_blocks($content);
            $parsedBlocks = $this->parseBlocks($blocks);
            $serialisedContent = \serialize_blocks($parsedBlocks);
            $content = \apply_filters('the_content', $serialisedContent);
        }

        return $content;
    }

    /**
     * Parse blocks
     * @param array $blocks
     */
    public function parseBlocks(array $blocks): array
    {
        $allBlocks = [];
        foreach ($blocks as $block) {

            // Go into inner blocks and run this method recursively
            if (!empty($block['innerBlocks'])) {
                $block['innerBlocks'] = $this->parseBlocks($block['innerBlocks']);
            }

            // Make sure that is a valid block (some block names may be NULL)
            if (!empty($block['blockName'] && !str_starts_with($block['blockName'], 'acf'))) {
                $block['innerHTML'] =  '
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10 offset-lg-1 col-xxl-8 offset-xxl-2 content">
                                ' .$block['innerHTML'] . '
                            </div>
                        </div>
                    </div>
                ';

                foreach ($block['innerContent'] as $key => $value) {
                    if ($value) {
                        $block['innerContent'][$key] =  '
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-10 offset-lg-1 col-xxl-8 offset-xxl-2 content">
                                        ' .$value . '
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
            }

            // Continuously create back the blocks array.
            $allBlocks[] = $block;
        }

        return $allBlocks;
    }
}
