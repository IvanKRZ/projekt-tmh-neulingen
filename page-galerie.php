<title>TMH - Galerie</title>
<?php
/**
 * Template Name: Galerie
 */
get_header(); ?>

<div class="gallery-wrapper" role="region" aria-label="Fotogalerie">

    <?php
    $albums = get_terms([
        'taxonomy'   => 'gallery_album',
        'hide_empty' => true,
    ]);
    ?>

    <?php if (!empty($albums) && !is_wp_error($albums)) : ?>
        <div class="gallery-filter" role="group" aria-label="Album-Filter">
            <button class="filter-btn is-active" data-album="all" >Alle</button>
            <?php foreach ($albums as $album) : ?>
                <button class="filter-btn" data-album="<?php echo $album->slug; ?>">
                    <?php echo $album->name; ?>
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="gallery-grid">
        <?php
        $gallery_query = new WP_Query([
            'post_type'      => 'gallery_image',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ($gallery_query->have_posts()) :
            while ($gallery_query->have_posts()) : $gallery_query->the_post();
                $terms = get_the_terms(get_the_ID(), 'gallery_album');
                $album_slugs = !empty($terms) ? implode(' ', wp_list_pluck($terms, 'slug')) : '';
            ?>
                <div class="gallery-item" data-album="<?php echo $album_slugs; ?>">
                    <a href="<?php the_post_thumbnail_url('full'); ?>"
                        data-lightbox="gallery"
                        data-title="<?php the_title(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                    </a>
                    <p><?php the_title(); ?></p>
                </div>
            <?php endwhile;
            wp_reset_postdata();
        else : ?>
            <p>NO BILDER</p>
        <?php endif; ?>
    </div>
</div>

<?php get_template_part('floating-buttons') ?>
<?php get_footer(); ?>