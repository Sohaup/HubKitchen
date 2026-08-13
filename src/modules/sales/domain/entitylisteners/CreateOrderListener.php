<?php

namespace PostApi\modules\sales\domain\entitylisteners;

use Override;
use PDO;
use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\domain\services\order\CreateOrderAction;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;
use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Limit\Limit;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Select;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;
use PostApi\shared\templates\DB_Trait;
use SplObserver;
use SplSubject;

class CreateOrderListener implements SplObserver
{
    use DB_Trait;
    public function __construct()
    {
       $this->initialize();
    }
    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof CreateOrderAction && $subject->getEvent() == "created") {
            $stockRepo = new ProductRepository();
            $order = $subject->getOrder();
            $cart = $order->getCart();            
            $cartItems = $cart->getCartItems()->cartItems;         
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->getProduct();
                $sockId = getStocckProductByName($this->queryBuilder , $product->getName());
                $stock = $stockRepo->findOne($sockId);
                $quantity = $cartItem->getQuantity();
                $currentQuantity = $stock->getQuantity();
                $RemainQuantity = bcsub($currentQuantity, $quantity , 1);              
                $stock->setQuantity($RemainQuantity);  
                $stockRepo->update($stock);              
            }

        }
    }
}

function getStocckProductByName(QueryBuilder $queryBuilder , string $productName) {
    $queryTable = new QueryTable("inovice.products");
    $queryColumn = new QueryColumns(["id"]);
    $condition1 = new Condition("name" , ConditionOperators::EQUAL , $productName);
    $queryCondition = new BasicCondition($condition1);
    $limit = new Limit(1);
    $selectQuery = new Select(table:$queryTable->getQuery() , columns:$queryColumn->getColumns() , condition:$queryCondition->getCondition() , limit:$limit->getQuery());
    $stock = $queryBuilder->select($selectQuery->getQuery() , $queryCondition->getValues() , PDO::FETCH_ASSOC);
    return $stock[0]['id'];
}
