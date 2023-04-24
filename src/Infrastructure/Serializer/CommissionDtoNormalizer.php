<?php


declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Application\Commission\Dto\CommissionDto;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CommissionDtoNormalizer implements DenormalizerInterface
{
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $result = [];
        foreach ($data as $item){
            $result[] = new CommissionDto(
                $item['date'],
                (int) $item['userId'],
                $item['clientType'],
                $item['operationType'],
                (float) $item['amount'],
                $item['currency'],
            );
        }

        return $result;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === CommissionDto::class.'[]';
    }
}