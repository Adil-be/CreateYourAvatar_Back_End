<?php

namespace App\Serializer;

use App\Entity\NftCollection;

use App\Entity\NftImage;
use App\Entity\UserImage;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;


class CustomImageNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private $urlGenerator;
    private StorageInterface $storage;
    public function __construct(UrlGeneratorInterface $urlGenerator, StorageInterface $storage)
    {
        $this->urlGenerator = $urlGenerator;
        $this->storage = $storage;
    }

    private const ALREADY_CALLED = 'AppImageNormaliserAlreadyCalled';

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {

        return !isset($context[self::ALREADY_CALLED]) && ($data instanceof NftCollection || $data instanceof NftImage || $data instanceof UserImage);
    }
    public function normalize($object, $format = null, array $context = [])
    {

        $baseUrl = $this->urlGenerator->generate('root', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $url = $this->storage->resolveUri($object, 'file');
        $object->setPath($baseUrl . $url);

        $context[self::ALREADY_CALLED] = true;

        return $this->normalizer->normalize($object, $format, $context);

    }
}