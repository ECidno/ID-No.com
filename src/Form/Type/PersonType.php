<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Person;
use App\Form\Type\EntityHiddenType;
use Locale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\File;

/**
 * person form type
 */
class PersonType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nutzer', EntityHiddenType::class)
            ->add('sprache', ChoiceType::class, [
                'label' => new TranslatableMessage('person.sprache'),
                'choices' => [
                    'de' => 'de',
                    'en' => 'en',
                ],
                'choice_label' => function($choice, $key, $value) {
                    return new TranslatableMessage('person.sprache.'.$key);
                },
                'required' => true
            ])
            ->add('anrede', ChoiceType::class, [
                'label' => new TranslatableMessage('person.geschlecht'),
                'choices' => [
                    'm' => 'm',
                    'w' => 'w',
                    'x' => 'x',
                ],
                'choice_label' => function($choice, $key, $value) {
                    return new TranslatableMessage('person.geschlecht.'.$key);
                },
                'required' => true
            ])
            ->add('vorname', TextType::class, [
                'label' => new TranslatableMessage('person.vorname.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 100,
                ],
                'required' => true
            ])
            ->add('nachname', TextType::class, [
                'label' => new TranslatableMessage('person.nachname.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 100,
                ],
                'required' => true
            ])
            ->add('geburtsdatumTag', IntegerType::class, [
                'label' => new TranslatableMessage('person.geburtsdatumTag.lbl'),
                'attr' => [
                    'min' => 1,
                    'max' => 31
                ],
                'required' => false
            ])
            ->add('geburtsdatumMonat', IntegerType::class, [
                'label' => new TranslatableMessage('person.geburtsdatumMonat.lbl'),
                'attr' => [
                    'min' => 1,
                    'max' => 12
                ],
                'required' => false
            ])
            ->add('geburtsdatumJahr', IntegerType::class, [
                'label' => new TranslatableMessage('person.geburtsdatumJahr.lbl'),
                'attr' => [
                    'min' => 1900,
                    'max' => date('Y')
                ],
                'required' => false
            ])
            ->add('strasse', TextType::class, [
                'label' => new TranslatableMessage('person.strasse.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 100,
                ],
                'required' => false
            ])
            ->add('strasseShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])
            ->add('plz', TextType::class, [
                'label' => new TranslatableMessage('person.plz.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 13,
                ],
                'required' => false
            ])
            ->add('ort', TextType::class, [
                'label' => new TranslatableMessage('person.ort.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 100,
                ],
                'required' => false
            ])
            ->add('ortShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('zusatz', TextType::class, [
                'label' => new TranslatableMessage('person.zusatz.lbl'),
                'attr' => [
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('zusatzShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('land', ChoiceType::class, [
                'label' => new TranslatableMessage('person.land.lbl'),
                'choices' => ['please choose' => '',] + $this->getCountryChoices(),
                'choice_label' => function($choice, $key, $value) {
                    if (empty($choice)) {
                       return new TranslatableMessage('person.land.choose.lbl');
                    } else {
                        return $key;
                    }
                },
                'required' => false
            ])
            ->add('telefonLand', TextType::class, [
                'label' => new TranslatableMessage('person.telefonLand.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlenght' => 6
                ],
                'required' => false
            ])

            ->add('telefonVorwahl', TextType::class, [
                'label' => new TranslatableMessage('person.telefonVorwahl.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 10
                ],
                'required' => false
            ])
            ->add('telefon', TextType::class, [
                'label' => new TranslatableMessage('person.telefon.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 30
                ],
                'required' => false
            ])
            ->add('telefonShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('mobileLand', TextType::class, [
                'label' => new TranslatableMessage('person.mobileLand.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 6
                ],
                'required' => false
            ])
            ->add('mobileVorwahl', TextType::class, [
                'label' => new TranslatableMessage('person.mobileVorwahl.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 10
                ],
                'required' => false
            ])
            ->add('mobile', TextType::class, [
                'label' => new TranslatableMessage('person.mobile.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 30
                ],
                'required' => false
            ])
            ->add('mobileShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])
/*
            ->add('personImage', FileType::class, [
                'label' => new TranslatableMessage('person.image.lbl'),
                'row_attr' => ['class' => 'file mb-3'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                        ],
                    ])
                ],
                'attr' => [
                    'accept' => 'image/gif,image/jpeg,image/png',
                    'autocomplete' => 'off',
                ],
            ])
            */
            ->add('imageShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('blutgruppe', ChoiceType::class, [
                'label' => new TranslatableMessage('person.blutgruppe.lbl'),
                'choices' => [
                    'please choose' => '',
                    '0+' => '0+',
                    'A+' => 'A+',
                    'B+' => 'B+',
                    'AB+' => 'AB+',
                    '0-' => '0-',
                    'A-' => 'A-',
                    'B-' => 'B-',
                    'AB-' => 'AB-'
                ],
                'choice_label' => function($choice, $key, $value) {
                    if (empty($choice)) {
                        return new TranslatableMessage('person.blutgruppe.choose.lbl');
                    } else {
                        return $key;
                    }
                },
                'required' => false
            ])
            ->add('blutgruppeShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('erkrankungen', TextareaType::class, [
                'label' => new TranslatableMessage('person.erkrankungen.lbl'),
                'attr' => [
                    'class' => 'h-100',
                    'maxlength' => 16777215,
                    'autocomplete' => 'off',
                ],
                'required' => false,
            ])
            ->add('erkrankungenShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'],
                'required' => false
            ])

            ->add('medikamente', TextareaType::class, [
                'label' => new TranslatableMessage('person.medikamente.lbl'),
                'attr' => [
                    'class' => 'h-100',
                    'maxlength' => 65535,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('medikamenteShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'],
                'required' => false
            ])

            ->add('allergieen', TextareaType::class, [
                'label' => new TranslatableMessage('person.allergieen.lbl'),
                'attr' => [
                    'class' => 'h-100',
                    'maxlength' => 65535,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('allergieenShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'],
                'required' => false
            ])

            ->add('gewicht', TextType::class, [
                'label' => new TranslatableMessage('person.gewicht.lbl'),
                'attr' => [
                    'maxlenght' => 10,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('gewichtEinheit', ChoiceType::class, [
                'label' => new TranslatableMessage('person.gewichtEinheit.lbl'),
                'choices' => [
                    'kg' => 'kg',
                    'lbs' => 'lbs',
                ],
                'required' => true
            ])
            ->add('gewichtShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('groesse', TextType::class, [
                'label' => new TranslatableMessage('person.groesse.lbl'),
                'attr' => [
                    'maxlength' => 10,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('groesseEinheit', ChoiceType::class, [
                'label' => new TranslatableMessage('person.groesseEinheit.lbl'),
                'choices' => [
                    'cm' => 'cm',
                    'inch' => 'inch',
                ],
                'required' => true
            ])
            ->add('groesseShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('krankenversicherung', TextType::class, [
                'label' => new TranslatableMessage('person.krankenversicherung.lbl'),
                'attr' => [
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('krankenversicherungShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('versicherungsnummer', TextType::class, [
                'label' => new TranslatableMessage('person.versicherungsnummer.lbl'),
                'attr' => [
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('versicherungsnummerShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('zusatzversicherung', TextareaType::class, [
                'label' => new TranslatableMessage('person.zusatzversicherung.lbl'),
                'attr' => [
                    'class' => 'h-100',
                    'maxlength' => 65535,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('zusatzversicherungShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('organspender', CheckboxType::class, [
                'label' => new TranslatableMessage('person.organspender.lbl'),
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])
            ->add('organspenderShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('patientenverf', CheckboxType::class, [
                'label' => new TranslatableMessage('person.patientenverf.lbl'),
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])
            ->add('patientenverfShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])

            ->add('weitereangaben', TextareaType::class, [
                'label' => new TranslatableMessage('person.weitereangaben.lbl'),
                'attr' => [
                    'class' => 'h-100',
                    'maxlength' => 65535,
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('weitereangabenShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'label_attr' => [
                    'class' => 'd-none d-sm-block',
                ],
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ]);
    }


    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
            'csrf_token_id' => Person::class,
            'csrf_protection' => true,
        ]);
    }


    /**
     * get country choices
     *
     * @return array $countries
     */
    private function getCountryChoices(): array
    {
        $countries = Countries::getNames(Locale::getDefault());
        return array_flip($countries);
    }
}