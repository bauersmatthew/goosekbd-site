CXX := python jj2_compileone.py

SRCS := $(shell find html_src/ -maxdepth 1 -name '*.html')
SRCS_NAMES := $(notdir $(SRCS))
FINALS := $(addprefix www/,$(SRCS_NAMES))

.PHONY: all clean

all: $(FINALS)

www/%.html: html_src/%.html
	$(CXX) $(notdir $<) > $@

clean:
	rm -f www/*.html
