<?php


namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateUserFormType
 * @package App\Form
 */
class CreateUserFormType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
               'data_class' => User::class
            ]);
    }

    /**
     * @param FormBuilderInterface  $builder
     * @param array                 $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class, [
                'required' => true
            ])
            ->add('lastName', TextType::class, [
                'required' => true
            ])
            ->add('firstName', TextType::class, [
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'required' => true
            ])
            ->add('fonction', TextType::class, [
                'required' => true
            ])
            ->add('rgpd', CheckboxType::class, [
                'required' => true
            ])
            ->add('telephone',TextType::class, [
                'required' => true
            ]);
    }
}