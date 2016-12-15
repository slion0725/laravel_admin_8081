<?php
namespace App\Models;

class Datatable
{
    public static function complex($request, $table, $primaryKey, $columns, $whereResult = null, $whereAll = null)
    {
        $bindings = array();
        // $db = self::db($conn);
        $localWhereResult = array();
        $localWhereAll = array();
        $whereAllSql = '';

        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        $whereResult = self::_flatten($whereResult);
        $whereAll = self::_flatten($whereAll);

        if ($whereResult) {
            $where = $where ? $where.' AND '.$whereResult : 'WHERE '.$whereResult;
        }

        if ($whereAll) {
            $where = $where ? $where.' AND '.$whereAll : 'WHERE '.$whereAll;
            $whereAllSql = 'WHERE '.$whereAll;
        }

        // Main query to actually get the data
        $data = 'SELECT '.implode(', ', self::pluck($columns, 'db'))." FROM $table $where $order $limit";

        // Data set length after filtering
        $resFilterLength = "SELECT COUNT({$primaryKey}) AS count FROM $table $where";
        // $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = "SELECT COUNT({$primaryKey}) AS count FROM $table ".$whereAllSql;
        // $recordsTotal = $resTotalLength[0][0];

        // SQL
        return array(
            'draw' => isset($request['draw']) ? intval($request['draw']) : 0,
            'recordsTotal' => $resTotalLength,
            'recordsFiltered' => $resFilterLength,
            'data' => $data,
        );

        // return array(
        //     'draw' => isset($request['draw']) ? intval($request['draw']) : 0,
        //     'recordsTotal' => intval($recordsTotal),
        //     'recordsFiltered' => intval($recordsFiltered),
        //     'data' => self::data_output($columns, $data),
        // );
    }

    public static function data_output($columns, $data)
    {
        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; ++$i) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; ++$j) {
                $column = $columns[$j];
                $columndb = $columns[$j]['dt'];
                // Is there a formatter?
                if (isset($column['formatter'])) {
                    $row[ $column['dt'] ] = $column['formatter']($data[$i]->$columndb, $data[$i]);
                } else {
                    $row[ $column['dt'] ] = $data[$i]->$columndb;
                }
            }

            $out[] = $row;
        }

        return $out;
    }

    public static function limit($request, $columns)
    {
        $limit = '';

        if (isset($request['start']) && $request['length'] != -1) {
            $limit = 'LIMIT '.intval($request['start']).', '.intval($request['length']);
        }

        return $limit;
    }

    public static function order($request, $columns)
    {
        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; ++$i) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[ $columnIdx ];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ? 'ASC' : 'DESC';
                    $orderBy[] = $column['db'].' '.$dir;
                }
            }

            $order = 'ORDER BY '.implode(', ', $orderBy);
        }

        return $order;
    }

    public static function filter($request, $columns, &$bindings)
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');

        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];

            for ($i = 0, $ien = count($request['columns']); $i < $ien; ++$i) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[ $columnIdx ];

                if ($requestColumn['searchable'] == 'true') {
                    // $binding = self::bind($bindings, '%'.$str.'%', PDO::PARAM_STR);
                    $binding = '\'%'.$str.'%\'';
                    $globalSearch[] = $column['db'].' LIKE '.$binding;
                }
            }
        }

        // Individual column filtering
        if (isset($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; ++$i) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[ $columnIdx ];

                $str = $requestColumn['search']['value'];

                if ($requestColumn['searchable'] == 'true' &&
                 $str != '') {
                    // $binding = self::bind($bindings, '%'.$str.'%', PDO::PARAM_STR);
                    $binding = '\'%'.$str.'%\'';
                    $columnSearch[] = $column['db'].' LIKE '.$binding;
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if (count($globalSearch)) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where.' AND '.implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = 'WHERE '.$where;
        }

        return $where;
    }

    public static function bind(&$a, $val, $type)
    {
        $key = ':binding_'.count($a);

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type,
        );

        return $key;
    }

    public static function pluck($a, $prop)
    {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; ++$i) {
            $out[] = $a[$i][$prop];
        }

        return $out;
    }

    public static function _flatten($a, $join = ' AND ')
    {
        if (!$a) {
            return '';
        } elseif ($a && is_array($a)) {
            return implode($join, $a);
        }

        return $a;
    }
}
