<?php
namespace App\Validator;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailExists extends Constraint
{
    public string $message = 'We dont find any User with this email.';
}