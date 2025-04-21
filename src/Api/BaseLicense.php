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
     * }
     */
    public function find(string $id): array
    {
        return $this->get($this->buildPath($id));
    }
}
