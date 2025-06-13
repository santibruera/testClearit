<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Ticket;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // Columna 'id' como clave primaria auto-incrementable

            // Columnas de datos del ticket
            $table->string('name'); // Título o nombre del ticket
            $table->text('description')->nullable(); // Descripción detallada del ticket (opcional)
            $table->foreignId('typeId')
                  ->constrained('tipo_tickets') 
                  ->onDelete('cascade'); 

            $table->foreignId('modeOfTransport')
                  ->constrained('modo_transportes') // Relacionado con la tabla 'modo_transportes'
                  ->onDelete('cascade');

            $table->foreignId('productId')
                  ->constrained('productos') // Relacionado con la tabla 'productos'
                  ->onDelete('cascade');

            $table->foreignId('status')
                  ->constrained('ticket_statuses') // Relacionado con la tabla 'ticket_statuses'
                  ->onDelete('cascade');
            $table->string('origin')->nullable(); // Origen del ticket (string, opcional)
    
            $table->foreignId('createdBy')
                  ->constrained('users') // Relacionado con la tabla 'users'
                  ->onDelete('cascade'); // Si se elimina el usuario creador, los tickets que creó también se eliminan

            $table->foreignId('assignedTo')
                  ->nullable() // Puede no estar asignado inicialmente
                  ->constrained('users') // Relacionado con la tabla 'users'
                  ->onDelete('set null'); // Si se elimina el usuario asignado, el campo se pone a NULL

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
