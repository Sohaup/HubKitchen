<?php

namespace PostApi\modules\inovice\domain\entityListeners;

use Override;
use PDO;
use PostApi\modules\inovice\domain\services\product\DeleteProductAction;
use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\helpers\adapters\stripe\StripeProduct;
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

class DeleteStockProductListener implements SplObserver
{
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
    }
    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof DeleteProductAction && $subject->getEvent() == "deleted") {
            $stock = $subject->getStock();
            $product = $this->getProductByName($stock->getName());            
            $productRepo = new ProductRepository();
            $stripe = new StripeProduct();
            $productRepo->delete($product['id']);
            $stripe->delete($product['stripe_id']);
        }
    }

    public function getProductByName(string $productName)
    {
        $queryTable = new QueryTable("sales.products");
        $queryColumn = new QueryColumns(["id" , "stripe_id"]);
        $queryCondition = new BasicCondition(new Condition("name", ConditionOperators::EQUAL, $productName));
        $limit = new Limit(1);
        $selectQuery = new Select(table: $queryTable->getQuery(), columns: $queryColumn->getColumns(), condition: $queryCondition->getCondition(), limit: $limit->getQuery());
        $product = $this->queryBuilder->select($selectQuery->getQuery(), $queryCondition->getValues(), PDO::FETCH_ASSOC);
        return $product[0];
    }
}
