<?php

header('Content-Type: application/json');

if ('GET' === $_SERVER['REQUEST_METHOD'] && '/health/' === ($_SERVER['PATH_INFO'] ?? '')) {
    try {
        $data = json_encode(['status' => 'OK'], JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        $data = null;
    }

    if (null !== $data) {
        http_response_code(200);
        die($data);
    }

    http_response_code(500);
} else {
    http_response_code(400);
}
