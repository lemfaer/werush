<?php

namespace App\Service;

use JetBrains\PhpStorm\Pure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ApiParamConverter implements ParamConverterInterface
{
    #[Required]
    public SerializerInterface $serializer;

    /**
     * @inheritDoc
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if (!$this->isContentTypeSupported($request)) {
            throw new BadRequestHttpException("Content-Type not supported");
        }

        $name = $configuration->getName();

        $entity = $this->getRequestData($request, $configuration);

        $request->attributes->set($name, $entity);

        return true;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function supports(ParamConverter $configuration): bool
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        if (null === $configuration->getConverter()) {
            return false;
        }

        return true;
    }

    /**
     * Check if request content type is supported
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function isContentTypeSupported(Request $request): bool
    {
        return match ($request->headers->get('content-type')) {
            'application/json' => true,
            default => false,
        };
    }

    /**
     * Return Entity object based on post data
     *
     * @param Request $request
     * @param ParamConverter $configuration
     *
     * @return object
     */
    protected function getRequestData(Request $request, ParamConverter $configuration): object
    {
        $class = $configuration->getClass();
        $data = $request->getContent();
        $type = $request->headers->get('content-type');

        /**
         * @noinspection DegradedSwitchInspection
         * @noinspection PhpSwitchCanBeReplacedWithMatchExpressionInspection
         */
        switch ($type) {
            case 'application/json':
                $entity = $this->serializer->deserialize($data, $class, 'json');
                break;
            default:
                throw new BadRequestException('Wrong request data type');
        }

        return $entity;
    }
}
