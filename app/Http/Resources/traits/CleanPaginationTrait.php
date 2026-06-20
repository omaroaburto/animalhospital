<?php

namespace App\Http\Resources\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

trait CleanPaginationTrait
{
    /**
     * Formatea la metadata numérica estándar si viene de un paginador.
     */
    protected function getCleanMeta(): array
    {
        $meta = [
            'version' => '1.0.0',
        ];

        if (method_exists($this, 'currentPage')) {
            return array_merge($meta, [
                'current_page' => $this->currentPage(),
                'last_page'    => $this->lastPage(),
                'per_page'     => $this->perPage(),
                'total'        => $this->total(),
            ]);
        }

        return $meta;
    }

    /**
     * 🌟 LA SOLUCIÓN DEFINITIVA:
     * Tomamos el control total de la respuesta y forzamos a Laravel
     * a enviar SOLO el formato exacto que tú definiste en toArray().
     */
    public function withResponse(Request $request, JsonResponse $response): void
    {
        // Reemplazamos TODO el JSON de salida por lo que devuelve tu método toArray()
        $response->setData($this->toArray($request));
    }
}
