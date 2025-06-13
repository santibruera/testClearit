<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Ticket #{{ $ticket->id }}</h2>

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

    @if ($isClosed)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">
            <strong class="font-bold">¡Atención!</strong>
            <span class="block sm:inline">Este ticket está cerrado y no puede ser editado.</span>
        </div>
    @endif

    <form wire:submit.prevent="updateTicket" class="space-y-6">
        {{-- Título del Ticket --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Título del Ticket</label>
            <input type="text" id="name" wire:model.live.debounce.500ms="form.name"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $canEditAllFields ? '' : 'bg-gray-100 cursor-not-allowed' }}"
                   {{ $canEditAllFields ? '' : 'disabled' }}>
            @error('form.name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Descripción --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea id="description" wire:model.live.debounce.500ms="form.description" rows="4"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $canEditAllFields ? '' : 'bg-gray-100 cursor-not-allowed' }}"
                      {{ $canEditAllFields ? '' : 'disabled' }}></textarea>
            @error('form.description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Tipo de Ticket (Desplegable) --}}
        <div>
            <label for="typeId" class="block text-sm font-medium text-gray-700">Tipo de Ticket</label>
            <select id="typeId" wire:model="form.typeId"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $canEditAllFields ? '' : 'bg-gray-100 cursor-not-allowed' }}"
                    {{ $canEditAllFields ? '' : 'disabled' }}>
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
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $canEditAllFields ? '' : 'bg-gray-100 cursor-not-allowed' }}"
                    {{ $canEditAllFields ? '' : 'disabled' }}>
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
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $canEditAllFields ? '' : 'bg-gray-100 cursor-not-allowed' }}"
                    {{ $canEditAllFields ? '' : 'disabled' }}>
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
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm {{ $canEditAllFields ? '' : 'bg-gray-100 cursor-not-allowed' }}"
                   {{ $canEditAllFields ? '' : 'disabled' }}>
            @error('form.origin') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Estado del Ticket (Solo lectura para el usuario) --}}
        <div>
            <p class="text-sm font-medium text-gray-700">Estado Actual:</p>
            <p class="text-lg text-gray-900 font-bold">{{ $ticket->ticketStatus->name ?? 'N/A' }}</p>
        </div>

        {{-- Asignado a (Solo lectura para el usuario) --}}
        <div>
            <p class="text-sm font-medium text-gray-700">Asignado a:</p>
            <p class="text-lg text-gray-900">{{ $ticket->assignedUser->name ?? 'Sin Asignar' }}</p>
        </div>

        {{-- Sección de Archivos Adjuntos Existentes --}}
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Archivos Adjuntos Actuales</h3>
            @if($existingAttachments->isEmpty())
                <p class="text-gray-600">No hay archivos adjuntos actualmente.</p>
            @else
                <ul class="list-disc list-inside space-y-2">
                    @foreach($existingAttachments as $attachment)
                        <li class="flex items-center justify-between text-gray-700">
                            <span>
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $attachment->file_name }} ({{ round($attachment->file_size / 1024 / 1024, 2) }} MB)
                                </a>
                                <span class="text-gray-500 text-sm ml-2">Subido por: {{ $attachment->uploader->name ?? 'Desconocido' }}</span>
                            </span>
                            @if (!$isClosed) {{-- Solo permite eliminar si el ticket no está cerrado --}}
                                <button wire:click="deleteAttachment({{ $attachment->id }})"
                                        wire:confirm="¿Estás seguro de que quieres eliminar este archivo?"
                                        class="text-red-600 hover:text-red-900 text-sm ml-4"
                                        type="button">
                                    Eliminar
                                </button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Campo para adjuntar Nuevos Archivos --}}
        @if ($canUploadFiles)
            <div class="pt-4">
                <label for="attachments" class="block text-sm font-medium text-gray-700">Adjuntar Nuevos Documentos (PDF, DOC, XLS, JPG, PNG - max 5MB c/u)</label>
                <input type="file" id="attachments" wire:model="attachments" multiple
                       class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('attachments.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                {{-- Opcional: Mostrar progreso de subida --}}
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
                @if (!empty($attachments))
                    <p class="mt-2 text-sm font-medium text-gray-700">Archivos listos para subir:</p>
                    <ul class="mt-1 text-sm text-gray-600 list-disc list-inside">
                        @foreach ($attachments as $file)
                            <li>{{ $file->getClientOriginalName() }} ({{ round($file->getSize() / 1024 / 1024, 2) }} MB)</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded relative text-sm">
                <p>No puedes adjuntar archivos en el estado actual de este ticket.</p>
            </div>
        @endif

        {{-- Botón de Guardar Cambios --}}
        @if (!$isClosed) {{-- Solo mostrar el botón si el ticket no está cerrado --}}
            <div class="pt-4">
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Guardar Cambios</span>
                    <span wire:loading>Guardando...</span>
                </button>
            </div>
        @endif
    </form>
</div>
