<?php

namespace App\Serializer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EntityNormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $excludedCandidates = $this->extractMoreComplexData($type);

        $excludeFields = array_map(function (\ReflectionProperty $e) {
            return $e->getName();
        }, $excludedCandidates);

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $this->denormalizeMoreComplexData($excludedCandidates, $data);

        return $serializer->deserialize(json_encode($data), $type, $format, [AbstractNormalizer::IGNORED_ATTRIBUTES => $excludeFields]);
    }

    private function denormalizeMoreComplexData(array $candidates, mixed $data)
    {
        array_map(function (\ReflectionProperty $candidate) use ($data) {
            $property = $candidate->getName();
            $class = $candidate->getType()->getName();
            if ($class === Collection::class) {
                $instance = new ArrayCollection();
            } else {
                $instance = new $class();
            }

            $instanceReflection = new \ReflectionObject($instance);

            if ($instanceReflection->isIterable() && in_array($property, $data) && is_array($data[$property])) {
                $ii = '';
            }
            $candidate->getAttributes()[0]->getArguments();
        }, $candidates);
    }

    private function extractMoreComplexData(string $class): array
    {
        $reflexion = new \ReflectionClass($class);
        $props = $reflexion->getProperties();

        return array_filter($props, function (\ReflectionProperty $property) {
            return preg_match("/\w\\\\\w/", $property->getType()?->getName()) > 0;
        });
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return true;
    }
}
