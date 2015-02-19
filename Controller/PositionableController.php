<?php

namespace FSi\Bundle\AdminPositionableBundle\Controller;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminPositionableBundle\Model\PositionableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PositionableController
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement $element
     * @param $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \RuntimeException
     * @return RedirectResponse
     */
    public function increasePositionAction(CRUDElement $element, $id, Request $request)
    {
        $entity = $this->getEntity($element, $id);
        $entity->increasePosition();

        $this->persistAndFlush($element, $entity);

        return $this->getRedirectResponse($element, $request);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement $element
     * @param $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \RuntimeException
     * @return RedirectResponse
     */
    public function decreasePositionAction(CRUDElement $element, $id, Request $request)
    {
        $entity = $this->getEntity($element, $id);
        $entity->decreasePosition();

        $this->persistAndFlush($element, $entity);

        return $this->getRedirectResponse($element, $request);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement $element
     * @param int $id
     * @throws \RuntimeException
     * @return PositionableInterface
     */
    private function getEntity(CRUDElement $element, $id)
    {
        $entity = $element->getDataIndexer()->getData($id);

        if (!$entity instanceof PositionableInterface) {
            throw new \RuntimeException(sprintf('Entity with id %s does not implement PositionableInterface', $id));
        }

        return $entity;
    }

    /**
     * @param CRUDElement $element
     * @param Request $request
     * @return RedirectResponse
     */
    private function getRedirectResponse(CRUDElement $element, Request $request)
    {
        if ($request->query->get('redirect_uri')) {
            $uri = $request->query->get('redirect_uri');
        } else {
            $uri = $this->router->generate($element->getRoute(), $element->getRouteParameters());
        }

        return new RedirectResponse($uri);
    }

    /**
     * @param CRUDElement $element
     * @param $entity
     */
    private function persistAndFlush(CRUDElement $element, $entity)
    {
        $om = $element->getObjectManager();
        $om->persist($entity);
        $om->flush();
    }
}
