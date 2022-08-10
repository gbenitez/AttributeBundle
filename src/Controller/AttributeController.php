<?php

namespace gbenitez\Bundle\AttributeBundle\Controller;

use Doctrine\ORM\EntityRepository;
use gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use gbenitez\Bundle\AttributeBundle\Entity\Region;
use gbenitez\Bundle\AttributeBundle\Entity\Repository\AttributeRepository;
use gbenitez\Bundle\AttributeBundle\Form\Type\YamlType;
use gbenitez\Bundle\AttributeBundle\Model\AttributeTypes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Response;

class AttributeController extends AbstractController
{
    /** @var AttributeRepository  */
    public AttributeRepository $attributeRepository;

    /**
     * CaptchaController constructor.
     *
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }


    public function listAction(): Response
    {
        $attributes = $this->attributeRepository->getQueryAll();

        return $this->render('@Attribute/admin/list.html.twig', array(
            'attributes' => $attributes,
        ));
    }


    /*public function createAction(Request $request)
    {
        $form = $this->createProfileForm($attribute = new Attribute());
        $form->handleRequest($request);


        if ($form->isSubmitted() and $form->isValid()) {
            $attribute->setCreatedAt(new \Datetime('NOW'));
            $this->container->get('attribute.manager')->saveAttribute($attribute);

            $this->addFlash('success', 'Attribute Created');

            return $this->redirectToRoute('attributes_list');
        }

        return $this->render('@Attribute/admin/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }*/


    /*public function editAction(Request $request, Attribute $attribute)
    {
        $form = $this->createProfileForm($attribute);
        $form->handleRequest($request);


        if ($form->isSubmitted() and $form->isValid()) {

            $this->container->get('attribute.manager')->saveAttribute($attribute);

            $this->addFlash('success', 'Attribute Updated');

            return $this->redirectToRoute('attributes_list');
        }

        return $this->render('@Attribute/admin/edit.html.twig', array(
            'form' => $form->createView(),
            'attribute' => $attribute,
        ));
    }*/

    /*protected function createProfileForm(Attribute $attribute)
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
    }*/

}
