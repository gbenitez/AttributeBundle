<?php

namespace gbenitez\Bundle\AttributeBundle\Form\Type;

use gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;
use gbenitez\Bundle\AttributeBundle\Entity\Repository\AttributeRepository;
use gbenitez\Bundle\AttributeBundle\Model\AttributeTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeValueType extends AbstractType
{
    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;

    /**
     * AttributeValueType constructor.
     *
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(AttributeRepository $attributeRepository, $em)
    {
        $this->attributeRepository = $attributeRepository;
        $this->em = $em;
    }

    public function getName()
    {
        return 'attribute_value';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            /** @var AttributeValueInterface $attributeValue */
            $attributeValue = $event->getData();
            $form = $event->getForm();

            $attribute = $attributeValue->getAttribute();
            $type = strtolower($attribute->getType());

            $configuration = (array)$attribute->getConfiguration();
            $configuration += array(
                'label' => $options['label'],
            );

            switch ($type) {
                case AttributeTypes::TEXT:
                case AttributeTypes::ENTITY:
                    $form->add('value', $type, $configuration);
                    break;
                case AttributeTypes::CHECKBOX:
                case AttributeTypes::MONEY:
                case AttributeTypes::NUMBER:
                case AttributeTypes::DATE:
                    $form->add('value', $type, $configuration);
                    break;
                case AttributeTypes::CHOICE:
                    $form->add('value', $type, $configuration);
                    break;
                default:
                    throw new InvalidArgumentException("No se reconoce el tipo de attributo \"{$type}\"");
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /**@var AttributeValueInterface $attributeValue */
            $attributeValue = $event->getData();
            $form = $event->getForm();

            $attribute = $attributeValue->getAttribute();
            $type = strtolower($attribute->getType());
            $config = (array)$attribute->getConfiguration();

            if ($type == AttributeTypes::ENTITY) {
                if ($attributeValue->getValue()) {
                    $entity = $this->em->getRepository($config['class'])->find($attributeValue->getValue());

                    $attributeValue->setValue($entity);
                }

            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /**@var AttributeValueInterface $attributeValue */
            $attributeValue = $event->getData();
            $form = $event->getForm();

            $attribute = $attributeValue->getAttribute();
            $type = strtolower($attribute->getType());

            if ($type == AttributeTypes::ENTITY) {
                if ($attributeValue->getValue()) {
                    $attributeValue->setValue($attributeValue->getValue()->getId());
                }
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'data_class',
            'data',
            'attribute',
        ));

        $resolver->setAllowedTypes('attribute', Attribute::class);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['_attribute'] = $options['attribute'];
    }


}