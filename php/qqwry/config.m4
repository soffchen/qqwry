dnl $Id$
dnl config.m4 for extension qqwry

dnl Comments in this file start with the string 'dnl'.
dnl Remove where necessary. This file will not work
dnl without editing.

dnl If your extension references something external, use with:

dnl PHP_ARG_WITH(qqwry, for qqwry support,
dnl Make sure that the comment is aligned:
dnl [  --with-qqwry             Include qqwry support])

dnl Otherwise use enable:

PHP_ARG_ENABLE(qqwry, whether to enable qqwry support,
[  --enable-qqwry           Enable qqwry support])

if test "$PHP_QQWRY" != "no"; then
  dnl Write more examples of tests here...

  dnl # --with-qqwry -> check with-path
  dnl SEARCH_PATH="/usr/local /usr"     # you might want to change this
  dnl SEARCH_FOR="/include/qqwry.h"  # you most likely want to change this
  dnl if test -r $PHP_QQWRY/$SEARCH_FOR; then # path given as parameter
  dnl   QQWRY_DIR=$PHP_QQWRY
  dnl else # search default path list
  dnl   AC_MSG_CHECKING([for qqwry files in default path])
  dnl   for i in $SEARCH_PATH ; do
  dnl     if test -r $i/$SEARCH_FOR; then
  dnl       QQWRY_DIR=$i
  dnl       AC_MSG_RESULT(found in $i)
  dnl     fi
  dnl   done
  dnl fi
  dnl
  dnl if test -z "$QQWRY_DIR"; then
  dnl   AC_MSG_RESULT([not found])
  dnl   AC_MSG_ERROR([Please reinstall the qqwry distribution])
  dnl fi

  dnl # --with-qqwry -> add include path
  dnl PHP_ADD_INCLUDE($QQWRY_DIR/include)

  dnl # --with-qqwry -> check for lib and symbol presence
  dnl LIBNAME=qqwry # you may want to change this
  dnl LIBSYMBOL=qqwry # you most likely want to change this 

  dnl PHP_CHECK_LIBRARY($LIBNAME,$LIBSYMBOL,
  dnl [
  dnl   PHP_ADD_LIBRARY_WITH_PATH($LIBNAME, $QQWRY_DIR/lib, QQWRY_SHARED_LIBADD)
  dnl   AC_DEFINE(HAVE_QQWRYLIB,1,[ ])
  dnl ],[
  dnl   AC_MSG_ERROR([wrong qqwry lib version or lib not found])
  dnl ],[
  dnl   -L$QQWRY_DIR/lib -lm -ldl
  dnl ])
  dnl
  dnl PHP_SUBST(QQWRY_SHARED_LIBADD)

  PHP_NEW_EXTENSION(qqwry, qqwry.c libqqwry/qqwry.c, $ext_shared)
fi
