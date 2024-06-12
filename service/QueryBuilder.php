<?php

/**
 * Class QueryBuilder
 *
 * This class is used to convert array into sql statements
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last Modified : 03-06-2024
 */

abstract class QueryBuilder
{
    /**
     * Converts array into sql insert statements
     *
     * @param $data array
     *
     * @return string
     */
    public function arrayToInsert(array $data): string
    {
        return "(" . implode(",", array_keys($data)) . ") VALUES('" . implode("','", array_values($data)) . "')";
    }

    /**
     * Converts array of column names into sql format column parameter
     *
     * @param array $columns
     *
     * @return string
     */
    public function arrayToColumns(array $columns): string
    {
        return "(" . implode(",", $columns) . ")";
    }

    /**
     * Converts array into sql set statements
     *
     * @param $data array
     *
     * @return string
     */
    public function setValues(array $data): string
    {
        $str = "SET ";
        foreach ($data as $key => $value) {
            $str .= $key . "= '" . $value . "' ,";
        }

        return substr($str, 0, strlen($str) - 1);
    }

    /**
     * Converts array into sql conditional statement
     *
     * @param $data array
     *
     * @return string
     */
    public function arrayToCondition(array $data): string
    {
        $str = "WHERE ";
        $conditions = [];

        foreach ($data as $key => $value) {
            if ($key == "condition") {
                $str = $str . " $value ";
                continue;
            }

            if (is_array($value)) {
                $conditions[] = $key . " IN (" . implode(",", $value) . ")";
            } else {
                $conditions[] = $key . "=" . "'" . $value . "'";
            }
        }

        $str .= implode(" AND ", $conditions);

        return $str;
    }

    /**
     * Convert array to select columns
     * 
     * @param array $columns
     * 
     * @return string
     */
    public function arrayToSelect(array $columns): string
    {
        return implode(",", $columns);
    }
}
