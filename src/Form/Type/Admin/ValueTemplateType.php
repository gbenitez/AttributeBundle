<?php

namespace Gbenitez\AttributeBundle\Form\Type\Admin;

use Gbenitez\AttributeBundle\Entity\Attribute;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ValueTemplateType extends AbstractType implements EventSubscriberInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getParent()
    {
        return 'textarea';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function onSubmit(FormEvent $event)
    {
        $data = $event->getData();

        $this->validateTwigSyntax($event, $data);
        $this->addDefaultValueTemplate($event, $data);
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     * @param $data
     */
    private function validateTwigSyntax(FormEvent $event, $data)
    {
        if (null === $data) {
            return;
        }

        $realLoader = $this->twig->getLoader();

        try {
            $temporaryLoader = new \Twig_Loader_Array(array('file' => $data));
            $this->twig->setLoader($temporaryLoader);
            $nodeTree = $this->twig->parse($this->twig->tokenize($data, 'file'));
            $this->twig->compile($nodeTree);
        } catch (\Twig_Error $e) {
            $event->getForm()->addError(new FormError(
                'Twig Sintax Error: '.$e->getMessage()
            ));
        }

        $this->twig->setLoader($realLoader);
    }

    /**
     * @param FormEvent $event
     * @param $data
     */
    private function addDefaultValueTemplate(FormEvent $event, $data)
    {
        $form = $event->getForm();
        $object = $form->getParent()->getNormData();

        if (($form->isSubmitted() and !$form->isValid()) || null != $data || !($object instanceof Attribute)) {
            return;
        }

        $dateTypes = [
            'date',
            'datetime',
            DateType::class,
            DateTimeType::class,
        ];

        if (in_array($object->getType(), $dateTypes)) {
            $event->setData('{{ value ? value|date }}');
        }
    }
}
