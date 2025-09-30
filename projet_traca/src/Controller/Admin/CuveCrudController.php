<?php

namespace App\Controller\Admin;

use App\Entity\Cuve;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class CuveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cuve::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IntegerField::new('numero'),
            AssociationField::new('silo')
        ];
    }
}
