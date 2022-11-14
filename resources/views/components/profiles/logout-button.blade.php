<form action="{{ route('logOut') }}" method="POST">
    @csrf
    @method('DELETE')

    <button type="submit" class="inline-flex items-center text-body hover:text-pink">
        <svg class="shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg"
             fill="currentColor"
             viewBox="0 0 20 20">
            <path
                d="m19.026 7.643-3.233-3.232a.833.833 0 0 0-1.178 1.178l3.232 3.233c.097.098.18.207.25.325-.012 0-.022-.007-.035-.007l-13.07.027a.833.833 0 1 0 0 1.666l13.066-.026c.023 0 .042-.012.064-.014a1.621 1.621 0 0 1-.278.385l-3.232 3.233a.833.833 0 1 0 1.178 1.178l3.233-3.232a3.333 3.333 0 0 0 0-4.714h.003Z"/>
            <path
                d="M5.835 18.333H4.17a2.5 2.5 0 0 1-2.5-2.5V4.167a2.5 2.5 0 0 1 2.5-2.5h1.666a.833.833 0 1 0 0-1.667H4.17A4.172 4.172 0 0 0 .002 4.167v11.666A4.172 4.172 0 0 0 4.169 20h1.666a.833.833 0 1 0 0-1.667Z"/>
        </svg>
        <span class="ml-2 font-medium">{{ $slot }}</span>
    </button>
</form>
