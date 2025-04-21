<?php

namespace SureCart\Api;

use SureCart\Api\BaseApi;

/**
 * Api for managing activations.
 *
 * @phpstan-type ActivationReq array{fingerprint: string, name?: string, license: string}
 */
abstract class BaseActivation extends BaseApi
{
    /**
     * Get an existing activation by ID.
     *
     * @param  string $id The activation ID.
     * @return array{
     *   id?: string,
     *   object: string,
     *   name?: string,
     *   counted: bool,
     *   license?: string,
     *   created_at: int,
     *   updated_at?: int,
     * }
     */
    public function show(string $id): array
    {
        return $this->get($this->buildPath($id));
    }

    /**
     * Create a new activation.
     *
     * If an activation already exists with the same `fingerprint` then it will be updated and returned.
     *
     * @param  ActivationReq $args License arguments.
     * @return array{
     *   id?: string,
     *   object: string,
     *   name?: string,
     *   counted: bool,
     *   license?: string,
     *   created_at: int,
     *   updated_at?: int,
     * }|array{
     *   http_status: string,
     *   type: string,
     *   code: string,
     *   message: string,
     *   validation_errors?: array<array{
     *     attribute: string,
     *     type: string,
     *     code: string,
     *     options: array<string,mixed>,
     *     message: string,
     *   }>
     * }
     */
    public function create(array $args): array|string
    {
        return $this->post($this->buildPath(''), [ 'activation' => $args ]);
    }

    /**
     * Update an existing activation.
     *
     * @param  string $id   The activation ID.
     * @param  ActivationReq  $args Activation arguments.
     * @return array{
     *   id?: string,
     *   object: string,
     *   name?: string,
     *   counted: bool,
     *   license?: string,
     *   created_at: int,
     *   updated_at?: int,
     * }
     */
    public function update(string $id, array $args): array
    {
        return $this->patch($this->buildPath($id), $args);
    }

    public function delete(string $id): array
    {
        return $this->deleteRaw($this->buildPath($id));
    }
}
