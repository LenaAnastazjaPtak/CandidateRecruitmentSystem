<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control mb-3'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Email cannot be empty.',
                    ]),
                    new Assert\Email([
                        'message' => 'Please enter a valid email address.',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control mb-3'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Name cannot be empty.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Name must be at least {{ limit }} characters long.',
                        'max' => 50,
                        'maxMessage' => 'Name cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control mb-3'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Last name cannot be empty.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Last name must be at least {{ limit }} characters long.',
                        'max' => 50,
                        'maxMessage' => 'Last name cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('phoneNumber', NumberType::class, [
                'attr' => ['class' => 'form-control mb-3'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Phone number cannot be empty.',
                    ]),
                    new Assert\Length([
                        'min' => 9,
                        'minMessage' => 'Phone number must be at least {{ limit }} digits long.',
                        'max' => 15,
                        'maxMessage' => 'Phone number cannot be longer than {{ limit }} digits.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9]+$/',
                        'message' => 'Please enter a valid phone number.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'attr' => ['class' => 'form-check-input mb-3'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control mb-3'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
