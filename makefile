CXX := python jj2_compileone.py

SRCS := $(shell find html_src/ -maxdepth 1 -name '*.html')
SRCS_NAMES := $(notdir $(SRCS))
FINALS := $(addprefix www/,$(SRCS_NAMES))
ZIPF := www.zip

.PHONY: all clean html zip

all: $(FINALS) $(ZIPF)

html: $(FINALS)

www/%.html: html_src/%.html
	$(CXX) $(notdir $<) > $@

zip: $(ZIPF)

$(ZIPF):
	rm -f www.zip
	zip -r www www

clean:
	rm -f www/*.html
	rm -f www.zip
