<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "Документація до API бордової дошки",
    title: "BordApp API",
    contact: new OA\Contact(email: "support@bord-app.com")
)]
#[OA\SecurityScheme(
    securityScheme: 'Bearer',
    type: 'http',
    bearerFormat: 'JWT',
    scheme: 'bearer'
)]
#[OA\Server(
    url: "http://localhost",
    description: "Основний сервер"
)]
class HomeController
{
    public function home(): array
    {
        return [
            'name' => 'Board API',
        ];
    }
}
