<?php

namespace SureCart\Api\Pub;

use SureCart\Api\BaseActivation;

/**
 * Api for managing activations.
 *
 * @phpstan-type ActivationReq array{fingerprint: string, name?: string, license: string}
 */
class Activation extends BaseActivation
{
    protected function getPath(): string
    {
        return '/public/activations/%s';
    }
}
