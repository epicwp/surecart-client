<?php

namespace SureCart\Api;

abstract class BaseLicense extends BaseApi
{
    /**
     * Retrieves details of a specific license.
     *
     * @param  string $id License ID.
     * @return array{
     *   id?: string,
     *   key: string,
     *   object: string,
     *   activation_limit: int,
     *   activations_count: int,
     *   revokes_at?: int,
     *   status: 'active'|'inactive'|'revoked',
     *   current_release?: string,
     *   price: string,
     *   product: string,
     *   variant?: string,
     *   created_at?: int,
     *   updated_at?: int,
     * }|array{
     *   http_status: string,
     *   code: string,
     *   message: string,
     * }
     */
    public function show(string $id): array
    {
        return $this->get($this->buildPath($id));
    }
}
