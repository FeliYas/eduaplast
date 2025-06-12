@props(['novedad'])

<div class="bg-gray-50 h-[493px] rounded-md border border-gray-200">
    <img src="{{ asset($novedad->path) }}" alt="{{ $novedad->titulo }}" class="object-cover w-full h-[246px]">
    <div class="p-4 h-[247px] flex flex-col justify-between">
        <p class="text-orange-700 font-extrabold">{{ Str::upper($novedad->epigrafe) }}</p>
        <div>
            <h3 class="text-2xl font-medium text-black">{{ $novedad->titulo }}</h3>
        </div>

        <div class="text-gray-500 line-clamp-2 h-[65px] py-3 font-light">{!! $novedad->descripcion !!}</div>

        <div class="flex gap-6 text-black h-15">
            <a href="{{ route('novedad', ['id' => $novedad->id]) }}" class="hover:underline text-gray-700 mt-8">Leer
                más</a>
        </div>
    </div>
</div>
