<?php

namespace App\Controller\Admin;

use App\Entity\Codejde;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CodejdeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Codejde::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('code'),
            AssociationField::new('matiere')
        ];
    }
}
