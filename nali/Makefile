include config.mak
all:nali.c libqqwry/qqwry.c share/nali.sh
	gcc -o bin/qqwrynali nali.c libqqwry/qqwry.c
	cp share/nali.sh bin/nali
	cp share/nali.pl bin/nali.pl
	sed -i -e 's|__DATADIR|$(DESTDIR)$(datadir)|g' bin/nali
	sed -i -e 's|__QQWRYNALI|$(DESTDIR)$(bindir)/qqwrynali|g' bin/nali.pl
install:bin share
	install -d -m 0755 $(DESTDIR)$(bindir) 
	install -d -m 0755 $(DESTDIR)$(prefix) 
	install -d -m 0755 $(DESTDIR)$(datadir) 
	install bin/qqwrynali $(DESTDIR)$(bindir)/qqwrynali 
	install bin/nali $(DESTDIR)$(bindir)/nali
	install bin/nali-traceroute $(DESTDIR)$(bindir)/nali-traceroute
	install bin/nali-tracepath $(DESTDIR)$(bindir)/nali-tracepath 
	install bin/nali-dig $(DESTDIR)$(bindir)/nali-dig
	install bin/nali-nslookup $(DESTDIR)$(bindir)/nali-nslookup 
	install bin/nali-ping $(DESTDIR)$(bindir)/nali-ping 
	install bin/nali-update $(DESTDIR)$(bindir)/nali-update 
	install share/QQWry.Dat $(DESTDIR)$(datadir)/QQWry.Dat
	install bin/nali.pl $(DESTDIR)$(datadir)/nali.pl
distclean: clean
	rm -f config.mak
	rm -f config.h
clean:
	rm -f bin/qqwrynali
	rm -f bin/nali
