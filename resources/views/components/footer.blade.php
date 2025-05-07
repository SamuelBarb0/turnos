<footer class="bg-gray-900 text-gray-400 py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center mb-12">
            @foreach($footerColumns as $column)
            <div class="px-8 py-4">
                <h4 class="text-lg font-semibold text-white mb-4">{{ $column['title'] }}</h4>
                <ul class="space-y-2">
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
                        <a href="{{ $url }}" class="hover:text-[#3161DD] transition-colors">
                            {{ $text }}
                        </a>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        <div class="border-t border-gray-800 mx-auto max-w-4xl"></div>

        <div class="flex flex-col md:flex-row justify-between items-center max-w-4xl mx-auto mt-8 mb-8">
            <div class="mb-6 md:mb-0">
                <a href="">
                    <x-application-logo class="block h-10 w-auto text-[#3161DD]" />
                </a>
            </div>

            <div class="flex space-x-4">
                @foreach($socialLinks as $social)
                <a href="{{ $social['url'] }}" class="text-gray-400 hover:text-[#3161DD] transition-colors" target="_blank">
                    @php
                    $icon = strtolower($social['name']);
                    $iconClass = 'fa-circle'; // default

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

                    <i class="fab {{ $iconClass }} fa-lg"></i>
                </a>
                @endforeach
            </div>
        </div>

        <div class="text-center">
            <p>{!! $copyright !!}</p>
        </div>
    </div>
</footer>