CXX := python jj2_compileone.py

SRCS := $(shell find html_src/ -maxdepth 1 -name '*.php')
SRCS_NAMES := $(notdir $(SRCS))
FINALS := $(addprefix www/,$(SRCS_NAMES))
ZIPF := www.zip

.PHONY: all clean html zip

all: $(FINALS) $(ZIPF)

html: $(FINALS)

www/%.php: html_src/%.php
	$(CXX) $(notdir $<) > $@

zip: $(ZIPF)

$(ZIPF): www
	rm -f www.zip
	zip -r www www

clean:
	rm -f www/*.php
	rm -f www.zip
