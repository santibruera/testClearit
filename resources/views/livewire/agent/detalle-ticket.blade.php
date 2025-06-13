<x-app-layout title="Mis Tickets">
    <x-slot name="header">
        <h2 class="text-xl font-bold">Inicio</h2>
    </x-slot>


  @livewire('agent.ticket-details', ['ticketId' => $ticketId])

</x-app-layout>
