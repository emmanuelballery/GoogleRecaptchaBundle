# GoogleRecaptchaBundle

Google reCAPTCHA form type for Symfony.

https://www.google.com/recaptcha/intro/invisible.html

## Configuration

```yaml
google_recaptcha:

    site_key: ~
    secret_key: ~

    # following configuration is optional

    api_endpoint: 'https://www.google.com/recaptcha/api/siteverify'
    form_field_name: 'g-recaptcha-response'
```

## Usage

In your Symfony form type:

```php
use GoogleRecaptchaBundle\Form\Type\GoogleRecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('recaptcha', GoogleRecaptchaType::class);
    }
}
```

## Form theme

Use the `google_recaptcha` identifier to theme your form field.

```twig
{% block google_recaptcha_row %}
    <div class="hide">{{ block('form_label') }}</div>
    {{ block('form_widget') }}
    {{ block('form_errors') }}
{% endblock %}
```
