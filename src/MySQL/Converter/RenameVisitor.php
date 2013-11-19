<?php
namespace MySQL\Converter;

use PHPParser_NodeVisitorAbstract as AbstractVisitor;
use PHPParser_Node as Node;
use PHPParser_Node_Expr_FuncCall as FuncCallNode;

/**
 * Visitor for automatic renaming of mysql_* functions.
 */
class RenameVisitor extends AbstractVisitor
{
    protected $mysqlFunctions = array('mysql_affected_rows', 'mysql_client_encoding', 'mysql_close', 'mysql_connect',
        'mysql_create_db', 'mysql_data_seek', 'mysql_db_name', 'mysql_db_query', 'mysql_drop_db', 'mysql_errno',
        'mysql_error', 'mysql_escape_string', 'mysql_fetch_array', 'mysql_fetch_assoc', 'mysql_fetch_field',
        'mysql_fetch_lengths', 'mysql_fetch_object', 'mysql_fetch_row', 'mysql_field_flags', 'mysql_field_len',
        'mysql_field_name', 'mysql_field_seek', 'mysql_field_table', 'mysql_field_type', 'mysql_free_result',
        'mysql_get_client_info', 'mysql_get_host_info', 'mysql_get_proto_info', 'mysql_get_server_info', 'mysql_info',
        'mysql_insert_id', 'mysql_list_dbs', 'mysql_list_fields', 'mysql_list_processes', 'mysql_list_tables',
        'mysql_num_fields', 'mysql_num_rows', 'mysql_pconnect', 'mysql_ping', 'mysql_query', 'mysql_real_escape_string',
        'mysql_result', 'mysql_select_db', 'mysql_set_charset', 'mysql_stat', 'mysql_tablename', 'mysql_thread_id',
        'mysql_unbuffered_query'
    );

    public function leaveNode(Node $node)
    {
        parent::leaveNode($node);
        if (!$node instanceof FuncCallNode) {
            return;
        }
        /* @var $node FuncCallNode */
        if (!in_array($node->name->toString(), $this->mysqlFunctions)) {
            return;
        }
        $node->name->set(str_replace('mysql_', '\\MySQL\\Proxy::', $node->name->toString()));
    }
}
