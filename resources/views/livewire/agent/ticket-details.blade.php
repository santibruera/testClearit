<div class="container mx-auto p-4">
    {{-- Mensajes de feedback --}}
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

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Detalles del Ticket #{{ $ticket->id }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Información del Ticket</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Título:</p>
                <p class="text-lg text-gray-900">{{ $ticket->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Descripción:</p>
                <p class="text-gray-900 leading-relaxed">{{ $ticket->description }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Tipo:</p>
                <p class="text-gray-900">{{ $ticket->tipoTicket->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Modo de Transporte:</p>
                <p class="text-gray-900">{{ $ticket->modoTransporte->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Producto:</p>
                <p class="text-gray-900">{{ $ticket->producto->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Origen:</p>
                <p class="text-gray-900">{{ $ticket->origin ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Creado Por:</p>
                <p class="text-gray-900">{{ $ticket->user->name ?? 'Desconocido' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Asignado A:</p>
                <p class="text-gray-900">{{ $ticket->assignedUser->name ?? 'Sin Asignar' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Fecha de Creación:</p>
                <p class="text-gray-900">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Última Actualización:</p>
                <p class="text-gray-900">{{ $ticket->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Sección para cambiar el estado --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Cambiar Estado del Ticket</h2>
        <div class="flex items-end space-x-4">
            <div>
                <label for="newStatusId" class="block text-sm font-medium text-gray-700">Estado Actual: <span class="font-bold text-indigo-600">{{ $ticket->ticketStatus->name ?? 'N/A' }}</span></label>
                <select id="newStatusId" wire:model="newStatusId"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach($ticketStatuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
                @error('newStatusId') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <button wire:click="updateStatus"
                    class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Actualizar Estado</span>
                <span wire:loading>Guardando...</span>
            </button>
        </div>
    </div>

    {{-- Sección de Archivos Adjuntos --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Archivos Adjuntos</h2>
        @if($ticket->attachments->isEmpty())
            <p class="text-gray-600">No hay archivos adjuntos para este ticket.</p>
        @else
            <ul class="list-disc list-inside space-y-2">
                @foreach($ticket->attachments as $attachment)
                    <li>
                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $attachment->file_name }} ({{ round($attachment->file_size / 1024, 2) }} KB)
                        </a>
                        <span class="text-gray-500 text-sm ml-2">Subido por: {{ $attachment->uploader->name ?? 'Desconocido' }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Sección para solicitar más documentos --}}
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Solicitar Más Documentos</h2>
        <div class="space-y-4">
            <div>
                <label for="requestDocumentMessage" class="block text-sm font-medium text-gray-700">Mensaje para el Creador del Ticket:</label>
                <textarea id="requestDocumentMessage" wire:model.live.debounce.500ms="requestDocumentMessage" rows="4"
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                          placeholder="Explica qué documentos adicionales necesitas..."></textarea>
                @error('requestDocumentMessage') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <button wire:click="requestMoreDocuments"
                    class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Enviar Solicitud de Documentos</span>
                <span wire:loading>Enviando...</span>
            </button>
        </div>
    </div>
</div>
