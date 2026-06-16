<?php
namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\AddresseMapper;
use PostApi\modules\HR\domain\entities\Addresse;
use PostApi\shared\templates\DB_Trait;

class AddreseRepository {
     private AddresseMapper $addresseMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->addresseMapper = new AddresseMapper($this->postgre->pdo);
    }

    public function findOne(int $id)
    {
        return $this->addresseMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->addresseMapper->findAll();
    }

    public function create(Addresse $addresse)
    {
        $this->addresseMapper->create($addresse);
    }

    public function update(Addresse $addresse)
    {
        $this->addresseMapper->update($addresse);
    }

    public function delete(int $id)
    {
        $this->addresseMapper->delete($id);
    }
}