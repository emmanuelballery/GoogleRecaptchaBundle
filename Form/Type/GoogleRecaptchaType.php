<?php

namespace GoogleRecaptchaBundle\Form\Type;

use GoogleRecaptchaBundle\Validator\GoogleRecaptchaValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class GoogleRecaptchaType
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class GoogleRecaptchaType extends AbstractType
{
    /**
     * @var string
     */
    private $siteKey;

    /**
     * @var string
     */
    private $formFieldName;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var GoogleRecaptchaValidator
     */
    private $validator;

    /**
     * @var null|TranslatorInterface
     */
    private $translator;

    /**
     * @param string                   $siteKey       Site key
     * @param string                   $formFieldName Form field name
     * @param RequestStack             $requestStack  Request stack
     * @param GoogleRecaptchaValidator $validator     Validator
     * @param TranslatorInterface      $translator    Translator
     */
    public function __construct($siteKey, $formFieldName, RequestStack $requestStack, GoogleRecaptchaValidator $validator, TranslatorInterface $translator = null)
    {
        $this->siteKey = $siteKey;
        $this->formFieldName = $formFieldName;
        $this->requestStack = $requestStack;
        $this->validator = $validator;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $request = $this->requestStack->getCurrentRequest();
            $method = $event->getForm()->getRoot()->getConfig()->getMethod();

            if ($request->getMethod() === $method) {
                $response = $request->request->get($this->formFieldName);

                if (!$this->validator->validate($response)) {
                    $message = $this->translator ? $this->translator->trans('google_recaptcha.invalid_recaptcha') : 'Invalid reCATPCHA';
                    $event->getForm()->addError(new FormError($message));
                }

                $request->request->remove($this->formFieldName);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'google_recaptcha.form_type_field',
            'attr' => [
                'class' => 'g-recaptcha',
                'data-sitekey' => $this->siteKey,
            ],
            'mapped' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'google_recaptcha';
    }
}
