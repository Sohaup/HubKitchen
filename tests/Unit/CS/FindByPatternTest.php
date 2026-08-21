<?php

use PostApi\modules\CS\app\DB\models\ActionMapper;
use PostApi\modules\CS\app\DB\models\CaseInterActionMapper;
use PostApi\modules\CS\app\DB\models\CustomerLogMapper;
use PostApi\modules\CS\app\DB\models\CustomerMapper;
use PostApi\modules\CS\app\DB\models\EmployeeMapper;
use PostApi\modules\CS\app\DB\models\RoleMapper;
use PostApi\modules\CS\app\DB\models\StatusMapper;
use PostApi\modules\CS\app\DB\models\TicketMapper;
use PostApi\modules\CS\app\DB\repositories\ActionRepository;
use PostApi\modules\CS\app\DB\repositories\CaseInterActionRepository;
use PostApi\modules\CS\app\DB\repositories\CustomerLogRepository;
use PostApi\modules\CS\app\DB\repositories\CustomerRepository;
use PostApi\modules\CS\app\DB\repositories\EmployeeRepository;
use PostApi\modules\CS\app\DB\repositories\RoleRepository;
use PostApi\modules\CS\app\DB\repositories\StatusRepository;
use PostApi\modules\CS\app\DB\repositories\TicketRepository;

test('cs mappers expose findBy method', function () {
    expect(method_exists(ActionMapper::class, 'findBy'))->toBeTrue()
        ->and(method_exists(CustomerMapper::class, 'findBy'))->toBeTrue()
        ->and(method_exists(CustomerLogMapper::class, 'findBy'))->toBeTrue()
        ->and(method_exists(EmployeeMapper::class, 'findBy'))->toBeTrue()
        ->and(method_exists(RoleMapper::class, 'findBy'))->toBeTrue()
        ->and(method_exists(StatusMapper::class, 'findBy'))->toBeTrue()
        ->and(method_exists(TicketMapper::class, 'findBy'))->toBeTrue()
        ->and(method_exists(CaseInterActionMapper::class, 'findBy'))->toBeTrue();
});

test('cs repositories expose findBy method', function () {
    expect(method_exists(ActionRepository::class, 'findBy'))->toBeTrue()
        ->and(method_exists(CustomerRepository::class, 'findBy'))->toBeTrue()
        ->and(method_exists(CustomerLogRepository::class, 'findBy'))->toBeTrue()
        ->and(method_exists(EmployeeRepository::class, 'findBy'))->toBeTrue()
        ->and(method_exists(RoleRepository::class, 'findBy'))->toBeTrue()
        ->and(method_exists(StatusRepository::class, 'findBy'))->toBeTrue()
        ->and(method_exists(TicketRepository::class, 'findBy'))->toBeTrue()
        ->and(method_exists(CaseInterActionRepository::class, 'findBy'))->toBeTrue();
});
