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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('paiement_periodique_id')->nullable()->constrained('paiements_periodiques')->onDelete('set null');
            $table->foreignId('mode_paiement_id')->nullable()->constrained('modes_paiement')->onDelete('set null');
            $table->string('description');
            $table->decimal('montant', 15, 2);
            $table->enum('statut', ['EN_ATTENTE', 'REUSSI', 'ECHEC'])->default('EN_ATTENTE');
            $table->timestamp('traite_a')->nullable(); // date/heure de traitement final
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};
