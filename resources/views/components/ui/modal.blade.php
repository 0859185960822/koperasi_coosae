@props(['id', 'title' => ''])

<dialog id="{{ $id }}" class="modal p-0 m-auto bg-transparent border-0 rounded-lg shadow-lg"
    onclick="if(event.target === this) this.close()">
    
    <div class="w-full max-w-lg sm:min-w-[500px] bg-background p-6 rounded-lg text-foreground text-left relative mx-auto my-auto">
        @if($title)
        <div class="flex flex-col space-y-1.5 text-left mb-4 border-b pb-4">
            <h2 class="text-lg font-semibold leading-none tracking-tight">{{ $title }}</h2>
        </div>
        @endif

        <div class="py-2 text-left">
            {{ $slot }}
        </div>

        <button type="button" onclick="document.getElementById('{{ $id }}').close()" class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
            <span class="sr-only">Close</span>
        </button>
    </div>
</dialog>

<style>
    /* Reset HTML native dialog defaults */
    dialog {
        padding: 0;
        margin: auto;
        color: inherit;
        background: transparent;
        border: none;
        max-width: 100vw;
        max-height: 100vh;
    }
    dialog::backdrop {
        background-color: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(2px);
    }
</style>
