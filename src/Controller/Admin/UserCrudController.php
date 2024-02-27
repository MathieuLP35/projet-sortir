<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        
        yield TextField::new('name', 'Nom :');
        yield TextField::new('firstname', 'Prénom :');
        yield TextField::new('username', 'Pseudo :');
        yield EmailField::new('email', 'Email :');
        yield NumberField::new('phone', 'Télephone :');
        yield BooleanField::new('isActive', 'Compte actif :');
        yield ImageField::new('profilePicture', 'Avatar')
        ->setBasePath('uploads/user_profile_pictures')
            ->setUploadDir('public/uploads/user_profile_pictures')
            ;
       
    }
    

    
}
