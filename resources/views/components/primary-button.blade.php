
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#3161DD] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2050C0] focus:bg-[#2050C0] active:bg-[#1040A0] focus:outline-none focus:ring-2 focus:ring-[#3161DD] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>