<?php

namespace PostApi\modules\CS\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Action;
use PostApi\modules\CS\domain\entities\CaseInterAction;
use PostApi\modules\CS\domain\entities\Customer;
use PostApi\modules\CS\domain\entities\Employee;
use PostApi\modules\CS\domain\entities\Role;
use PostApi\modules\CS\domain\entities\Status;
use PostApi\modules\CS\domain\entities\Ticket;

class CaseInterActionMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db)
    {
        $this->db = $db;
    }

    public function findOne(string $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                return $this->identityMap[$id];
            }
            $stmt = $this->db->prepare(
                "SELECT * FROM CS.case_inter_action_view WHERE id = ?"
            );
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $entity = new CaseInterAction();
                $entity->setId($row['id']);

                $customer = new Customer();
                $customer->setId($row['customer_id']);
                $customer->setCountry($row['customer_country']);
                $customer->setUserId($row['customer_user_id']);
                $entity->setCustomer($customer);

                $employee = new Employee();
                $employee->setId($row['employee_id']);
                $employee->setEmployeeId($row['hr_employee_id']);
                $employee->setUserId($row['employee_user_id']);

                $role = new Role();
                $role->setId($row['employee_role_id']);
                $role->setName($row['role_name']);
                $employee->setRole($role);
                $entity->setEmployee($employee);

                $action = new Action();
                $action->setId($row['action_id']);
                $action->setAction($row['action']);
                $action->setTakedAt($row['action_taked_at']);
                $entity->setAction($action);

                $status = new Status();
                $status->setId($row['status_id']);
                $status->setStatus($row['status']);
                $status->setIssuedAt($row['status_issued_at']);
                $entity->setStatus($status);

                $ticket = new Ticket();
                $ticket->setId($row['ticket_id']);
                $ticket->setType($row['ticket_type']);
                $entity->setTicket($ticket);

                $entity->setTakedAction($row['action']);
                $entity->setInteractedAt($row['interacted_at']);
                $this->identityMap[$id] = $entity;
                return $entity;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM CS.case_inter_action_view"
            );
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $entity = new CaseInterAction();
                    $entity->setId($row['id']);

                    $customer = new Customer();
                    $customer->setId($row['customer_id']);
                    $customer->setCountry($row['customer_country']);
                    $customer->setUserId($row['customer_user_id']);
                    $entity->setCustomer($customer);

                    $employee = new Employee();
                    $employee->setId($row['employee_id']);
                    $employee->setEmployeeId($row['hr_employee_id']);
                    $employee->setUserId($row['employee_user_id']);

                    $role = new Role();
                    $role->setId($row['employee_role_id']);
                    $role->setName($row['role_name']);
                    $employee->setRole($role);
                    $entity->setEmployee($employee);

                    $action = new Action();
                    $action->setId($row['action_id']);
                    $action->setAction($row['action']);
                    $action->setTakedAt($row['action_taked_at']);
                    $entity->setAction($action);

                    $status = new Status();
                    $status->setId($row['status_id']);
                    $status->setStatus($row['status']);
                    $status->setIssuedAt($row['status_issued_at']);
                    $entity->setStatus($status);

                    $ticket = new Ticket();
                    $ticket->setId($row['ticket_id']);
                    $ticket->setType($row['ticket_type']);
                    $entity->setTicket($ticket);

                    $entity->setTakedAction($row['action']);
                    $entity->setInteractedAt($row['interacted_at']);
                    $this->identityMap[$row['id']] = $entity;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $query = "SELECT * FROM CS.case_inter_action_view";
        $whereClauses = [];
        $bindings = [];

        if (!empty($criteria['id'])) {
            $whereClauses[] = "id = ?";
            $bindings[] = $criteria['id'];
        }

        if (!empty($criteria['customer_id'])) {
            $whereClauses[] = "customer_id = ?";
            $bindings[] = $criteria['customer_id'];
        }

        if (!empty($criteria['employee_id'])) {
            $whereClauses[] = "employee_id = ?";
            $bindings[] = $criteria['employee_id'];
        }

        if (!empty($criteria['action_id'])) {
            $whereClauses[] = "action_id = ?";
            $bindings[] = $criteria['action_id'];
        }

        if (!empty($criteria['status_id'])) {
            $whereClauses[] = "status_id = ?";
            $bindings[] = $criteria['status_id'];
        }

        if (!empty($criteria['ticket_id'])) {
            $whereClauses[] = "ticket_id = ?";
            $bindings[] = $criteria['ticket_id'];
        }

        if (!empty($criteria['action'])) {
            $whereClauses[] = "action LIKE ?";
            $bindings[] = "%" . $criteria['action'] . "%";
        }

        if (!empty($criteria['interacted_at'])) {
            $whereClauses[] = "interacted_at = ?";
            $bindings[] = $criteria['interacted_at'];
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $entity = new CaseInterAction();
                    $entity->setId($row['id']);

                    $customer = new Customer();
                    $customer->setId($row['customer_id']);
                    $customer->setCountry($row['customer_country']);
                    $customer->setUserId($row['customer_user_id']);
                    $entity->setCustomer($customer);

                    $employee = new Employee();
                    $employee->setId($row['employee_id']);
                    $employee->setEmployeeId($row['hr_employee_id']);
                    $employee->setUserId($row['employee_user_id']);

                    $role = new Role();
                    $role->setId($row['employee_role_id']);
                    $role->setName($row['role_name']);
                    $employee->setRole($role);
                    $entity->setEmployee($employee);

                    $action = new Action();
                    $action->setId($row['action_id']);
                    $action->setAction($row['action']);
                    $action->setTakedAt($row['action_taked_at']);
                    $entity->setAction($action);

                    $status = new Status();
                    $status->setId($row['status_id']);
                    $status->setStatus($row['status']);
                    $status->setIssuedAt($row['status_issued_at']);
                    $entity->setStatus($status);

                    $ticket = new Ticket();
                    $ticket->setId($row['ticket_id']);
                    $ticket->setType($row['ticket_type']);
                    $entity->setTicket($ticket);

                    $entity->setTakedAction($row['action']);
                    $entity->setInteractedAt($row['interacted_at']);
                    $this->identityMap[$row['id']] = $entity;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(CaseInterAction $entity)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.case_interactions(customer_id, employee_id, action_id, status_id, action , ticket_id) VALUES(?, ?, ?, ?, ? , ?) RETURNING id");

            $stmt->execute([
                $entity->getCustomer()->getId(),
                $entity->getEmployee()->getId(),
                $entity->getAction()->getId(),
                $entity->getStatus()->getId(),
                $entity->getTakedAction(),
                $entity->getTicket()->getId()
            ]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

            $entity->setId($id);
            $this->identityMap[$id] = $entity;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(CaseInterAction $entity)
    {
        try {
            $stmt = $this->db->prepare("UPDATE cs.case_interactions SET customer_id = ?, employee_id = ?, action_id = ?, status_id = ?, action = ? , ticket_id = ? WHERE id = ?");
            $stmt->execute([
                $entity->getCustomer()->getId(),
                $entity->getEmployee()->getId(),
                $entity->getAction()->getId(),
                $entity->getStatus()->getId(),
                $entity->getTakedAction(),
                $entity->getTicket()->getId(),
                $entity->getId()
            ]);
            $this->identityMap[$entity->getId()] = $entity;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM cs.case_interactions WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
