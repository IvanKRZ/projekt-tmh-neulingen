<title>TMH - Aktuelles</title>
<?php
    /**
    * Template Name: Aktuelles
    */
    get_header(); 
    wp_head();
?>

<body <?php body_class(); ?>>
    <?php get_header(); ?>
    <div class="blog-grid">
        <?php
        $blog_query = new WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ($blog_query->have_posts()) :
            while ($blog_query->have_posts()) : $blog_query->the_post(); ?>

                <div class="blog-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="blog-item__image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="blog-item__content">
                        <p class="blog-item__date"><?php echo get_the_date('d.m.Y'); ?></p>
                        <h2 class="blog-item__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="blog-item__excerpt"><?php the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="blog-item__link">Weiterlesen →</a>
                    </div>
                </div>

            <?php endwhile;
            wp_reset_postdata();
        else : ?>
            <p>Keine Beiträge vorhanden.</p>
        <?php endif; ?>
    </div>

    <?php get_template_part('floating-buttons') ?>
    <?php get_footer(); ?>

    <?php get_template_part('floating-buttons'); ?>
    <?php get_footer(); ?>
</body>