<?php get_header(); ?>

<main id="main-content">
    <div class="single-post">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <article class="single-post__article" aria-labelledby="post-title">
                <div class="single-post__content">
                    <time class="single-post__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
                        <?php echo esc_html(get_the_date('d.m.Y')); ?>
                    </time>
                    <h1 class="single-post__title" id="post-title"><?php the_title(); ?></h1>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="single-post__image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                    <?php the_content(); ?>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('aktuelles'))); ?>"
                       class="single-post__back"
                       aria-label="Zurück zur Aktuelles-Übersicht">
                        ← Zurück
                    </a>
                </div>
            </article>

            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BlogPosting",
                "headline": <?php echo wp_json_encode(get_the_title()); ?>,
                "datePublished": "<?php echo esc_attr(get_the_date('c')); ?>",
                "dateModified": "<?php echo esc_attr(get_the_modified_date('c')); ?>",
                "url": "<?php echo esc_url(get_permalink()); ?>",
                "author": {
                    "@type": "Organization",
                    "name": "Teamwork Mensch & Hund Neulingen e.V."
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "Teamwork Mensch & Hund Neulingen e.V.",
                    "url": "<?php echo esc_url(home_url('/')); ?>"
                }
                <?php if (has_post_thumbnail()) : ?>
                ,"image": "<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>"
                <?php endif; ?>
            }
            </script>

        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>