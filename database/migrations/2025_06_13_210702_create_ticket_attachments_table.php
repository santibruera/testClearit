<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // Clave foránea al ticket
            $table->string('file_name'); // Nombre original del archivo
            $table->string('file_path'); // Ruta donde se guarda el archivo en storage
            $table->string('mime_type')->nullable(); // Tipo MIME del archivo (ej. application/pdf)
            $table->unsignedBigInteger('file_size')->nullable(); // Tamaño del archivo en bytes
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade'); // Quién subió el archivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
    }
};
