<?php

use App\Enum\MethodEnum;
use App\Enum\APITypeEnum;
use App\Models\Certificate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlsTable extends Migration
{
    public function up()
    {
        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('url');
            $table->json('headers')->nullable();
            $table->string('method')
                ->default(MethodEnum::POST->value);
            $table->string('service_type')
                ->default(APITypeEnum::SOAP->value);
            $table->text('soap_action')->nullable();
            $table->text('request');
            $table->text('expected_response')->nullable();

            // authentication Certificate
            $table->$table->foreignIdFor(Certificate::class)
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('urls');
    }
}
