<?php


namespace App\Form;


use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    private ProductService $productService;
    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', NumberType::class)
            ->add('updated', HiddenType::class, [
                'data' => new \DateTime(),
                'validation_groups' => false
            ]);
        $builder->get('price')
            ->addModelTransformer(new CallbackTransformer(
                function ($price) {
                    return $this->productService->transformPrice($price, true);
                },
                function ($price) {
                    return $this->productService->transformPrice($price, false);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => Product::class,
            'csrf_protection' => false,
        ]);
    }
}

