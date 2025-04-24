<?php

namespace SureCart\Api\Pub;

use SureCart\Api\BaseLicense;

class License extends BaseLicense
{
    protected function getPath(): string
    {
        return '/public/licenses/%s';
    }

    public function show(string $id): array
    {
        return parent::show($id);
    }

    /**
     * Exposes the current release media for the license.
     *
     * @param  string $key       License key.
     * @param  string $id        The ID of an activation associated with this license. This is required to expose the current release if the license has an activation limit.
     * @param  int    $exposeFor Sets how long a private media URL should be valid for, in seconds. The max value allowed is 86400 or 24 hours. When exposing a media through a purchase or license the default value is 900 or 15 minutes.
     * @return array{
     *   id?: string,
     *   object: string,
     *   byte_size: int,
     *   content_type: string,
     *   extension: string,
     *   filename: string,
     *   height?: int,
     *   width?: int,
     *   public_access: bool,
     *   release_json?: array<string,mixed>,
     *   url?: string,
     *   url_expires_at?: int,
     *   created_at?: int,
     *   updated_at?: int,
     * }
     */
    public function expose(string $key, string $id, int $exposeFor = 900): array
    {
        $path = \sprintf(
            '%s/%s%s',
            $this->buildPath($key),
            'expose_current_release',
            $this->buildQuery([
                'activation_id' => $id,
                'expose_for' => $exposeFor,
            ]),
        );


        return $this->get($path);
    }
}
