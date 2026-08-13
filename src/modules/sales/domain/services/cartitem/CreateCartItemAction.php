<?php

namespace PostApi\modules\sales\domain\services\cartitem;

use Exception;
use Override;
use PDO;
use PostApi\modules\sales\app\DB\repositories\CartItemRepository;
use PostApi\modules\sales\domain\entities\CartItem;
use PostApi\modules\sales\domain\entitylisteners\CreateCartItemListener;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;
use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\ComplexCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionTypes;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\ConditionsCollection;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Limit\Limit;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Select;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;
use PostApi\shared\templates\DB_Trait;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class CreateCartItemAction implements SplSubject
{
    use DB_Trait;
    private SplObjectStorage $observers;
    private string $createCartItemEvent =  "";
    private CartItem $cartItem;
    public function __construct()
    {
        $this->initialize();
        $this->observers = new SplObjectStorage();
        $this->attach(new CreateCartItemListener());
    }
    public function execute(CartItem $cartItem)
    {
        $cartItemRepository = new CartItemRepository();
        try {
            $cartItemRepository->create($cartItem);
        } catch (Exception $error) {
            $cartItemId = getCartItemId($cartItem->getCart()->getId(), $cartItem->getProduct()->getId(), $this->queryBuilder);
            $cartItem->setId($cartItemId);
            $oldCartItem = $cartItemRepository->findOne($cartItemId);
            $qunatity = bcadd($oldCartItem->getQuantity(), $cartItem->getQuantity(), 1);
            $cartItem->setQuantity($qunatity);
            $cartItem->setAddedAt($oldCartItem->getAddedAt());
            $cartItemRepository->update($cartItem);
        }
        $this->createCartItemEvent = "created";
        $this->cartItem = $cartItem;
        $this->notify();
        return $cartItem;
    }

    #[Override]
    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    #[Override]
    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    #[Override]
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getEvent(): string
    {
        return $this->createCartItemEvent;
    }

    public function getCartItem(): CartItem
    {
        return $this->cartItem;
    }
}

function getCartItemId(string $cart_id, string $product_id, QueryBuilder $queryBuilder)
{

    $queryTable = new QueryTable("sales.cart_items");
    $queryColumns = new QueryColumns(['id']);
    $condition1 = new Condition("cart_id", ConditionOperators::EQUAL, $cart_id);
    $condition2 = new Condition("product_id", ConditionOperators::EQUAL, $product_id, ConditionTypes::AND);
    $conditions = new ConditionsCollection([new BasicCondition($condition1), new BasicCondition($condition2)]);
    $queryCondition = new ComplexCondition($conditions);
    $limit = new Limit(1);
    $selectStmt = new Select(table: $queryTable->getQuery(), columns: $queryColumns->getColumns(), condition: $queryCondition->getCondition(), limit: $limit->getQuery());
    $cart = $queryBuilder->select($selectStmt->getQuery(), $queryCondition->getValues(), PDO::FETCH_ASSOC);
    return $cart[0]['id'];
}
