<?php
global $post;
$author = get_user_by('slug', get_query_var('author_name'));
if (!is_object($author)) {
    $author = get_user_by('id', $post->post_author);
}
$uid = $author->ID;
$userData = get_userdata($uid);
$args = [
    'member' => [
        'link' => [
            'url' => get_author_posts_url($uid),
            'target' => '_blank',
            'title' => $userData->display_name
        ],
        'image' => get_user_meta($uid, 'user_avatar', true),
        'name' => $userData->display_name,
        'position' => get_user_meta($uid, 'user_position', true),
        'text' => get_user_meta($uid, 'user_bio', true),
        'social_media_links' => function_exists('get_field') ? get_field('social_media_links', "user_{$uid}") : false
    ]
];
get_template_part('template-parts/member', 'Member', $args);
