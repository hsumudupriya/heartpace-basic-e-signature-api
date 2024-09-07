<?php

use App\Models\SignatureRequest;
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
        Schema::create('signature_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('document_id')->unsigned();
            $table->bigInteger('requested_by')->unsigned();
            $table->bigInteger('requested_from')->unsigned();
            $table->dateTimeTz('deadline');
            $table->json('signature_positions');
            $table->string('status')->default(SignatureRequest::STATUS_PENDING);
            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('requested_by')->references('id')->on('users');
            $table->foreign('requested_from')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_requests');
    }
};
