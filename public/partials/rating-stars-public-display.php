<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.florentinasultan.co.uk
 * @since      1.0.0
 *
 * @package    Rating_Stars
 * @subpackage Rating_Stars/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
global $post;

$post_id = $post->ID;
$rating_0_5star = get_post_meta($post_id, 'votes-0.5', true) ? get_post_meta($post_id, 'votes-0.5', true) : 0;
$rating_1star = get_post_meta($post_id, 'votes-1', true) ? get_post_meta($post_id, 'votes-1', true) : 0;
$rating_1_5star = get_post_meta($post_id, 'votes-1.5', true) ? get_post_meta($post_id, 'votes-1.5', true) : 0;
$rating_2stars = get_post_meta($post_id, 'votes-2', true) ? get_post_meta($post_id, 'votes-2', true) : 0;
$rating_2_5star = get_post_meta($post_id, 'votes-2.5', true) ? get_post_meta($post_id, 'votes-2.5', true) : 0;
$rating_3stars = get_post_meta($post_id, 'votes-3', true) ? get_post_meta($post_id, 'votes-3', true) : 0;
$rating_3_5star = get_post_meta($post_id, 'votes-3.5', true) ? get_post_meta($post_id, 'votes-3.5', true) : 0;
$rating_4stars = get_post_meta($post_id, 'votes-4', true) ? get_post_meta($post_id, 'votes-4', true) : 0;
$rating_4_5star = get_post_meta($post_id, 'votes-4.5', true) ? get_post_meta($post_id, 'votes-4.5', true) : 0;
$rating_5stars = get_post_meta($post_id, 'votes-5', true) ? get_post_meta($post_id, 'votes-5', true) : 0;
$ratings = [
    "0.5" => $rating_0_5star, 
    "1" => $rating_1star, 
    "1.5" => $rating_1_5star, 
    "2" => $rating_2stars,
    "2.5" => $rating_2_5star,
    "3" => $rating_3stars,
    "3.5" => $rating_3_5star,
    "4" => $rating_4stars, 
    "4.5" => $rating_4_5star,
    "5" => $rating_5stars
];
$avg = 0;
$no_votes = 0;
foreach ($ratings as $index => $rating){
    $avg += $index*$rating;
    $no_votes += $rating;
}

$stars_avg = $no_votes ? $avg/$no_votes : $avg;
$starClasses = ['fa fa-star-o', 'fa fa-star-half-o', 'fa fa-star'];
$rating_array = [];
foreach ($ratings as $index => $rate) {
    if ($rate !=0 ) {
        array_push($rating_array, $index);
    }
}
$bestRating = $rating_array ? max($rating_array) : 0;
$worstRating = $rating_array ? min($rating_array) : 0;
printf(
    '<script>window.starClasses = %s</script>',
    json_encode($starClasses)
);
?>
<div class="rating-container">
    <ul class="star-rating" id="rating-<?= $post->name?>">
        <?php for ($index = 1; $index <= 5; $index++) : ?>
        <?php if ($index <= $stars_avg) : ?>
            <li><i class="star-<?= $index ?> fa fa-star"></i></li>
        <?php elseif ($index - .5 == $stars_avg) : ?>
            <li><i class="star-<?= $index ?> fa fa-star-half-o"></i></li>
        <?php else : ?>
            <li><i class="star-<?= $index ?> fa fa-star-o"></i></li>
        <?php endif; ?>
        <?php endfor; ?>
    </ul>

    <input type="hidden" id="star-rating-hidden" data-allow-half="1" name="star-rating-hidden" value="<?= $stars_avg ?>">
    <?php if ($no_votes) {?>
    <div>(<?= $no_votes  ?> <?= __('Votes', 'rating-stars') ?>) </div>
    <?php } else { ?>
    <div>(<?= __('No rating yet', 'rating-stars') ?>) </div>
    <?php }; ?>
    <a class="add-rating" data-term="<?= $post->ID ?>" data-stars="<?= $stars_avg ?>" ><?= __('Send feedback') ?></a>
</div>
