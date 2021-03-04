<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Service\Attribute\Required;

class UserNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    #[Required]
    public ObjectNormalizer $normalizer;

    /**
     * @inheritDoc
     * @noinspection NullPointerExceptionInspection
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if ($object instanceof User) {
            $data = [
                "id" => $object->getId(),
                "name" => $object->getName(),
                "email" => $object->getEmail(),
                "created_at" => $object->getCreatedAt()?->format('c'),
                "updated_at" => $object->getUpdatedAt()?->format('c'),
            ];
        } else {
            $data = [];
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        unset($data['created_at'], $data['updated_at']);

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof User;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === User::class;
    }

    /**
     * @inheritDoc
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
