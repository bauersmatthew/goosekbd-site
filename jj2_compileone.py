#!/bin/python
from jinja2 import FileSystemLoader
from jinja2.environment import Environment
from jinja2 import Template
import sys

env = Environment()
env.loader = FileSystemLoader('./html_src/')
tmpl = env.get_template(sys.argv[1])
print tmpl.render()
