<?php

namespace App\Serializer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @deprecated("I abondonned this denormalizer or now, continue on it will take me much more time of concept and implementation, to be continue...")
 * Class EntityNormalizer
 * @package App\Serializer
 */
class EntityNormalizer implements DenormalizerInterface
{
    private function getSerializer(): SerializerInterface
    {
        return new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $excludedCandidates = $this->extractMoreComplexData($type);

        $excludeFields = array_map(function (\ReflectionProperty $e) {
            return $e->getName();
        }, $excludedCandidates);


        $this->denormalizeMoreComplexData($excludedCandidates, $data);

        return $this->getSerializer()->deserialize(json_encode($data), $type, $format, [AbstractNormalizer::IGNORED_ATTRIBUTES => $excludeFields]);
    }

    private function denormalizeMoreComplexData(array $candidates, mixed $data)
    {
        array_map(function (\ReflectionProperty $candidate) use ($data) {

            $attributeDatas = (array_filter($candidate->getAttributes(), function (\ReflectionAttribute $attribute) {
                return in_array('targetEntity', $attribute->getArguments());
            }));

            if (count($attributeDatas) > 0) {
                $property = $candidate->getName();
                $propertyType = $candidate->getType()->getName();

                if ($propertyType === Collection::class) {
                    $instance = new ArrayCollection();
                } else {
                    $instance = new $propertyType();
                }

                /** @var \ReflectionAttribute $attribute */
                $attribute = array_values($attributeDatas)[0];
                $arguments = $attribute->getArguments();


                $instanceReflection = new \ReflectionObject($instance);

                if ($instanceReflection->isIterable() && isset($data[$property]) && is_array($data[$property])) {
                    $rowData = $data[$property];
                    for ($r = 0; $r < $rowData; $r++) {
                        //TODO: HERE HANDLE EMBED RELATION DATA MODEL
                        $_data = $rowData[$r];
                        $mm = $this->getSerializer()->deserialize(json_encode($_data), $arguments['targetEntity'], 'json');
                        $i = '';
                    }
                }
            }
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
        return false;
    }
}
