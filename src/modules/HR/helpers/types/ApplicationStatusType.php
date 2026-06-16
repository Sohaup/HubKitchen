<?php
namespace PostApi\modules\HR\helpers\types;

enum ApplicationStatusType : string {
    case APPLIED = "Applied";
    case SCREENING = "Screening";
    case SHORTLISTED = "Shortlisted";
    case INTERVIEWING = "Interviewing";
    case OFFERED = "Offered";
    case REJECTED = "Rejected";
    case WITHDRAWN = "Withdrawn";
}