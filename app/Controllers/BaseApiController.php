<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Throwable;

/**
 * BaseApiController
 *
 * Envoltorio de respuesta consistente para la API REST (JSON):
 * { success, message?, data?, errors?, meta? }.
 *
 * attempt() centraliza el manejo de errores: nunca se filtra el mensaje
 * interno de una excepción al cliente, solo se registra en el log.
 *
 * @package App\Controllers
 */
abstract class BaseApiController extends Controller
{
    protected function ok($data = null, string $message = '', int $code = 200, ?array $meta = null)
    {
        $body = ['success' => true];

        if ($message !== '') {
            $body['message'] = $message;
        }

        if ($data !== null) {
            $body['data'] = $data;
        }

        if ($meta !== null) {
            $body['meta'] = $meta;
        }

        return $this->response->setJSON($body)->setStatusCode($code);
    }

    protected function fail(string $message, int $code = 400, ?array $errors = null)
    {
        $body = ['success' => false, 'message' => $message];

        if ($errors !== null) {
            $body['errors'] = $errors;
        }

        return $this->response->setJSON($body)->setStatusCode($code);
    }

    /**
     * Ejecuta $callback y traduce cualquier excepción no controlada en un
     * 500 genérico. El detalle real de la excepción se registra en el log
     * de errores, nunca se devuelve al cliente.
     */
    protected function attempt(callable $callback)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            log_message('error', '{exception}', ['exception' => $e]);

            return $this->fail('Ocurrió un error inesperado. Intenta de nuevo.', 500);
        }
    }
}
