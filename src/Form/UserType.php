<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_username', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
              'type' => PasswordType::class,
              'first_options'=>['label'=>'Password'],
              'second_options'=>['label'=>'Repeat Password']
            ])
            ->add('fullName', TextType::class)
            ->add('termsAgreed', CheckboxType::class, [
              'mapped' => false,
              'constraints'=> new IsTrue(),
              'label'=>'I agree to the termes of servise'
            ])
            ->add('Register', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
        'data_class'=>User::class
      ]
        );
        //parent::configureOptions($resolver); //Todo: change the autogenerated stub...
    }
}
