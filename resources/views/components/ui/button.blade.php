@props(['variant' => 'default', 'size' => 'default'])

@php
$baseClass = "inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50";

$variants = [
    'default' => 'bg-primary text-primary-foreground hover:bg-primary/90',
    'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90',
    'outline' => 'border border-input bg-background hover:bg-accent hover:text-accent-foreground',
    'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
    'ghost' => 'hover:bg-accent hover:text-accent-foreground',
    'link' => 'text-primary underline-offset-4 hover:underline',
    'tambah' => 'bg-btnTambah text-btnTambah-foreground hover:bg-btnTambah/90',
    'history' => 'bg-btnHistory text-btnHistory-foreground hover:bg-btnHistory/90',
    'submit' => 'bg-btnSubmit text-btnSubmit-foreground hover:bg-btnSubmit/90',
    'search' => 'bg-btnSearch text-btnSearch-foreground hover:bg-btnSearch/90',
    'masuk' => 'bg-btnMasuk text-btnMasuk-foreground hover:bg-btnMasuk/90',
    'paginasi' => 'bg-btnPaginasi text-btnPaginasi-foreground hover:bg-btnPaginasi/90',
];

$sizes = [
    'default' => 'h-10 px-4 py-2',
    'sm' => 'h-9 rounded-md px-3 text-xs',
    'lg' => 'h-11 rounded-md px-8',
    'icon' => 'h-10 w-10',
];

$classes = $baseClass . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
