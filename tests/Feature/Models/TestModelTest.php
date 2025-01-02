<?php

namespace Tests\Models;

use App\Models\Test;
use Illuminate\Support\Carbon;

test('executionTime calculates the difference between response and request timestamps', function () {
    $test = new Test([
        'request_timestamp' => Carbon::now()->subSeconds(10),
        'response_timestamp' => Carbon::now(),
    ]);

    expect($test->executionTime)->toBe(10);
});

test('executionTime returns 0 when timestamps are the same', function () {
    $timestamp = Carbon::now();

    $test = new Test([
        'request_timestamp' => $timestamp,
        'response_timestamp' => $timestamp,
    ]);

    expect($test->executionTime)->toBe(0);
});

test('executionTime handles response timestamp earlier than request timestamp', function () {
    $test = new Test([
        'request_timestamp' => Carbon::now(),
        'response_timestamp' => Carbon::now()->subSeconds(5),
    ]);

    expect($test->executionTime)->toBe(-5);
});
