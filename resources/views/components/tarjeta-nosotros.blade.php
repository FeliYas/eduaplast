@props(['imagen', 'titulo', 'descripcion', 'id', 'num'])

<div
    class="bg-white px-6 py-8 rounded-lg shadow-md flex flex-col justify-between items-center text-center transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 group h-[392px]">
    <div class="relative">
        <div class="p-3 transition-all duration-500 group-hover:scale-110 items-center">
            <img src="{{ asset('storage/' . $imagen) }}" alt="Icon"
                class="object-contain transition-all duration-300 group-hover:brightness-110">
        </div>
    </div>
    <div class="w-full flex-1">
        <h3 class="text-xl text-gray-800 font-semibold py-6 transition-all duration-300 group-hover:text-main-color">
            {{ $titulo }}</h3>
        <div class="text-black text-sm leading-relaxed transition-all duration-300 group-hover:text-gray-700">
            {!! $descripcion !!}
        </div>
    </div>
</div>
