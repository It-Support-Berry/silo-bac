<?php

namespace App\Controller\Admin;

use App\Entity\MatiereBac;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MatiereBacCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MatiereBac::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
