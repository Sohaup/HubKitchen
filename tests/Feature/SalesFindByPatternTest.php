<?php

$mapperClasses = [
    'PostApi\\modules\\sales\\app\\DB\\models\\CategoryMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\CustomerMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\ProductMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\EmployeeMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\LeadMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\OfferMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\ReviewMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\PaymentMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\CartMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\CartItemMapper',
    'PostApi\\modules\\sales\\app\\DB\\models\\OrderMapper',
];

$repositoryClasses = [
    'PostApi\\modules\\sales\\app\\DB\\repositories\\CategoryRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\CustomerRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\ProductRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\EmployeeRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\LeadRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\OfferRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\ReviewRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\PaymentRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\CartRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\CartItemRepository',
    'PostApi\\modules\\sales\\app\\DB\\repositories\\OrderRepository',
];

test('sales mappers and repositories expose findBy filtering support', function () use ($mapperClasses, $repositoryClasses) {
    foreach ($mapperClasses as $mapperClass) {
        expect(method_exists($mapperClass, 'findBy'))->toBeTrue("{$mapperClass} should define findBy");
    }

    foreach ($repositoryClasses as $repositoryClass) {
        expect(method_exists($repositoryClass, 'findBy'))->toBeTrue("{$repositoryClass} should define findBy");
    }
});
