<?php


namespace App\Service;


use App\Entity\Product;
use App\Model\ProductQuery;
use App\Exception\EntityNotFountException;
use App\Exception\InvalidFormException;
use App\Exception\InvalidQueryException;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    private EntityManagerInterface $em;
    private ValidatorInterface $validator;
    private FormFactoryInterface $formFactory;
    private ProductRepository $productRepository;
    private SessionInterface $session;
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        FormFactoryInterface $formFactory,
        ProductRepository $productRepository,
        SessionInterface $session
    )
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->formFactory = $formFactory;
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    /**
     * @param array $data
     * @return Product
     */
    public function addProduct(array $data): Product {
        return $this->saveProduct($data);
    }

    /**
     * @param array $data
     * @return Product
     */
    public function updateProduct(array $data): Product
    {
        $product = $this->findProduct($data['id']);
        unset($data['id']);
        return $this->saveProduct($data, $product);
    }

    /**
     * @param int $id
     */
    public function removeProduct(int $id): void {
        $product = $this->findProduct($id);
        $this->productRepository->delete($product);
        $this->em->persist($product);
        $this->em->flush();
    }

    /**
     * @param array $options
     * @param array|null $lastId
     * @return Product[]
     */
    public function getProducts(array $options, ?array $lastId = null): array
    {

        $serializer = new Serializer([new ObjectNormalizer()]);

        try {
            /** @var ProductQuery $objOptions */
            $objOptions = $serializer->denormalize($options, ProductQuery::class);
        } catch (ExceptionInterface $e) {
            throw new InvalidQueryException(InvalidQueryException::ERROR_MESSAGE, 400, $e);
        }

        $err = $this->validator->validate($objOptions);
        if($err->count()>0) {
            throw new InvalidQueryException($err->get(0)->getMessage());
        }

        /** Wiem że ta ifka wygląda okrutnie, nie miałem pomysłu jak to inaczej rozwiązać :( */
        if(
            $objOptions->getPagination() === ProductQuery::PAGINATION_NEXT && $objOptions->getOrderSort() === 'ASC' ||
            $objOptions->getPagination() === ProductQuery::PAGINATION_PREVIOUS && $objOptions->getOrderSort() === 'DESC'
        ) {
            $objOptions->setPivot($this->session->get('lastMaxId'));
            $products = $this->productRepository->getListWhere($objOptions, false);
        } else if(
            $objOptions->getPagination() === ProductQuery::PAGINATION_NEXT && $objOptions->getOrderSort() === 'DESC' ||
            $objOptions->getPagination() === ProductQuery::PAGINATION_PREVIOUS && $objOptions->getOrderSort() === 'ASC'
        ) {
            $objOptions->setPivot($this->session->get('lastMinId'));
            $products = $this->productRepository->getListWhere($objOptions, true);
        } else {
            $products = $this->productRepository->getListOffset($objOptions);
        }
        if(empty($products['data']) && $products['rows'] > 0)
            throw new InvalidQueryException("Invalid Pagination");

        $this->saveExtremeResults($products['data'], $objOptions->getOrderField());
        return $products;
    }

    /**
     * @param int $id
     * @return Product
     */
    public function findProduct(int $id): Product {
        /** @var Product $product */
        $product = $this->productRepository->findOneBy([
            'id' => $id,
            'isDeleted' => false
        ]);

        if(!$product) {
            throw new EntityNotFountException();
        }

        return $product;
    }

    private function saveExtremeResults(array $products,string $field) {
        if(!empty($products)) {
            $this->session->set('lastMaxId', max(array_column($products, $field)));
            $this->session->set('lastMinId', min(array_column($products, $field)));
        }
    }

    private function saveProduct(array $data, ?Product $product = null): Product {
        $form = $this->formFactory->create(ProductType::class, $product ?? new Product());

        $form->submit($data);

        if(!$form->isValid()) {
            throw new InvalidFormException($form);
        }

        /** @var Product $product */
        $product = $form->getNormData();

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }
}
