@props(['imagen', 'titulo', 'descripcion', 'id', 'num'])

<div
    class="bg-white px-7 py-8 rounded-lg shadow-md flex flex-col justify-center items-center text-center transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 group h-[450px] lg:h-[392px]">
    <div class="flex flex-col items-center justify-center h-full">
        <div class="p-3 transition-all duration-500 group-hover:scale-110 items-center">
            <img src="{{ asset('storage/' . $imagen) }}" alt="Icon"
                class="object-contain transition-all duration-300 group-hover:brightness-110">
        </div>
        <div class="w-full">
            <h3 class="text-2xl text-black font-semibold py-4 transition-all duration-300 group-hover:text-main-color">
                {{ $titulo }}</h3>
            <div class="text-black text-sm leading-relaxed transition-all duration-300 group-hover:text-gray-700">
                {!! $descripcion !!}
            </div>
        </div>
    </div>
</div>
