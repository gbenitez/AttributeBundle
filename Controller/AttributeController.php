<?php

namespace gbenitez\Bundle\AttributeBundle\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use gbenitez\Bundle\AttributeBundle\Entity\Region;
use gbenitez\Bundle\AttributeBundle\Form\Type\AttributeAdminType;
use gbenitez\Bundle\AttributeBundle\Form\Type\AttributeFilterType;
use gbenitez\Bundle\AttributeBundle\Form\Type\YamlType;
use gbenitez\Bundle\AttributeBundle\Model\AttributeTypes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;


class AttributeController extends Controller
{
    /**
     * @Route("/list", name="attributes_list")
     */
    public function indexAction(Request $request)
    {

        $attributes = $this->get('attribute.repository')->getQueryAll();

        return $this->render('@Attribute/admin/list.html.twig', array(
            'attributes' => $attributes,
        ));
    }

    /**
     * @Route("/create", name="attribute_create")
     *
     */
    public function createAction(Request $request)
    {
        $form = $this->createProfileForm($attribute = new Attribute());
        $form->handleRequest($request);


        if ($form->isSubmitted() and $form->isValid()) {
            $attribute->setCreatedAt(new \Datetime('NOW'));
            $this->get('attribute.manager')->saveAttribute($attribute);

            $this->addFlash('success', 'Attribute Created');

            return $this->redirectToRoute('attributes_list');
        }

        return $this->render('@Attribute/admin/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="attribute_edit")
     *
     */
    public function editAction(Request $request, Attribute $attribute)
    {
        $form = $this->createProfileForm($attribute);
        $form->handleRequest($request);


        if ($form->isSubmitted() and $form->isValid()) {

            $this->get('attribute.manager')->saveAttribute($attribute);

            $this->addFlash('success', 'Attribute Updated');

            return $this->redirectToRoute('attributes_list');
        }

        return $this->render('@Attribute/admin/edit.html.twig', array(
            'form' => $form->createView(),
            'attribute' => $attribute,
        ));
    }

    protected function createProfileForm(Attribute $attribute)
    {

        $builder = $this->createFormBuilder($attribute)
            ->add('name', TextType::class, array(
                'required' => false,
                'label' => 'Name',
            ))
            ->add('presentation', TextType::class, array(
                'required' => false,
                'label' => 'Presentation',
            ))
            ->add('type', ChoiceType::class, array(
                'label' => 'Type',
                'choices' => AttributeTypes::getChoices(),
                'placeholder' => false
            ))
            ->add('targetEntity', null, array(
                'label' => 'Target Entity',
            ))
            ->add('active', ChoiceType::class, array(
                'choices' => array(
                    1 => 'Yes',
                    0 => 'No'
                ),
                'multiple' => false,
                'expanded' => true

            ))
            ->add('configuration', YamlType::class, array(
                'required' => false,
            ))
            ->add('region', EntityType::class, array(
                'label' => 'Region:',
                'class' => Region::class,
                'choice_label' => 'name',
                'placeholder' => 'Seleccione',
                'required' => true,
                'invalid_message' => 'Debe seleccionar un fabricante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.active = 1');
                }
            ));

        return $builder->getForm();
    }

}
