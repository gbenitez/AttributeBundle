<?php

namespace AttributeBundle\Form\Type;

use AttributeBundle\Entity\Attribute;
use AttributeBundle\Entity\AttributeValueInterface;
use AttributeBundle\Entity\Repository\AttributeRepository;
use AttributeBundle\Model\AttributeTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
     * AttributeValueType constructor.
     *
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var AttributeValueInterface $attributeValue */
            $attributeValue = $event->getData();
            $form = $event->getForm();

            $attribute = $attributeValue->getAttribute();
            $type = strtolower($attribute->getType());

            switch ($type) {
                case AttributeTypes::TEXT:
                case AttributeTypes::ENTITY:
                    $form->add('value', $type, (array) $attribute->getConfiguration());
                    break;
                case AttributeTypes::CHECKBOX:
                case AttributeTypes::MONEY:
                case AttributeTypes::NUMBER:
                case AttributeTypes::DATE:
                    $form->add('value', $type, (array) $attribute->getConfiguration());
                    break;
                case AttributeTypes::CHOICE:
                    $form->add('value', $type, (array) $attribute->getConfiguration());
                    break;
                default:
                    throw new InvalidArgumentException("No se reconoce el tipo de attributo \"{$type}\"");
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'data_class',
            'data',
        ));
    }
}