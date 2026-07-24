@props([
    'title' => null,
    'subject' => null,
])

{{--
    Shared layout for the genealogical numbering reports (Ahnentafel, Henry,
    d'Aboville, de Villiers). Reports fill:
      - $toolbar : person picker + Generate action (hidden when printing)
      - default slot : the report body (a table, or indented .report-node list)
    Helper classes for the body: .report-node (+ style="--depth: N" for
    generational indentation), .report-number, .report-loading, .report-empty.
    Print CSS is inline — style-src allows 'unsafe-inline' (SecurityHeaders).
--}}

<div {{ $attributes->class(['report-layout']) }}>
    @isset($toolbar)
        <div class="report-toolbar mb-6 print:hidden">{{ $toolbar }}</div>
    @endisset

    <div class="report-sheet rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900 print:border-0 print:p-0">
        <header class="report-header mb-4 flex items-start justify-between gap-4 border-b border-gray-200 pb-3 dark:border-gray-700">
            <div>
                @if($title)
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h2>
                @endif
                @if($subject)
                    <p class="report-subject text-sm text-gray-500 dark:text-gray-400">{{ $subject }}</p>
                @endif
            </div>
            <button type="button" onclick="window.print()"
                class="report-print shrink-0 rounded-md bg-gray-100 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 print:hidden">
                Print
            </button>
        </header>

        <div wire:loading.delay class="report-loading py-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Generating report…
        </div>

        <div wire:loading.delay.remove class="report-body font-serif text-sm leading-relaxed text-gray-900 dark:text-gray-100">
            {{ $slot }}
        </div>
    </div>

    <style>
        /* Generational indentation for descendant reports: style="--depth: N" */
        .report-layout .report-node { padding-inline-start: calc(var(--depth, 0) * 1.5rem); }
        .report-layout .report-number { font-variant-numeric: tabular-nums; font-weight: 600; margin-inline-end: .5rem; }
        .report-layout .report-empty { padding: 1.5rem 0; text-align: center; color: #6b7280; }

        @media print {
            /* Strip app chrome so browser Print-to-PDF shows only the report. */
            .fi-topbar, .fi-sidebar, .fi-header, nav, .report-toolbar, .report-print { display: none !important; }
            .report-layout, .report-layout * { color: #000 !important; background: transparent !important; }
            .report-layout .report-node { break-inside: avoid; }
            .report-layout table { width: 100%; border-collapse: collapse; }
            .report-layout th, .report-layout td { padding: 2px 8px; }
        }
    </style>
</div>
