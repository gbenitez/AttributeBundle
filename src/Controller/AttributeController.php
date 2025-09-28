<?php

namespace Gbenitez\AttributeBundle\Controller;

use Gbenitez\AttributeBundle\Entity\Repository\AttributeRepository;
use Gbenitez\AttributeBundle\Entity\Attribute;
use Gbenitez\AttributeBundle\Form\Type\AttributeAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class AttributeController extends AbstractController
{
    public function __construct(
        private readonly AttributeRepository $attributeRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }


    #[Route('/admin/attributes/list', name: 'gbenitez_attribute_list', methods: ['GET'])]
    public function listAction(): Response
    {
        $attributes = $this->attributeRepository->getQueryAll();

        return $this->render('@GbenitezAttribute/admin/list.html.twig', [
            'attributes' => $attributes,
        ]);
    }


    #[Route('/admin/attributes/new', name: 'gbenitez_attribute_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request): Response
    {
        $attribute = new Attribute();
        
        // Crear un formulario simple para demostración
        $form = $this->createFormBuilder($attribute)
            ->add('name', null, [
                'label' => 'Nombre',
                'required' => true,
            ])
            ->add('presentation', null, [
                'label' => 'Presentación',
                'required' => true,
            ])
            ->add('type', null, [
                'label' => 'Tipo',
                'required' => true,
            ])
            ->add('active', null, [
                'label' => 'Activo',
                'required' => false,
            ])
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($attribute);
            $this->entityManager->flush();
            
            $this->addFlash('success', 'Attribute creado exitosamente');
            
            return $this->redirectToRoute('gbenitez_attribute_list');
        }
        
        return $this->render('@GbenitezAttribute/admin/new.html.twig', [
            'form' => $form->createView(),
            'attribute' => $attribute,
        ]);
    }

    #[Route('/admin/attributes/{id}/edit', name: 'gbenitez_attribute_edit', methods: ['GET', 'POST'])]
    public function editAction(Request $request, int $id): Response
    {
        $attribute = $this->attributeRepository->find($id);
        
        if (!$attribute) {
            throw $this->createNotFoundException('Attribute no encontrado');
        }
        
        // Crear formulario de edición
        $form = $this->createFormBuilder($attribute)
            ->add('name', null, [
                'label' => 'Nombre',
                'required' => true,
            ])
            ->add('presentation', null, [
                'label' => 'Presentación',
                'required' => true,
            ])
            ->add('type', null, [
                'label' => 'Tipo',
                'required' => true,
            ])
            ->add('active', null, [
                'label' => 'Activo',
                'required' => false,
            ])
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            
            $this->addFlash('success', 'Attribute actualizado exitosamente');
            
            return $this->redirectToRoute('gbenitez_attribute_list');
        }
        
        return $this->render('@GbenitezAttribute/admin/edit.html.twig', [
            'form' => $form->createView(),
            'attribute' => $attribute,
        ]);
    }

    #[Route('/admin/attributes/{id}/delete', name: 'gbenitez_attribute_delete', methods: ['DELETE', 'POST'])]
    public function deleteAction(Request $request, int $id): Response
    {
        $attribute = $this->attributeRepository->find($id);
        
        if (!$attribute) {
            throw $this->createNotFoundException('Attribute no encontrado');
        }
        
        // Verificar token CSRF para seguridad
        if ($this->isCsrfTokenValid('delete'.$attribute->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($attribute);
            $this->entityManager->flush();
            
            $this->addFlash('success', 'Attribute eliminado exitosamente');
        } else {
            $this->addFlash('error', 'Token de seguridad inválido');
        }
        
        return $this->redirectToRoute('gbenitez_attribute_list');
    }

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
