<?php

namespace App\Serializer;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EntityNormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $collectionDenormalizeable = $this->extractUnsupportableDenormalize($type);
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        return $serializer->deserialize(json_encode($data), $type, $format);
    }

    private function extractUnsupportableDenormalize(string $class): array
    {
        $reflexion = new \ReflectionClass($class);
        $props = $reflexion->getProperties();

        return array_filter($props, function (\ReflectionProperty $property) {
            return Collection::class === $property->getType()?->getName();
        });
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return true;
    }
}
