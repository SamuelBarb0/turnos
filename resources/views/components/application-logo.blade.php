@props(['width' => '16rem', 'height' => 'auto'])

<a href="/">
    <img src="{{ asset('img/svgpng.png') }}" style="width: {{ $width }}; height: {{ $height }};" {{ $attributes }} alt="Agendux Logo">
</a>