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
  | Author:Surf Chen <surfchen@gmail.com>                                |
  +----------------------------------------------------------------------+
*/

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "errno.h"
#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_qqwry.h"
#include "libqqwry/qqwry.h"

#define QQWRY_ADDR1_LEN 64
#define QQWRY_ADDR2_LEN 128 

ZEND_DECLARE_MODULE_GLOBALS(qqwry)

/* True global resources - no need for thread safety here */
static zend_class_entry *qqwry_class_entry_ptr = NULL;

/* {{{ proto string qqwry(string qqwry_path)
*/
PHP_METHOD(qqwry,__construct)
{
	char *qqwry_path = NULL;
	int qqwry_len;
    zval * _this_zval = NULL;
    if (zend_parse_method_parameters(ZEND_NUM_ARGS() TSRMLS_CC,getThis(), "Os",&_this_zval,qqwry_class_entry_ptr, &qqwry_path,&qqwry_len) == FAILURE) {
        return;
    }
	add_property_string(_this_zval,"f",qqwry_path,1);
}
/* }}} */

/* {{{ proto string q(string arg)
   Return Array,of which the 1st value is addr1 and the 2nd value is addr2*/
PHP_METHOD(qqwry,q)
{
	char *ip_string = NULL;
	int ipstring_len;
    zval * _this_zval = NULL;

    if (zend_parse_method_parameters(ZEND_NUM_ARGS() TSRMLS_CC,getThis(), "Os",&_this_zval,qqwry_class_entry_ptr, &ip_string, &ipstring_len) == FAILURE) {
        return;
    }   
	zval *zaddr1,*zaddr2;
	char *addr1=(char *)emalloc(QQWRY_ADDR1_LEN);
	char *addr2=(char *)emalloc(QQWRY_ADDR2_LEN);
	memset(addr1,0,QQWRY_ADDR1_LEN);
	memset(addr2,0,QQWRY_ADDR2_LEN);
	zval **tmp;
	char *qqwry_path;
	if (zend_hash_find(Z_OBJPROP_P(_this_zval),"f",sizeof("f"),(void **)&tmp)==FAILURE) {
		return;
	}
	qqwry_path=Z_STRVAL_PP(tmp);

	FILE *fp=NULL;
    qqwry_fp_list *qfl=QQWRY_G(fp_list);
	while (qfl) {
		if (!strcmp(qfl->filepath,qqwry_path)) {
			fp=qfl->fp;
			break;
		}
		qfl=qfl->next;
	}

	if (!fp) {
		qqwry_fp_list *pre_qfl=NULL;
		qfl=QQWRY_G(fp_list);
		while (qfl) {
			pre_qfl=qfl;
			qfl=qfl->next;
		}
		fp=fopen(qqwry_path,"rb");
		if (!fp) {
			php_error_docref(NULL TSRMLS_CC, E_WARNING,"%s",strerror(errno));
			return;
		}
		qfl=emalloc(sizeof(qqwry_fp_list));
		qfl->filepath = estrndup(qqwry_path, strlen(qqwry_path));
		qfl->fp=fp;
		qfl->next=NULL;
		if (pre_qfl) {
			pre_qfl->next=qfl;
		} else {
			QQWRY_G(fp_list)=qfl;
		}
	}

	qqwry_get_location(addr1,addr2,ip_string,fp);
	MAKE_STD_ZVAL(zaddr1);
	ZVAL_STRING(zaddr1,addr1,0);
	MAKE_STD_ZVAL(zaddr2);
	ZVAL_STRING(zaddr2,addr2,0);

	array_init(return_value);
    add_next_index_zval(return_value,zaddr1);
    add_next_index_zval(return_value,zaddr2);
}
/* }}} */



/* {{{ qqwry_functions[]
 */
zend_function_entry qqwry_functions[] = {
	{NULL, NULL, NULL}	/* Must be the last line in qqwry_functions[] */
};
/* }}} */

/* {{{ qqwry_methods[]
 */
static zend_function_entry php_qqwry_class_functions[] = {
	PHP_ME(qqwry, __construct, NULL, ZEND_ACC_PUBLIC)
	PHP_ME(qqwry, q, NULL, ZEND_ACC_PUBLIC)
	{NULL, NULL, NULL}	/* Must be the last line in qqwry_functions[] */
};
/* }}} */


/* {{{ qqwry_module_entry
 */
zend_module_entry qqwry_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
	STANDARD_MODULE_HEADER,
#endif
	"qqwry",
	qqwry_functions,
	PHP_MINIT(qqwry),
	PHP_MSHUTDOWN(qqwry),
	PHP_RINIT(qqwry),		/* Replace with NULL if there's nothing to do at request start */
	PHP_RSHUTDOWN(qqwry),	/* Replace with NULL if there's nothing to do at request end */
	PHP_MINFO(qqwry),
#if ZEND_MODULE_API_NO >= 20010901
	"0.1", /* Replace with version number for your extension */
#endif
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_QQWRY
ZEND_GET_MODULE(qqwry)
#endif



/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(qqwry)
{
#ifdef ZTS
    ZEND_INIT_MODULE_GLOBALS(qqwry,NULL,NULL);
#endif
    zend_class_entry qqwry_class_entry;
    INIT_CLASS_ENTRY(qqwry_class_entry, "qqwry", php_qqwry_class_functions);
	qqwry_class_entry_ptr = zend_register_internal_class(&qqwry_class_entry TSRMLS_CC);
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MSHUTDOWN_FUNCTION
 */
PHP_MSHUTDOWN_FUNCTION(qqwry)
{
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_RINIT_FUNCTION
 */
PHP_RINIT_FUNCTION(qqwry)
{
    QQWRY_G(fp_list)=NULL;
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_RSHUTDOWN_FUNCTION
 */
PHP_RSHUTDOWN_FUNCTION(qqwry)
{
    qqwry_fp_list *qfl=QQWRY_G(fp_list);
    qqwry_fp_list *pre_qfl=NULL;
	while (qfl) {
		pre_qfl=qfl;
		qfl=qfl->next;
		fclose(pre_qfl->fp);
		efree(pre_qfl->filepath);
		efree(pre_qfl);
	}
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(qqwry)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "qqwry support", "enabled");
	php_info_print_table_end();
}
/* }}} */

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
