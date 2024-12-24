<?php

use App\Enum\MethodEnum;
use App\Enum\APITypeEnum;
use App\Models\Certificate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('url');
            $table->string('service_type')
                ->default(APITypeEnum::SOAP->value);
            $table->string('method')
                ->default(MethodEnum::POST->value);
            $table->json('headers')->nullable();
            $table->text('request');
            $table->text('expected_response')->nullable();

            // authentication Certificate
            $table->foreignIdFor(Certificate::class);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
