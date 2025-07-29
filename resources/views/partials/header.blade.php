<button
  @click="$dispatch('opendrawer')"
  aria-label="Toggle mobile menu"
  class="p-2 text-gray-700 transition md:hidden focus:outline-none hover:text-primary"
>
  <svg xmlns="http://www.w3.org/2000/svg"
       class="w-6 h-6" fill="none"
       viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
       stroke-linecap="round" stroke-linejoin="round">
    <path d="M4 6h16M4 12h16M4 18h16" />
  </svg>
</button>
