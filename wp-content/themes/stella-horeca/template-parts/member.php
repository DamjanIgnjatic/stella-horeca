<?php if (array_key_exists('member', $args) && $member = $args['member']): ?>
    <div class="team2-member row">
        <div class="col-sm-5 d-flex align-items-center">
            <?php $link = (array_key_exists('link', $member) && !empty($member['link']) && is_array($member['link'])) ? $member['link'] : false; ?>
            <?php if ($link): ?><a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $link['title']; ?>"><?php endif; ?>
                <?php if ($imgId = $member['image']) {
                    echo wp_get_attachment_image($imgId, 'profile', false, ['class' => 'member-image']);
                } ?>
            <?php if ($link): ?></a><?php endif;?>
        </div>
        <div class="col-sm-7 d-flex align-items-center">
            <div>
                <h3 class="mt-4 mt-md-0">
                    <?php if ($link): ?><a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $link['title']; ?>"><?php endif; ?>
                        <?php echo $member['name']; ?>
                    <?php if ($link): ?></a><?php endif;?>
                </h3>
                <?php $position = array_key_exists('position', $member) && $member['position'] ? $member['position'] : false; ?>
                <?php if ($position): ?>
                    <div class="small position">
                        <?php echo $position; ?>
                    </div>
                <?php endif; ?>
                <div class="text text-sm-left"><?php echo $member['text']; ?></div>
                <?php if (is_array($member['social_media_links']) && count($member['social_media_links'])): ?>
                    <div class="social-media-links justify-content-sm-start">
                        <?php foreach ($member['social_media_links'] as $link): ?>
                            <?php if (isset($link['link'], $link['link']['url'])): ?>
                            <a href="<?php echo $link['link']['url']; ?>" class="link d-inline-flex justify-content-center align-items-center" target="<?php echo $link['link']['target']; ?>" title="<?php echo $link['link']['title']; ?>">
                                <?php if (isset($link['icon'])): ?>
                                    <i class="icon <?php echo $link['icon']; ?>"></i>
                                <?php endif; ?>
                            </a>
                            <?php endif; ?>
                        <?php endforeach ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
