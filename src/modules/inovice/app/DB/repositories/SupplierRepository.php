<?php
namespace PostApi\modules\inovice\app\DB\repositories;

use PostApi\modules\inovice\app\DB\models\SupplierMapper;
use PostApi\modules\inovice\domain\entities\Supplier;
use PostApi\shared\templates\DB_Trait;

class SupplierRepository
{
    use DB_Trait;
    private SupplierMapper $supplierMapper;
    public function __construct()
    {
        $this->initialize();
        $this->supplierMapper = new SupplierMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->supplierMapper->findOne($id);
    }

    public function findAll() {
        return $this->supplierMapper->findAll();
    }

    public function create(Supplier $supplier) {
        $this->supplierMapper->insert($supplier);
    }

    public function update(Supplier $supplier) {
        $this->supplierMapper->update($supplier);
    }

    public function delete(string $id) {
        $this->supplierMapper->delete($id);
    }
}
