<?php

namespace gbenitez\Bundle\AttributeBundle\Controller;

use gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use gbenitez\Bundle\AttributeBundle\Form\Type\AttributeAdminType;
use gbenitez\Bundle\AttributeBundle\Form\Type\AttributeFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class AttributeController extends Controller
{
    /**
     * @Route("/list", name="attributes_list")
     */
    public function indexAction(Request $request)
    {
        $formFilter = $this->createForm(new AttributeFilterType())->handleRequest($request);

        $attributes = $this->get('attribute.repository')->getQueryAll($formFilter->getData());

        return $this->render('AttributeBundle:admin:list.html.twig', array(
            'attributes' => $attributes,
            'form_filter' => $formFilter->createView(),
        ));
    }
    /**
     * @Route("/create", name="attribute_create")
     *
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new AttributeAdminType(), $attribute = new Attribute())
            ->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $attribute->setCreatedAt(new \Datetime('NOW'));
            $this->get('attribute.manager')->saveAttribute($attribute);

            $this->addFlash('success', 'Attribute Created');

            return $this->redirectToRoute('attributes_list');
        }

        return $this->render('AttributeBundle:admin:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/edit/{id}", name="attribute_edit")
     *
     */
    public function editAction(Request $request, Attribute $attribute)
    {
        $form = $this->createForm(new AttributeAdminType(), $attribute)
            ->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $this->get('attribute.manager')->saveAttribute($attribute);

            $this->addFlash('success', 'Attribute Updated');

            return $this->redirectToRoute('attributes_list');
        }

        return $this->render('AttributeBundle:admin:edit.html.twig', array(
            'form' => $form->createView(),
            'attribute' => $attribute,
        ));
    }
}
