<?php

namespace SureCart\Api;

class Activation extends BaseActivation
{
    protected function getPath(): string
    {
        return '/activations/%s';
    }

    /**
     * Returns a list of your activations.
     *
     * @param  array<string>    $ids        Return objects with the given IDs.
     * @param  array<string>    $licenseIds Return objects that belong to the given license IDs.
     * @param  null|int<0,100>  $limit      Limit on the number of objects to return.
     * @param  null|int         $page       Page number to return.
     * @return array{
     *   object: string,
     *   pagination: array{
     *     count: int,
     *     limit?: int,
     *     page?: int,
     *   },
     *   data: array<int,array{
     *     id?: string,
     *     object: string,
     *     name?: string,
     *     counted: bool,
     *     license?: string,
     *     created_at: int,
     *     updated_at?: int,
     *   }>
     * }
     */
    public function all(array $ids = [], array $licenseIds = [], ?int $limit = null, ?int $page = null): array
    {
        $params = \array_filter([
            'ids' => $ids,
            'license_ids' => $licenseIds,
            'limit' => $limit,
            'page' => $page,
        ]);

        return $this->get($this->buildPath(''), $params);
    }
}
