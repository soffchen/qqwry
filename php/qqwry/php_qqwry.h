/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2007 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author:                                                              |
  +----------------------------------------------------------------------+
*/

/* $Id: header,v 1.16.2.1.2.1 2007/01/01 19:32:09 iliaa Exp $ */

#ifndef PHP_QQWRY_H
#define PHP_QQWRY_H

extern zend_module_entry qqwry_module_entry;
#define phpext_qqwry_ptr &qqwry_module_entry

#ifdef PHP_WIN32
#define PHP_QQWRY_API __declspec(dllexport)
#else
#define PHP_QQWRY_API
#endif

#ifdef ZTS
#include "TSRM.h"
#endif

PHP_MINIT_FUNCTION(qqwry);
PHP_MSHUTDOWN_FUNCTION(qqwry);
PHP_RINIT_FUNCTION(qqwry);
PHP_RSHUTDOWN_FUNCTION(qqwry);
PHP_MINFO_FUNCTION(qqwry);

PHP_METHOD(qqwry, __construct);
ZEND_BEGIN_ARG_INFO_EX(qqwry____construct_args, ZEND_SEND_BY_VAL, ZEND_RETURN_VALUE, 0)
ZEND_END_ARG_INFO()

PHP_METHOD(qqwry, q);
ZEND_BEGIN_ARG_INFO_EX(qqwry__q_args, ZEND_SEND_BY_VAL, ZEND_RETURN_VALUE, 0)
ZEND_END_ARG_INFO()

typedef struct _qqwry_fp_list{
	FILE *fp;
	char *filepath;
	struct _qqwry_fp_list* next;
}qqwry_fp_list;

/* 
  	Declare any global variables you may need between the BEGIN
	and END macros here:     
*/

ZEND_BEGIN_MODULE_GLOBALS(qqwry)
	qqwry_fp_list  *fp_list;
ZEND_END_MODULE_GLOBALS(qqwry)

/* In every utility function you add that needs to use variables 
   in php_qqwry_globals, call TSRMLS_FETCH(); after declaring other 
   variables used by that function, or better yet, pass in TSRMLS_CC
   after the last function argument and declare your utility function
   with TSRMLS_DC after the last declared argument.  Always refer to
   the globals in your function as QQWRY_G(variable).  You are 
   encouraged to rename these macros something shorter, see
   examples in any other php module directory.
*/

#ifdef ZTS
#define QQWRY_G(v) TSRMG(qqwry_globals_id, zend_qqwry_globals *, v)
#else
#define QQWRY_G(v) (qqwry_globals.v)
#endif

#endif	/* PHP_QQWRY_H */


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
