<?php

use App\Enum\ExecutionStatusEnum;
use App\Models\LoadTest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('load_test_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LoadTest::class);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->string('status')->default(ExecutionStatusEnum::CREATED->value);
            $table->bigInteger('number_requests_effective')->nullable();
            $table->bigInteger('success_number')->nullable();
            $table->bigInteger('failure_number')->nullable();
            $table->json('failure_responses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('load_test_executions');
    }
};
