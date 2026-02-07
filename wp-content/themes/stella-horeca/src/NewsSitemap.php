<?php
/**
 * use BoldizArt\WpTheme\NewsSitemap;
 */
namespace BoldizArt\WpTheme;

class NewsSitemap
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Init function
            \add_action('init', [$this, 'createXMLNewsSitemap']);

            // Add meta box
            \add_action('add_meta_boxes', [$this, 'addMetaBox']);

            // Save meta box
            \add_action('save_post', [$this, 'saveMetaBox']);
        }
    }

    /**
     * Create news XML sitemap
     */
    function createXMLNewsSitemap() {
        if (array_key_exists('REQUEST_URI', $_SERVER)) {
            if (str_contains($_SERVER['REQUEST_URI'], 'news-sitemap.xml')) {
                $news = $this->getNews();
                $urls = '';
                foreach ($news as $data) {
                    $urls .= newsToXMLUrl($data);
                }
                header('Content-Type: text/xml');
                ?>
                    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
                        <?php echo $urls; ?>
                    </urlset>
                <?php
                exit;
            }
        }
    }

    /**
     * Create an URL XML element from the given data
     */
    function newsToXMLUrl($data)
    {
        return "
            <url>
                <loc>{$data->loc}</loc>
                <news:news>
                    <news:publication>
                        <news:name>{$data->name}</news:name>
                        <news:language>{$data->language}</news:language>
                    </news:publication>
                    <news:genres>{$data->genres}</news:genres>
                    <news:publication_date>{$data->publication_date}</news:publication_date>
                    <news:title>{$data->title}</news:title>
                    <news:keywords>{$data->keywords}</news:keywords>
                    <news:stock_tickers>NASDAQ:GOOGL</news:stock_tickers>
                </news:news>
            </url>
        ";
    }

    /**
     * Get all news posts, format the data and return back
     */
    public function getNews() : array
    {
        $response = [];
        $args = [
            'post_type'  => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'meta_key' => 'brt_google_news_keywords',
                    'meta_compare' => '!=',
                    'meta_value' => ''
                ]
            ]
        ];
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                // Get the current posts lNewsSitemapanguage
                $locale = get_locale();
                $locales = explode('_', $locale);
                $lang = reset($locales);

                // Get keywords
                $keywords = get_post_meta(get_the_ID(), 'brt_google_news_keywords', true);

                if ($keywords) {
                    // Set the response
                    $response[] = (object) [
                        'loc' => get_permalink(),
                        'name' => get_bloginfo('name'),
                        'language' => $lang,
                        'genres' => 'News, Blog',
                        'publication_date' => get_the_date('c'),
                        'title' => get_the_title(),
                        'keywords' => $keywords
                    ];
                }
            }
        }
        \wp_reset_postdata();

        return $response;
    }

    /**
     * Add custom meta box
     */
    public function addMetaBox()
    {
        add_meta_box(
            'brt_google_news', // Unique ID
            'Google news', // Box title
            [$this, 'addHTMLBox'], // Content callback, must be of type callable
            ['post'], // Post types
            'side',
            'core'
        );
    }

    /**
     * Add custom HTML box
     * @param WP_Post $post
     */
    function addHTMLBox($post)
    {
        $keywords = get_post_meta($post->ID, 'brt_google_news_keywords', true);
        ?>
            <div class="theme-meta">
                <div>
                    <label class="theme-meta-box-input-label" for="brtGoogleNewsKeywords">
                        <span class="description" style="
                            font-size: 11px;
                            font-weight: 500;
                            line-height: 1.4;
                            text-transform: uppercase;
                            display: inline-block;
                            margin-bottom: calc(8px);
                            padding: 0px;
                        ">Google news keywords</span>
                    </label>
                    <input type="text" value="<?php echo $keywords; ?>" name="brt_google_news_keywords" id="brtGoogleNewsKeywords">
                    <small>Add comma separated keywords that you wish to use for Google news.</small>
                </div>
            </div>
        <?php
    }

    /**
     * Save the custom meta value(s)
     * @param int $pid
     */
    function saveMetaBox(int $pid)
    {
        if (array_key_exists('brt_google_news_keywords', $_POST)) {
            update_post_meta(
                $pid,
                'brt_google_news_keywords',
                $_POST['brt_google_news_keywords']
            );
        }
    }
}
