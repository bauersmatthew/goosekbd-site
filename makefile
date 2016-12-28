CXX := python jj2_compileone.py

SRCS := $(shell find html_src/ -maxdepth 1 -name '*.php')
SRCS_NAMES := $(notdir $(SRCS))
TEMPLATES := $(shell find html_src/templates/ -name '*.html')
FINALS := $(addprefix www/,$(SRCS_NAMES))
ZIPF := www.zip

.PHONY: all clean html zip

all: html zip

html: $(FINALS)

www/%.php: html_src/%.php $(TEMPLATES)
	$(CXX) $(notdir $<) > $@
	touch www

zip: $(ZIPF)

$(ZIPF): www
	rm -f www.zip
	zip -r www www

www: html

clean:
	rm -f www/*.php
	rm -f www.zip
