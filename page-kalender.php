<?php
/**
 * Template Name: Kalender
 */
get_header();

$base_hour = 10;
$end_hour  = 20;

$days = [
    'mon' => ['label' => 'Montag',     'abbr' => 'MON', 'col' => 2],
    'tue' => ['label' => 'Dienstag',   'abbr' => 'DIE', 'col' => 3],
    'wed' => ['label' => 'Mittwoch',   'abbr' => 'MIT', 'col' => 4],
    'thu' => ['label' => 'Donnerstag', 'abbr' => 'DON', 'col' => 5],
    'fri' => ['label' => 'Freitag',    'abbr' => 'FRE', 'col' => 6],
    'sat' => ['label' => 'Samstag',    'abbr' => 'SAM', 'col' => 7],
    'sun' => ['label' => 'Sonntag',    'abbr' => 'SON', 'col' => 8],
];

$events_query = new WP_Query([
    'post_type'      => 'tmh_event',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_key'       => '_event_start',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
]);

$events = [];
if ($events_query->have_posts()) {
    while ($events_query->have_posts()) {
        $events_query->the_post();
        $id  = get_the_ID();
        $day = get_post_meta($id, '_event_day', true);
        if (!$day || !isset($days[$day])) continue;
        $events[$day][] = [
            'title'   => get_the_title(),
            'start'   => get_post_meta($id, '_event_start',   true) ?: '10:00',
            'end'     => get_post_meta($id, '_event_end',     true) ?: '11:00',
            'color'   => get_post_meta($id, '_event_color',   true) ?: 'gray',
            'trainer' => get_post_meta($id, '_event_trainer', true),
        ];
    }
    wp_reset_postdata();
}

function tmh_time_to_row(string $time, int $base = 10): int {
    [$h, $m] = explode(':', $time);
    return 2 + (((int)$h - $base) * 2) + ((int)$m >= 30 ? 1 : 0);
}

function tmh_group_overlapping(array $events): array {
    if (empty($events)) return [];
    usort($events, fn($a, $b) => strcmp($a['start'], $b['start']));
    $groups    = [];
    $cur_group = [$events[0]];
    $group_end = $events[0]['end'];
    for ($i = 1; $i < count($events); $i++) {
        $e = $events[$i];
        if ($e['start'] < $group_end) {
            $cur_group[] = $e;
            $group_end   = max($group_end, $e['end']);
        } else {
            $groups[]  = $cur_group;
            $cur_group = [$e];
            $group_end = $e['end'];
        }
    }
    $groups[] = $cur_group;
    return $groups;
}
?>

