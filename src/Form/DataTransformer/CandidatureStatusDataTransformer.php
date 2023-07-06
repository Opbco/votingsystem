<?php

namespace App\Form\DataTransformer;

use App\Form\CandidatureStatus;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class CandidatureStatusDataTransformer implements DataTransformerInterface
{
    public function transform($value): ?string
    {
        $status = $this->reverseTransform($value);

        return $status instanceof CandidatureStatus ? $status->getValue() : null;
    }

    public function reverseTransform($value): ?CandidatureStatus
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof CandidatureStatus) {
            return $value;
        }

        try {
            return CandidatureStatus::byValue($value);
        } catch (\Throwable $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
