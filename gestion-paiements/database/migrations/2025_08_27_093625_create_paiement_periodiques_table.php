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
        Schema::create('paiements_periodiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('libelle'); // ex: "Internet - Orange"
            $table->string('fournisseur')->nullable();
            $table->enum('periodicite', ['quotidienne','hebdomadaire','mensuelle','annuelle'])->default('mensuelle');
            $table->decimal('montant_defaut', 15, 2)->nullable();
            $table->date('prochain_paiement')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements_periodiques');
    }
};
