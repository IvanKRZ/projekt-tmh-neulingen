<?php
/**
 * Template Name: Aktuelles
 */
get_header();
$blog_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
?>
<main id="main-content">
    <h1 style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">
        <?php echo esc_html(get_queried_object()->post_title ?? 'Aktuelles'); ?>
    </h1>
    <div class="blog-grid" role="list" aria-label="Blogbeiträge">
        <?php if ($blog_query->have_posts()) :
            while ($blog_query->have_posts()) : $blog_query->the_post();
                $current_post = $blog_query->post; ?>
            <article class="blog-item" role="listitem" aria-labelledby="post-title-<?php echo $current_post->ID; ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="blog-item__image">
                        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                            <?php the_post_thumbnail('large'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="blog-item__content">
                    <a href="<?php the_permalink(); ?>" class="blog-item__link"
                       aria-label="<?php echo esc_attr(sprintf('Beitrag lesen: %s', get_the_title())); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false">
                            <line x1="7" y1="17" x2="17" y2="7"/>
                            <polyline points="7 7 17 7 17 17"/>
                        </svg>
                    </a>
                    <time class="blog-item__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
                        <?php echo esc_html(get_the_date('d.m.Y')); ?>
                    </time>
                    <h2 class="blog-item__title" id="post-title-<?php echo $current_post->ID; ?>">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <?php
                    $exc = wp_trim_words(
                        wp_strip_all_tags(apply_filters('the_content', $current_post->post_content)),
                        200,
                        '…'
                    );
                    if (empty($exc)) {
                        $exc = wp_trim_words(strip_tags($current_post->post_content), 200, '…');
                    }
                    if (!empty($exc)) : ?>
                        <p class="blog-item__excerpt"><?php echo esc_html($exc); ?></p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endwhile;
        wp_reset_postdata();
        else : ?>
            <p>Keine Beiträge vorhanden.</p>
        <?php endif; ?>
    </div>
</main>
<?php if ($blog_query->post_count > 0) :
    $schema_posts = [];
    foreach ($blog_query->posts as $i => $p) {
        $schema_posts[] = ['@type'=>'BlogPosting','position'=>$i+1,'headline'=>get_the_title($p->ID),'url'=>get_permalink($p->ID),'datePublished'=>get_the_date('c',$p->ID)];
    }
?>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Blog","name":"TMH Neulingen – Aktuelles","url":"<?php echo esc_url(get_permalink(get_page_by_path('aktuelles'))); ?>","blogPost":<?php echo wp_json_encode($schema_posts,JSON_UNESCAPED_UNICODE); ?>}
</script>
<?php endif; ?>
<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>