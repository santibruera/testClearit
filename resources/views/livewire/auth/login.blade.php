<div>
    <form wire:submit.prevent="login" class="space-y-6">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required
                       wire:model.live.debounce.1s="email" 
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            @error('email') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div x-data="{ show: false }"> 
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input :type="show ? 'text' : 'password'" name="password" id="password" required
                       wire:model.live.debounce.1s="password" 
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pr-10">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <span class="cursor-pointer" @click="show = !show">
                        <template x-if="!show">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </template>
                        <template x-if="show">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .966-3.003 2.766-5.418 5.093-7.143M4.322 4.322L19.678 19.678M12 5c4.478 0 8.268 2.943 9.542 7a10.07 10.07 0 01-2.99 4.34"></path>
                            </svg>
                        </template>
                    </span>
                </div>
            </div>
            @error('password') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" wire:model.live="remember" {{-- 'remember' puede quedar .live sin debounce --}}
                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-900">
                Recuérdame
            </label>
        </div>

        <div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    wire:loading.attr="disabled"> 
                <span wire:loading.remove>Iniciar Sesión</span>
                <span wire:loading>Cargando...</span>
            </button>
        </div>
    </form>
</div>