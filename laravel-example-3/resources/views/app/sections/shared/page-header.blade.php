<div class="page-header">
    @include('shared.form-alerts')

    @include('app.sections.shared.page-titles', [
             'pageTitle' => isset($pageTitle) ? $pageTitle : '',
             'pageSubtitle' => isset($pageSubtitle) ? $pageSubtitle : '',
    ])

    @include('app.sections.shared.page-navigation', [
        'menuItems' => isset($menuItems) ? $menuItems : null,
    ])
</div>
