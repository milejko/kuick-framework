<?php

namespace Kuick\Actions;

use Kuick\Http\JsonResponse;
use Kuick\Http\Request;
use Kuick\UI\ActionInterface;

class DefaultRootAction implements ActionInterface
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(['Kuick says: hello!']);
    }
}
