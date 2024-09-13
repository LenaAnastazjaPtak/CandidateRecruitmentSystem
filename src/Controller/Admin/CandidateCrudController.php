<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CandidateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();
        yield TextField::new('email');
        yield TextField::new('phoneNumber');
        yield TextField::new('name')
            ->setRequired(true);
        yield TextField::new('lastname')
            ->setRequired(true);
        yield AssociationField::new('user')
            ->setCrudController(UserCrudController::class)
            ->autocomplete();
        yield CollectionField::new('offers')
            ->allowAdd(false)
            ->allowDelete(false);
    }
}
