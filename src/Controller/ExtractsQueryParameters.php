<?php

namespace Mailer\Controller;

use Psr\Http\Message\RequestInterface;

trait ExtractsQueryParameters {

    /**
     * @param RequestInterface $request
     * @param string $identifier
     * @param int|null $filter
     * @return mixed|null
     */
    protected function getQueryParameter(RequestInterface $request, string $identifier, int $filter = null)
    {
        $queryParamsCouples = explode('&', $request->getUri()->getQuery());
        $queryParams = [];

        foreach ($queryParamsCouples as $couple) {
            $parts = explode('=', $couple);
            $queryParams[$parts[0]] = $parts[1];
        }

        if (!empty($queryParams[$identifier])) {
            if (empty($filter)) {
                return filter_var($queryParams[$identifier], $filter, FILTER_NULL_ON_FAILURE);
            } else {
                return $queryParams[$identifier];
            }
        } else {
            return null;
        }
    }
}