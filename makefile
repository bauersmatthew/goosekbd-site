CXX := python jj2_compileone.py

SRCS := $(shell find html_src/ -maxdepth 1 -name '*.php')
SRCS_NAMES := $(notdir $(SRCS))
TEMPLATES := $(shell find html_src/templates/ -name '*.html')
FINALS := $(addprefix www/,$(SRCS_NAMES))
WWW_CONTENTS := $(shell find www/ -name '*')
ZIPF := www.zip

.PHONY: all clean html zip

html: $(FINALS)

www/%.php: html_src/%.php $(TEMPLATES)
	$(CXX) $(notdir $<) > $@

zip: $(ZIPF)

$(ZIPF): $(WWW_CONTENTS)
	rm -f www.zip
	zip -r www www

clean:
	rm -f www/*.php
	rm -f www.zip
	
all: html zip

