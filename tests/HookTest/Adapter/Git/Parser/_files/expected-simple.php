<?php
/**
 * Expected data for simple test.
 */

$aObjects = array(
    array(
        'action'   => 'M',
        'isdir'    => false,
        'item'     => 'file0',
        'real'     => 'file0',
        'ext'      => '',
        'info'     => '',
        'props'    => null,
        'raw-data' => array(
            0 => '100644',
            1 => '100644',
            2 => 'bcd1234...',
            3 => '0123456...',
            4 => 'M',
            5 => 'file0',
        ),
        'txn'      => '',
        'rev'      => '',
        'lines'    => array()
    ),

    array(
        'action'   => 'C',
        'isdir'    => false,
        'item'     => 'file2',
        'real'     => 'file2',
        'ext'      => '',
        'info'     => '',
        'props'    => null,
        'raw-data' => array(
            0 => '100644',
            1 => '100644',
            2 => 'abcd123...',
            3 => '1234567...',
            4 => 'C68',
            5 => 'file1',
            6 => 'file2',
        ),
        'txn'      => '',
        'rev'      => '',
        'lines'    => array()
    ),
    array(
        'action'   => 'R',
        'isdir'    => false,
        'item'     => 'file3',
        'real'     => 'file3',
        'ext'      => '',
        'info'     => '',
        'props'    => null,
        'raw-data' => array(
            0 => '100644',
            1 => '100644',
            2 => 'abcd123...',
            3 => '1234567...',
            4 => 'R86',
            5 => 'file1',
            6 => 'file3',
        ),
        'txn'      => '',
        'rev'      => '',
        'lines'    => array()
    ),
    array(
        'action'   => 'A',
        'isdir'    => false,
        'item'     => 'file4',
        'real'     => 'file4',
        'ext'      => '',
        'info'     => '',
        'props'    => null,
        'raw-data' => array(
            0 => '000000',
            1 => '100644',
            2 => '0000000...',
            3 => '1234567...',
            4 => 'A',
            5 => 'file4',
        ),
        'txn'      => '',
        'rev'      => '',
        'lines'    => array()
    ),
    array(
        'action'   => 'D',
        'isdir'    => false,
        'item'     => 'file5',
        'real'     => 'file5',
        'ext'      => '',
        'info'     => '',
        'props'    => null,
        'raw-data' => array(
            0 => '100644',
            1 => '000000',
            2 => '1234567...',
            3 => '0000000...',
            4 => 'D',
            5 => 'file5'
        ),
        'txn'      => '',
        'rev'      => '',
        'lines'    => array()
    ),
    array(
        'action'   => 'U',
        'isdir'    => false,
        'item'     => 'file6',
        'real'     => 'file6',
        'ext'      => '',
        'info'     => '',
        'props'    => null,
        'raw-data' => array(
            0 => '000000',
            1 => '000000',
            2 => '0000000...',
            3 => '0000000...',
            4 => 'U',
            5 => 'file6',
        ),
        'txn'      => '',
        'rev'      => '',
        'lines'    => array()
    )
);