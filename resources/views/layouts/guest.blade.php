<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Agendux') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @font-face {
                font-family: 'Adineue Pro';
                src: url('/fonts/adineue-PRO.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }
            
            .font-adineue {
                font-family: 'Adineue Pro', sans-serif;
            }
            
            .topo-bg {
                position: relative;
                background-color: #f8fafc; /* Fondo blanco con un sutil tono azulado */
                overflow: hidden;
            }
            
            .topo-bg::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url("data:image/svg+xml,%3Csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='smallGrid' width='60' height='60' patternUnits='userSpaceOnUse'%3E%3Cpath d='M 60 0 L 0 0 0 60' fill='none' stroke='%233161DD' stroke-width='0.2' stroke-opacity='0.1'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23smallGrid)'/%3E%3Cpath d='M103,10 C133,30 183,150 233,150 C283,150 300,110 350,150 C400,190 420,250 500,290 C550,315 580,310 630,270 C680,230 730,180 780,150 C830,120 880,120 930,150 M53,50 C83,70 183,200 233,200 C283,200 300,160 350,200 C400,240 420,300 500,340 C550,365 580,360 630,320 C680,280 730,230 780,200 C830,170 880,170 930,200 M53,100 C83,120 183,250 233,250 C283,250 300,210 350,250 C400,290 420,350 500,390 C550,415 580,410 630,370 C680,330 730,280 780,250 C830,220 880,220 930,250 M53,150 C83,170 183,300 233,300 C283,300 300,260 350,300 C400,340 420,400 500,440 C550,465 580,460 630,420 C680,380 730,330 780,300 C830,270 880,270 930,300 M53,200 C83,220 183,350 233,350 C283,350 300,310 350,350 C400,390 420,450 500,490 C550,515 580,510 630,470 C680,430 730,380 780,350 C830,320 880,320 930,350 M53,250 C83,270 183,400 233,400 C283,400 300,360 350,400 C400,440 420,500 500,540 C550,565 580,560 630,520 C680,480 730,430 780,400 C830,370 880,370 930,400 M53,300 C83,320 183,450 233,450 C283,450 300,410 350,450 C400,490 420,550 500,590 C550,615 580,610 630,570 C680,530 730,480 780,450 C830,420 880,420 930,450 M53,350 C83,370 183,500 233,500 C283,500 300,460 350,500 C400,540 420,600 500,640 C550,665 580,660 630,620 C680,580 730,530 780,500 C830,470 880,470 930,500 M53,400 C83,420 183,550 233,550 C283,550 300,510 350,550 C400,590 420,650 500,690 C550,715 580,710 630,670 C680,630 730,580 780,550 C830,520 880,520 930,550' fill='none' stroke='%233161DD' stroke-width='0.5' stroke-opacity='0.4'/%3E%3Cpath d='M3,10 C33,30 83,150 133,150 C183,150 200,110 250,150 C300,190 320,250 400,290 C450,315 480,310 530,270 C580,230 630,180 680,150 C730,120 780,120 830,150 M3,60 C33,80 83,200 133,200 C183,200 200,160 250,200 C300,240 320,300 400,340 C450,365 480,360 530,320 C580,280 630,230 680,200 C730,170 780,170 830,200 M3,110 C33,130 83,250 133,250 C183,250 200,210 250,250 C300,290 320,350 400,390 C450,415 480,410 530,370 C580,330 630,280 680,250 C730,220 780,220 830,250 M3,160 C33,180 83,300 133,300 C183,300 200,260 250,300 C300,340 320,400 400,440 C450,465 480,460 530,420 C580,380 630,330 680,300 C730,270 780,270 830,300 M3,210 C33,230 83,350 133,350 C183,350 200,310 250,350 C300,390 320,450 400,490 C450,515 480,510 530,470 C580,430 630,380 680,350 C730,320 780,320 830,350 M3,260 C33,280 83,400 133,400 C183,400 200,360 250,400 C300,440 320,500 400,540 C450,565 480,560 530,520 C580,480 630,430 680,400 C730,370 780,370 830,400 M3,310 C33,330 83,450 133,450 C183,450 200,410 250,450 C300,490 320,550 400,590 C450,615 480,610 530,570 C580,530 630,480 680,450 C730,420 780,420 830,450 M3,360 C33,380 83,500 133,500 C183,500 200,460 250,500 C300,540 320,600 400,640 C450,665 480,660 530,620 C580,580 630,530 680,500 C730,470 780,470 830,500 M3,410 C33,430 83,550 133,550 C183,550 200,510 250,550 C300,590 320,650 400,690 C450,715 480,710 530,670 C580,630 630,580 680,550 C730,520 780,520 830,550' fill='none' stroke='%233161DD' stroke-width='0.5' stroke-opacity='0.2'/%3E%3C/svg%3E");
                background-size: cover;
                z-index: 1;
            }
            
            .topo-bg::after {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: radial-gradient(circle at center, rgba(255, 255, 255, 0.8) 0%, rgba(243, 244, 246, 0.4) 70%, rgba(243, 244, 246, 0.2) 100%);
                z-index: 2;
            }
            
            .content-container {
                position: relative;
                z-index: 3;
            }
            
            .auth-card {
                background: white;
                border-radius: 0.5rem;
                box-shadow: 
                    0 10px 25px rgba(49, 97, 221, 0.05),
                    0 4px 10px rgba(0, 0, 0, 0.03),
                    0 0 0 1px rgba(49, 97, 221, 0.1);
            }
        </style>
    </head>
    <body class="font-adineue text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 topo-bg">
            <div class="content-container">
                <a href="/">
                    <x-application-logo class="w-32 h-auto fill-current text-[#3161DD]" />
                </a>
            </div>

            <div class="content-container w-full sm:max-w-md mt-6 px-6 py-4 auth-card sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>