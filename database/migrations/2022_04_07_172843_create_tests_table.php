<?php

use App\Models\API;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(API::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('called_url');
            $table->text('request')->nullable();
            $table->text('request_raw')->nullable();
            $table->timestamp('request_timestamp')->nullable();
            $table->text('request_certificates')->nullable();
            $table->text('response')->nullable();
            $table->text('response_raw')->nullable();
            $table->timestamp('response_timestamp')->nullable();
            $table->text('response_expected')->nullable();
            $table->boolean('response_ok')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
