<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Crear Nuevo Ticket</h2>

    {{-- Mensajes de feedback (éxito/error) --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="saveTicket" class="space-y-6">
        {{-- Título del Ticket --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Título del Ticket</label>
            <input type="text" id="name" wire:model.live.debounce.500ms="form.name"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('form.name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Descripción --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea id="description" wire:model.live.debounce.500ms="form.description" rows="4"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            @error('form.description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Tipo de Ticket (Des desplegable) --}}
        <div>
            <label for="typeId" class="block text-sm font-medium text-gray-700">Tipo de Ticket</label>
            <select id="typeId" wire:model="form.typeId"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Selecciona un tipo</option>
                @foreach($tipoTickets as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                @endforeach
            </select>
            @error('form.typeId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Modo de Transporte (Desplegable) --}}
        <div>
            <label for="modeOfTransport" class="block text-sm font-medium text-gray-700">Modo de Transporte</label>
            <select id="modeOfTransport" wire:model="form.modeOfTransport"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Selecciona un modo</option>
                @foreach($modoTransportes as $modo)
                    <option value="{{ $modo->id }}">{{ $modo->name }}</option>
                @endforeach
            </select>
            @error('form.modeOfTransport') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Producto (Desplegable) --}}
        <div>
            <label for="productId" class="block text-sm font-medium text-gray-700">Producto</label>
            <select id="productId" wire:model="form.productId"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Selecciona un producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->name }}</option>
                @endforeach
            </select>
            @error('form.productId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Origen --}}
        <div>
            <label for="origin" class="block text-sm font-medium text-gray-700">Origen</label>
            <input type="text" id="origin" wire:model.live.debounce.500ms="form.origin"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('form.origin') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Campo para adjuntar Archivos --}}
        <div class="pt-4">
            <label for="attachments" class="block text-sm font-medium text-gray-700">Adjuntar Documentos (PDF, DOC, XLS, JPG, PNG - max 5MB c/u)</label>
            <input type="file" id="attachments" wire:model="attachments" multiple
                   class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('attachments.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            {{-- Opcional: Mostrar progreso de subida si quieres --}}
            <div x-data="{ isUploading: false, progress: 0 }"
                 x-on:livewire-upload-start="isUploading = true"
                 x-on:livewire-upload-finish="isUploading = false"
                 x-on:livewire-upload-error="isUploading = false"
                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                <div x-show="isUploading" class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="bg-indigo-600 h-2.5 rounded-full" :style="`width: ${progress}%`"></div>
                </div>
            </div>

            {{-- Mostrar los archivos seleccionados para subir --}}
            @if ($attachments)
                <ul class="mt-2 text-sm text-gray-600">
                    @foreach ($attachments as $file)
                        <li>{{ $file->getClientOriginalName() }} ({{ round($file->getSize() / 1024 / 1024, 2) }} MB)</li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Botón de Guardar --}}
        <div class="pt-4">
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Crear Ticket</span>
                <span wire:loading>Guardando Ticket...</span>
            </button>
        </div>
    </form>
</d