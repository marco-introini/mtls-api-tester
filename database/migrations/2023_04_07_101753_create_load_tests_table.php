<?php

use App\Models\Api;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('load_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Api::class);
            $table->smallInteger('number_requests')->nullable();
            $table->smallInteger('concurrent_requests')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('load_tests');
    }
};
