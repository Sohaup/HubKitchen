<?php
namespace PostApi\modules\CS\domain\valueObjects\types;

enum StatusType : string {
    case ACKNOWLEDGMENT = "Acknowledgment";
    case DIAGNOSIS = "Diagnosis";
    case ACTIONTAKEN = "Action Taken";
    case ARCHIVING = "Archiving";
}