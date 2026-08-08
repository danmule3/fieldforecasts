<?php
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                'name' => 'Field Forecast',
                'url' => route('home'),
                'description' => 'Football and sports statistics, predictions, and analysis platform. Informational only — does not accept wagers.',
            ],
            [
                '@type' => 'WebSite',
                'name' => 'Field Forecast',
                'url' => route('home'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('matches.index') . '?search={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ];
?>

<script type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_SLASHES); ?></script>
<?php /**PATH C:\Sites\fieldforecasts\resources\views/components/schema/organization.blade.php ENDPATH**/ ?>