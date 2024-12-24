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
            $table->timestamp('request_date')->nullable();
            $table->text('request_headers')->nullable();
            $table->text('request_certificates')->nullable();
            $table->text('response')->nullable();
            $table->text('expected_response')->nullable();
            $table->timestamp('response_date')->nullable();
            $table->text('server_certificates')->nullable();
            $table->bigInteger('response_time')->nullable();
            $table->boolean('response_ok')->default(true);
            $table->text('curl_info')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
