<?php
/**
 * Created by PhpStorm.
 * User: bibiz
 * Date: 01-May-20
 * Time: 11:00 PM
 */

namespace App\Models;


class Quandl
{
    public $start_date;
    public $end_date;
    public $name;
    public $column_names=[];
    public $data=[];


    public function getStartDate()
    {
        return $this->start_date;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function getName()
    {
        return $this->name;
    }


    public function getColumnNames()
    {
        return $this->column_names;
    }

    public function getData()
    {
        return $this->data;
    }
}