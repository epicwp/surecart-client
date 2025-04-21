<?php

namespace SureCart\Api;

class License extends BaseLicense
{
    protected function getPath(): string
    {
        return '/licenses/%s';
    }

    /**
     * Return a list of your licenses
     *
     * @param  array{
     *   customer_ids?: array<string>,
     *   ids?: array<string>,
     *   product_ids?: array<string>,
     *   purchase_ids?: array<string>,
     *   limit?: int,
     *   page?: int,
     *   query?: string,
     *   revoked?: bool,
     *   sort?: 'activations_count'|'created_at'|'updated_at',
     * } $params Search parameters.
     * @return array{
     *   object: string,
     *   pagination: array{
     *     count: int,
     *     limit?: int,
     *     page?: int,
     *   },
     *   data: array<int,array{
     *     id?: string,
     *     key: string,
     *     object: string,
     *     activation_limit: int,
     *     activations_count: int,
     *     revokes_at?: int,
     *     status: 'active'|'inactive'|'revoked',
     *     current_release?: string,
     *     price: string,
     *     product: string,
     *     variant?: string,
     *     created_at?: int,
     *     updated_at?: int,
     *   }>
     * }
     */
    public function all(array $params = []): array
    {
        return $this->get($this->buildPath(''), \array_filter($params));
    }

    /**
     * Create a new license.
     *
     * @param  string   $purchase        UUID of the purchase.
     * @param  string   $key             Unique identifier for the license. Default value is an UUID. But can be set to any unique value within the scope of the account.
     * @param  int|null $activationLimit Maximum number of actviations allowed. If set to `null` then the license will have no activation limit.
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
    public function create(string $purchase, string $key, ?int $activationLimit = null): array
    {
        return $this->post(
            $this->buildPath(''),
            [
                'activation_limit' => $activationLimit,
                'key'              => $key,
                'purchase'         => $purchase,
            ],
        );
    }
}
