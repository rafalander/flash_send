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
        Schema::create('encomendas', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('descricao');
            $table->date('data_recebimento');
            $table->boolean('retirada')->default(false);
            $table->string('origem')->nullable();
            $table->string('codigo_rastreamento')->nullable();
            $table->foreignId('morador_id')->constrained('moradores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encomendas');
    }
};
