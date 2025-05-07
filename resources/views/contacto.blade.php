@extends('layouts.app')

@section('title', 'Contacto')

@section('content')

<!-- HERO -->
<section class="relative py-24 min-h-[40vh] flex items-center justify-center bg-[#F8F9FC] text-[#111827]">
    <div class="container mx-auto px-4 text-center">
        <span class="inline-block px-6 py-2 mb-6 text-[#3161DD] bg-[#3161DD]/10 border border-[#3161DD]/20 rounded-full text-sm font-semibold uppercase">{{ $contacto->tag ?? 'Contacto' }}</span>
        <h1 class="text-5xl font-bold mb-4">{{ $contacto->title ?? 'Hablemos de tu proyecto' }}</h1>
        <p class="max-w-xl mx-auto text-lg text-gray-500">{{ $contacto->description ?? 'Estamos listos para ayudarte a impulsar tu empresa. Contáctanos y descubre cómo nuestras soluciones pueden transformar tu negocio.' }}</p>
    </div>
</section>

<!-- CONTACT FORM + INFO -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">

        <div class="bg-white border border-gray-200 rounded-2xl p-12 shadow-sm hover:shadow-md transition grid grid-cols-1 md:grid-cols-2 gap-12">

            <!-- Info -->
            <div class="space-y-8">
                @if($contacto->phone)
                <div>
                    <p class="text-[#3161DD] text-sm font-medium mb-1">Teléfono</p>
                    <p class="text-gray-800 text-lg">{{ $contacto->phone }}</p>
                </div>
                @endif

                @if($contacto->email)
                <div>
                    <p class="text-[#3161DD] text-sm font-medium mb-1">Email</p>
                    <p class="text-gray-800 text-lg">{{ $contacto->email }}</p>
                </div>
                @endif

                @if($contacto->address)
                <div>
                    <p class="text-[#3161DD] text-sm font-medium mb-1">Dirección</p>
                    <p class="text-gray-800 text-lg">{{ $contacto->address }}</p>
                </div>
                @endif

                @if($contacto->hours)
                <div>
                    <p class="text-[#3161DD] text-sm font-medium mb-1">Horario</p>
                    <p class="text-gray-800 text-lg">{{ $contacto->hours }}</p>
                </div>
                @endif

                <div>
                    <p class="text-[#3161DD] text-sm font-medium mb-3">Síguenos en redes sociales</p>
                    <div class="flex space-x-4">
                        @if($contacto->facebook && $contacto->facebook != '#')
                        <a href="{{ $contacto->facebook }}" class="w-10 h-10 rounded-full bg-gray-100 text-[#3161DD] flex items-center justify-center hover:bg-[#3161DD] hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        
                        @if($contacto->instagram && $contacto->instagram != '#')
                        <a href="{{ $contacto->instagram }}" class="w-10 h-10 rounded-full bg-gray-100 text-[#3161DD] flex items-center justify-center hover:bg-[#3161DD] hover:text-white transition"><i class="fab fa-instagram"></i></a>
                        @endif
                        
                        @if($contacto->twitter && $contacto->twitter != '#')
                        <a href="{{ $contacto->twitter }}" class="w-10 h-10 rounded-full bg-gray-100 text-[#3161DD] flex items-center justify-center hover:bg-[#3161DD] hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        @endif
                        
                        @if($contacto->whatsapp && $contacto->whatsapp != '#')
                        <a href="{{ $contacto->whatsapp }}" class="w-10 h-10 rounded-full bg-gray-100 text-[#3161DD] flex items-center justify-center hover:bg-[#3161DD] hover:text-white transition"><i class="fab fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="space-y-6">
                <form action="{{ route('contacto.enviar') }}" method="POST" class="space-y-6">
                    @csrf

                    <input type="text" name="nombre" placeholder="Nombre" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#3161DD]" required>

                    <input type="email" name="email" placeholder="Correo electrónico" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#3161DD]" required>

                    <input type="tel" name="telefono" placeholder="Teléfono" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#3161DD]">

                    <input type="text" name="asunto" placeholder="Asunto" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#3161DD]" required>

                    <textarea name="mensaje" placeholder="Mensaje" rows="4" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#3161DD]" required></textarea>

                    <button type="submit" class="w-full bg-[#3161DD] hover:bg-[#2651c0] text-white font-semibold py-3 rounded-lg transition">
                        Enviar mensaje
                    </button>
                </form>
            </div>

        </div>

    </div>
</section>

@endsection