<main id="main-content">
    <section class="kalender-section" aria-labelledby="kalender-heading">
        <div class="kalender-wrap">
            <h1 class="kalender-title" id="kalender-heading">Trainingszeiten</h1>

            <div class="kalender-grid" role="grid" aria-label="Wöchentlicher Trainingsplan">

                <div class="kalender-corner" aria-hidden="true"
                     style="grid-column:1; grid-row:1;"></div>

                <?php foreach ($days as $key => $day) : ?>
                    <div class="kalender-day-header"
                         role="columnheader"
                         aria-label="<?php echo esc_attr($day['label']); ?>"
                         style="grid-column:<?php echo $day['col']; ?>; grid-row:1;">
                        <?php echo esc_html($day['abbr']); ?>
                    </div>
                <?php endforeach; ?>

                <?php for ($hour = $base_hour; $hour <= $end_hour; $hour++) :
                    $row = 2 + (($hour - $base_hour) * 2); ?>
                    <div class="kalender-time-label" aria-hidden="true"
                         style="grid-column:1; grid-row:<?php echo $row; ?>;">
                        <?php echo esc_html(sprintf('%02d:00', $hour)); ?>
                    </div>
                    <div class="kalender-divider" aria-hidden="true"
                         style="grid-column:2 / -1; grid-row:<?php echo $row; ?>;"></div>
                <?php endfor; ?>

                <?php foreach ($days as $key => $day) :
                    if (empty($events[$key])) continue;
                    foreach (tmh_group_overlapping($events[$key]) as $group) :
                        $row_start = tmh_time_to_row($group[0]['start'], $base_hour);
                        $row_end   = max(array_map(fn($e) => tmh_time_to_row($e['end'], $base_hour), $group));
                        $span      = max(1, $row_end - $row_start);

                        if (count($group) === 1) :
                            $event = $group[0];
                            $color = sanitize_html_class($event['color']); ?>
                            <article class="kalender-event kalender-event--<?php echo $color; ?>"
                                     role="gridcell"
                                     style="grid-column:<?php echo $day['col']; ?>; grid-row:<?php echo $row_start; ?> / span <?php echo $span; ?>;"
                                     aria-label="<?php echo esc_attr($event['title'] . ', ' . $event['start'] . ' bis ' . $event['end'] . ($event['trainer'] ? ', ' . $event['trainer'] : '')); ?>">
                                <span class="kalender-event__name"><?php echo esc_html($event['title']); ?></span>
                                <span class="kalender-event__time"><?php echo esc_html($event['start'] . ' – ' . $event['end']); ?></span>
                                <?php if ($event['trainer']) : ?>
                                    <span class="kalender-event__trainer"><?php echo esc_html($event['trainer']); ?></span>
                                <?php endif; ?>
                            </article>
                        <?php else : ?>
                            <div class="kalender-event-group"
                                 style="grid-column:<?php echo $day['col']; ?>; grid-row:<?php echo $row_start; ?> / span <?php echo $span; ?>;"
                                 role="group">
                                <?php foreach ($group as $event) :
                                    $color = sanitize_html_class($event['color']); ?>
                                    <article class="kalender-event kalender-event--<?php echo $color; ?>"
                                             role="gridcell"
                                             aria-label="<?php echo esc_attr($event['title'] . ', ' . $event['start'] . ' bis ' . $event['end'] . ($event['trainer'] ? ', ' . $event['trainer'] : '')); ?>">
                                        <span class="kalender-event__name"><?php echo esc_html($event['title']); ?></span>
                                        <span class="kalender-event__time"><?php echo esc_html($event['start'] . ' – ' . $event['end']); ?></span>
                                        <?php if ($event['trainer']) : ?>
                                            <span class="kalender-event__trainer"><?php echo esc_html($event['trainer']); ?></span>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>

            </div>

            <div class="kalender-mobile-view" aria-label="Trainingszeiten nach Tag">
                <?php foreach ($days as $key => $day) :
                    if (empty($events[$key])) continue;
                    $groups = tmh_group_overlapping($events[$key]); ?>
                    <div class="kalender-mobile-day">
                        <h2 class="kalender-mobile-day__header">
                            <?php echo esc_html($day['label']); ?>
                        </h2>
                        <div class="kalender-mobile-day__events">
                            <?php foreach ($groups as $group) :
                                foreach ($group as $event) :
                                    $color = sanitize_html_class($event['color']); ?>
                                    <article class="kalender-event kalender-event--<?php echo $color; ?>"
                                             aria-label="<?php echo esc_attr($event['title'] . ', ' . $event['start'] . ' bis ' . $event['end'] . ($event['trainer'] ? ', ' . $event['trainer'] : '')); ?>">
                                        <span class="kalender-event__name"><?php echo esc_html($event['title']); ?></span>
                                        <span class="kalender-event__time"><?php echo esc_html($event['start'] . ' – ' . $event['end']); ?></span>
                                        <?php if ($event['trainer']) : ?>
                                            <span class="kalender-event__trainer"><?php echo esc_html($event['trainer']); ?></span>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>
</main>

<?php if (!empty($events)) :
    $items = [];
    foreach ($events as $day_key => $day_events) {
        foreach ($day_events as $e) {
            $items[] = ['@type' => 'Event', 'name' => $e['title'],
                'organizer' => ['@type' => 'SportsClub', 'name' => 'TMH Neulingen e.V.']];
        }
    }
?>
<script type="application/ld+json">
<?php echo wp_json_encode(['@context' => 'https://schema.org', '@type' => 'ItemList',
    'name' => 'TMH Neulingen Trainingszeiten', 'itemListElement' => $items],
    JSON_UNESCAPED_UNICODE); ?>
</script>
<?php endif; ?>

<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>