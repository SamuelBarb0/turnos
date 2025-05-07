<footer class="bg-gradient-to-tr from-gray-950 via-gray-900 to-gray-950 text-gray-400 py-6">
    <div class="container mx-auto px-6">
        <!-- Columnas -->
        <div class="flex flex-wrap justify-center gap-8 mb-6 text-xs">
            @foreach($footerColumns as $column)
            <div class="w-36">
                <h4 class="text-sm font-semibold text-white/70 mb-3 uppercase tracking-wider">{{ $column['title'] }}</h4>
                <ul class="space-y-1.5">
                    @foreach($column['links'] as $link)
                    @php
                        $url = $link['url'];
                        if (Str::startsWith($url, '#')) {
                            $url = '/' . $url;
                        }

                        $isMail = Str::startsWith($url, 'mailto:');
                        $isTel = Str::startsWith($url, 'tel:');
                        $isEmpty = $link['url'] === '#' || empty($link['url']);

                        $text = $link['text'];

                        if ($isMail) {
                            $text = str_replace('mailto:', '', $text);
                        }

                        if ($isTel) {
                            $text = str_replace('tel:', '', $text);
                        }
                    @endphp

                    <li>
                        @if($isEmpty)
                            <span class="text-gray-500">{{ $text }}</span>
                        @else
                            <a href="{{ $url }}" class="hover:text-[#3161DD] transition-colors">{{ $text }}</a>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        <!-- Línea divisora -->
        <div class="border-t border-gray-800 mb-6"></div>

        <!-- Redes y logo -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <!-- Logo -->
            <div>
                <a href="">
                    <x-application-logo class="block h-8 w-auto text-[#3161DD]" />
                </a>
            </div>

            <!-- Redes Sociales -->
            <div class="flex space-x-5">
                @foreach($socialLinks as $social)
                <a href="{{ $social['url'] }}" class="text-gray-500 hover:text-[#3161DD] transition transform hover:scale-110 hover:shadow-[0_0_10px_#3161DD] duration-300" target="_blank">
                    @php
                        $icon = strtolower($social['name']);
                        $iconClass = 'fa-circle';

                        if(Str::contains($icon, 'facebook')) {
                            $iconClass = 'fa-facebook-f';
                        } elseif(Str::contains($icon, 'instagram')) {
                            $iconClass = 'fa-instagram';
                        } elseif(Str::contains($icon, 'twitter')) {
                            $iconClass = 'fa-twitter';
                        } elseif(Str::contains($icon, 'whatsapp')) {
                            $iconClass = 'fa-whatsapp';
                        }
                    @endphp

                    <i class="fab {{ $iconClass }} fa-xl"></i>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-6 text-xs text-gray-600">
            <p>{!! $copyright !!}</p>
        </div>
    </div>
</footer>
