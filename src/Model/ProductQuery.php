<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class ProductQuery
{
    const PAGINATION_NEXT = 1;
    const PAGINATION_PREVIOUS = -1;
    const DEFAULT_LIMIT = 3;
    const DEFAULT_ORDER_FIELD = 'id';
    const DEFAULT_ORDER_SORT = 'ASC';

    /**
     * @var int
     */
    private int $limit;

    /**
     * @var ?int
     * @Assert\NotBlank(message = "Page should not be null")
     */
    private ?int $page = null;

    /**
     * @var string
     */
    private string $orderField;

    /**
     * @var string
     */
    private string $orderSort;

    /**
     * @var ?int
     * @Assert\NotBlank(message = "Pagination should not be null")
     */
    private ?int $pagination = null;

    /**
     * @var string|null
     */
    private ?string $pivot = null;

    /**
     * @var int|null
     */
    private ?int $priceLt = null;
    /**
     * @var int|null
     */
    private ?int $priceGt = null;
    /**
     * @var string|null
     */
    private ?string $name = null;

    public function __construct()
    {
        $this->setOrderSort(self::DEFAULT_ORDER_SORT);
        $this->setOrderField(self::DEFAULT_ORDER_FIELD);
        $this->setLimit(self::DEFAULT_LIMIT);
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getOrderField(): ?string
    {
        return $this->orderField;
    }

    /**
     * @param string $orderField
     */
    public function setOrderField(string $orderField): void
    {
        $this->orderField = $orderField;
    }

    /**
     * @return string
     */
    public function getOrderSort(): string
    {
        return $this->orderSort;
    }

    /**
     * @param string $orderSort
     */
    public function setOrderSort(string $orderSort): void
    {
        $this->orderSort = $orderSort;
    }

    /**
     * @return int
     */
    public function getPagination(): ?int
    {
        return $this->pagination;
    }

    /**
     * @param int $pagination
     */
    public function setPagination(int $pagination): void
    {
        $this->pagination = $pagination;
    }

    /**
     * @return ?string
     */
    public function getPivot(): ?string
    {
        return $this->pivot;
    }

    /**
     * @param ?string $pivot
     */
    public function setPivot(?string $pivot): void
    {
        $this->pivot = $pivot;
    }

    /**
     * @return ?int
     */
    public function getPriceGt(): ?int
    {
        return $this->priceGt;
    }

    /**
     * @param int $priceGt
     */
    public function setPriceGt(int $priceGt): void
    {
        $this->priceGt = $priceGt;
    }

    /**
     * @return int
     */
    public function getPriceLt(): ?int
    {
        return $this->priceLt;
    }

    /**
     * @param int $priceLt
     */
    public function setPriceLt(int $priceLt): void
    {
        $this->priceLt = $priceLt;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getOffset(): int {
        return $this->getPage() * $this->getLimit();
    }
}
