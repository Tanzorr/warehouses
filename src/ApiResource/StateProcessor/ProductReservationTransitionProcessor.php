<?php

namespace App\ApiResource\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\ReservationStatusInput;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Registry;

final class ProductReservationTransitionProcessor implements ProcessorInterface
{

    public const WORKFLOW_NAME = 'product_reservation_workflow';

    public function __construct(
        private readonly Registry          $workflowRegistry,
    ){
        
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        /** @var ReservationStatusInput $payload */
        $payload = $data;
        $productReservation = $context['data'];
        $transition = $payload->transition;

        if(empty($transition)){
            throw new InvalidArgumentException("Product reservation transition name is not provided.");
        }

        $workflow = $this->workflowRegistry->get($productReservation, self::WORKFLOW_NAME);

        if ($workflow->can($productReservation, $transition)) {
            $workflow->apply($productReservation, $transition);
        }

        // тут укажи логику формата if (!$workflow->can($transition)){ throw new ...; }
        // else $workflow->apply($transition)
        // после чего убери её из event listenet

        
        return $productReservation;
    }
}