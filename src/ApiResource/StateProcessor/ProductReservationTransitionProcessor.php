<?php

namespace App\ApiResource\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

final class ProductReservationTransitionProcessor implements ProcessorInterface
{
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $request = $context['request'];
        /** @var Request $request */
        $transition = $request->attributes->get('action');
        if(empty($transition)){
            throw new InvalidArgumentException("Product reservation transition name is not provided.");
        }

        // тут укажи логику формата if (!$workflow->can($transition)){ throw new ...; }
        // else $workflow->apply($transition)
        // после чего убери её из event listenet

        
        return $data;
    }
}
