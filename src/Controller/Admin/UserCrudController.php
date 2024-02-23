<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
       
    //         yield IdField::new('id');
    //         yield TextField::new('name', ' Nom');
    //         yield TextField::new('firstname', ' Prénom');
    //         yield TextField::new('email', ' Email');
    //         yield TextEditorField::new('description');
       
    // }
    
}
