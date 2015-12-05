#!/bin/sh
if test $# -gt 0
then
    echo $@|perl __DATADIR/nali.pl
else
    perl __DATADIR/nali.pl
fi
