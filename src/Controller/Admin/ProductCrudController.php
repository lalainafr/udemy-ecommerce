<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        // classe easyAdmin pour affiche le format des propriétés
        return [
            TextField::new('name'),
            // Générer le slug automatioquement
            SlugField::new('slug')->setTargetFieldName('name'),
            ImageField::new('illustration')
                // Définir le dossier pour l'upload des fichiers images)
                ->setBasePath('/uploads')
                // Définir le dossier pour enregistrer les fichiers images)
                ->setUploadDir('public/uploads')
                // Encoder le nom du fichier contenant l'image)
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            // Définir la monnaie à utiliser
            MoneyField::new('price')
                ->setCurrency('EUR'),
            // type relation
            AssociationField::new('category'),

        ];
    }
}
