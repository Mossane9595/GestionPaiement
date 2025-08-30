<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::create('pieces_jointes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_id')->constrained('paiements')->onDelete('cascade');
            $table->string('chemin'); // chemin dans storage
            $table->string('type')->nullable(); // mime ou extension
            $table->integer('taille')->nullable(); // en octets
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pieces_jointes');
    }
};
