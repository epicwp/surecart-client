<?php

namespace SureCart\Api;

class Pub extends BaseApi
{
    protected function getPath(): string
    {
        return '';
    }

    /**
     * Public activation API.
     *
     * @return Pub\Activation
     */
    public function activation(): Pub\Activation
    {
        return new Pub\Activation($this->getClient());
    }

    /**
     * Public license API.
     *
     * @return Pub\License
     */
    public function license(): Pub\License
    {
        return new Pub\License($this->getClient());
    }
}
